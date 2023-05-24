<?php

namespace Vng\EvaCore\Commands\Geo;

use Vng\EvaCore\Exceptions\GeoCommandException;
use Vng\EvaCore\Models\Township;
use Vng\EvaCore\Services\GeoComparison\TownshipComparison;
use Vng\EvaCore\Services\GeoComparison\TownshipDataComparisonService;
use Vng\EvaCore\Services\GeoData\BasicTownshipModel;
use Vng\EvaCore\Services\GeoData\TownshipDataService;
use Vng\EvaCore\Services\GeoData\TownshipService;
use Vng\EvaCore\Services\TownshipReallocationService;

class TownshipsUpdateDataFromSource extends GeoCheckCommand
{
    protected $signature = 'geo:townships-update-data-from-source {--yes-to-all}';
    protected $description = 'Update township data from source file';

    protected string $labelCollectionA = 'Current';
    protected string $labelCollectionB = 'Source';

    protected $sourceData;
    protected bool $yesToAll;

    public function handle(): int
    {
        $this->yesToAll = (bool) $this->option('yes-to-all');

        $this->output->writeln('updating townships');
        $this->output->writeln('');

        $data = TownshipDataService::loadSourceData();
        $this->sourceData = TownshipDataService::createBasicGeoCollectionFromData($data);

        $this->call('geo:integrity');

        $this->output->writeln('checking for townships missing from database');
        $this->checkForMissingTownships();
        $this->output->writeln('- checked');
        $this->output->writeln('');

        $this->output->writeln('checking for townships no longer in source');
        $result = $this->checkForRemovedTownships();
        if ($result === 1) {
            return 1;
        }
        $this->output->writeln('- checked');
        $this->output->writeln('');

        $this->output->writeln('checking townships content changes');
        $this->checkTownshipContent();
        $this->output->writeln('- checked');
        $this->output->writeln('');

        $this->output->writeln('updating townships finished!');
        $this->output->writeln('');
        $this->output->writeln('');
        return 0;
    }

    public function checkForMissingTownships()
    {
        $comparisonService = TownshipDataComparisonService::createWithDatabaseCollection($this->sourceData);
        $missingTownships = $comparisonService->findItemsNotInCollectionA();
        $this->handleMissingItems($missingTownships, function (BasicTownshipModel $geoModel) {
            $township = TownshipService::createTownship($geoModel);
            $this->output->writeln('township created with id ['. $township->id .']');
        });
    }

    public function checkForRemovedTownships(): int
    {
        $comparisonService = TownshipDataComparisonService::createWithDatabaseCollection($this->sourceData);
        $abandonedTownships = $comparisonService->findItemsNotInCollectionB();

        if ($this->yesToAll) {
            // Check if any missing township has connected items
            try {
                $this->handleMissingItems($abandonedTownships, function (BasicTownshipModel $geoModel) {
                    $abandonedTownship = $geoModel->getSourceTownship();
                    $localPartiesCount = $abandonedTownship->localParties()->count();
                    $neighbourhoodCount = $abandonedTownship->neighbourhoods()->count();
                    if ($localPartiesCount > 0 || $neighbourhoodCount > 0) {
                        throw new GeoCommandException('Cannot handle all missing townships');
                    }
                });
            } catch (GeoCommandException $e) {
                $this->output->newLine(2);
                $this->output->caution('Cannot handle all missing townships');
                $this->output->writeln('Some local parties and/or partnerships are connected to the township up for removal');
                $this->output->writeln('Connect these organisations to the right township before running this command again');
                $this->output->newLine(2);
                return 1;
            }
        }

        $this->handleMissingItems($abandonedTownships, function (BasicTownshipModel $geoModel) {
            $abandonedTownship = $geoModel->getSourceTownship();

            $localPartiesCount = $abandonedTownship->localParties()->count();
            $neighbourhoodCount = $abandonedTownship->neighbourhoods()->count();

            if ($localPartiesCount > 0 || $neighbourhoodCount > 0) {
                $this->output->writeln('Township is connected to :');
                $this->output->writeln($localPartiesCount . ' local parties');
                $this->output->writeln($neighbourhoodCount . ' neighbourhoods');

                if ($this->confirm('Reallocate data before removal?', true)) {
                    $slug = $this->askWithCompletion('Transfer to which township?', Township::all()->pluck('slug')->toArray());
                    /** @var Township $targetTownship */
                    $targetTownship = Township::query()->where('slug', $slug)->firstOrFail();

                    if ($this->confirm('Transfer data to ' . $targetTownship->name . ' [' . $targetTownship->code . '] ?')) {
                        TownshipReallocationService::transfer($abandonedTownship, $targetTownship);
                        $this->output->writeln('reallocated!');
                    }
                }
            } else {
                $this->output->writeln('Township currently has no relations');
            }

            $localPartiesCount = $abandonedTownship->localParties()->count();
            $neighbourhoodCount = $abandonedTownship->neighbourhoods()->count();
            $notRelated = $localPartiesCount === 0 && $neighbourhoodCount === 0;
            if ($notRelated || $this->confirm('Township is still related - remove anyway?')) {
                TownshipService::deleteTownship($abandonedTownship);
                $this->output->writeln('- removed');
            }
        });

        return 0;
    }

    public function checkTownshipContent()
    {
        $comparisonService = TownshipDataComparisonService::createWithDatabaseCollection($this->sourceData);
        $this->checkDeviatingItems($comparisonService, function(TownshipComparison $comparison) {
            $townshipModel = $comparison->getModelB();
            if (!is_null($townshipModel)){
                TownshipService::updateTownship($townshipModel);
            }
        });
    }
}
