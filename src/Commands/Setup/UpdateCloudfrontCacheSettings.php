<?php

namespace Vng\EvaCore\Commands\Setup;

use Aws\AwsClientInterface;
use Aws\Laravel\AwsFacade;
use Illuminate\Console\Command;

class UpdateCloudfrontCacheSettings extends Command
{
    protected $signature = 'eva-core:update-cloudfront';
    protected $description = 'Setup or update the Cloudfront cache settings';

    public function handle(): int
    {
        $this->info("\n[ Setting up CloudFront distribution ]\n");

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

        // Voeg de nieuwe cache behavior toe
        $newCacheBehavior = [
            'PathPattern' => '/downloads/40-rheden/*',
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

        $this->info('CloudFront distribution updated successfully.');
        return 0;
    }
}
