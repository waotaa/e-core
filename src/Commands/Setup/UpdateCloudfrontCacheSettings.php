<?php

namespace Vng\EvaCore\Commands\Setup;

use Aws\AwsClientInterface;
use Aws\Laravel\AwsFacade;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Vng\EvaCore\Models\Organisation;
use Vng\EvaCore\Repositories\OrganisationRepositoryInterface;
use Vng\EvaCore\Services\Storage\DownloadStorageService;

class UpdateCloudfrontCacheSettings extends Command
{
    protected $signature = 'eva-core:update-cloudfront
                            {--o|org= : Option; if given execute command for specified organisation}';

    protected $description = 'Setup or update the Cloudfront cache settings';

    public function handle(): int
    {
        $this->info("\n[ Setting up CloudFront distribution ]\n");

        $organisations = $this->getOrganisations();

        foreach ($organisations as $organisation) {
            $this->addCacheBehaviourForOrganisation($organisation);
        }

        $this->info('CloudFront distribution updated successfully.');
        return 0;
    }

    protected function getOrganisations(): Collection
    {
        /** @var OrganisationRepositoryInterface $orgRepo */
        $orgRepo = app(OrganisationRepositoryInterface::class);
        $organisationOption = $this->option('org');
        if ($organisationOption) {
            if (is_numeric($organisationOption)) {
                return new Collection([
                    $orgRepo->find($organisationOption)
                ]);
            } else {
                return $orgRepo->addSlugCondition($orgRepo->builder(), $organisationOption)->get();
            }
        }
        return $orgRepo->all();
    }

    protected function addCacheBehaviourForOrganisation(Organisation $organisation)
    {
        /** @var AwsClientInterface $awsClient */
        // Verkrijg de CloudFront client
        $cloudFrontClient = AwsFacade::createClient('cloudfront');

        // Specificeer de Distribution ID die je wilt updaten
        $distributionId = config('aws.services.cloudfront.distribution_id');
        if (is_null($distributionId)) {
            throw new \Exception('No distribution id found');
        }

        $targetOriginId = config('aws.services.cloudfront.target_origin_id');
        if (is_null($targetOriginId)) {
            throw new \Exception('No target origin id found');
        }

        // Verkrijg de huidige distributieconfiguratie
        $result = $cloudFrontClient->getDistribution([
            'Id' => $distributionId
        ]);

        // Haal de configuratie en het ETag op
        $distributionConfig = $result['Distribution']['DistributionConfig'];
        $eTag = $result['ETag'];

        $downloadStorageService = new DownloadStorageService();
        $downloadStorageService->setOrganisation($organisation);
        $storageDir = $downloadStorageService->getStorageDirectory();

        // Voeg de nieuwe cache behavior toe
        $newCacheBehavior = [
            'PathPattern' => "{$storageDir}/*",
            'TargetOriginId' => $targetOriginId,
            'TrustedSigners' => [
                'Enabled' => false,
                'Quantity' => 0
            ],
            'TrustedKeyGroups' => [
                'Enabled' => false,
                'Quantity' => 0
            ],
            'ViewerProtocolPolicy' => 'redirect-to-https',
            'AllowedMethods' => [
                'Quantity' => 2,
                'Items' => ['HEAD', 'GET'],
                'CachedMethods' => [
                    'Quantity' => 2,
                    'Items' => ['HEAD', 'GET']
                ]
            ],
            'SmoothStreaming' => false,
            'Compress' => true,
            'LambdaFunctionAssociations' => [
                'Quantity' => 0
            ],
            'FunctionAssociations' => [
                'Quantity' => 0
            ],
            'FieldLevelEncryptionId' => '',
            'CachePolicyId' => '658327ea-f89d-4fab-a63d-7e88639e58f6',
        ];

        // Voeg de nieuwe cache behavior toe aan de huidige configuratie
        if (!isset($distributionConfig['CacheBehaviors']['Items'])) {
            $distributionConfig['CacheBehaviors'] = [
                'Quantity' => 1,
                'Items' => [$newCacheBehavior]
            ];
        } else {
            $distributionConfig['CacheBehaviors']['Items'][] = $newCacheBehavior;
            $distributionConfig['CacheBehaviors']['Quantity']++;
        }

        // Voer de update uit
        $cloudFrontClient->updateDistribution([
            'Id' => $distributionId,
            'IfMatch' => $eTag,
            'DistributionConfig' => $distributionConfig,
        ]);
    }
}
