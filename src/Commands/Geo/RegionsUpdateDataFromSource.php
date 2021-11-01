<?php

namespace Vng\EvaCore\Commands\Geo;

use Vng\EvaCore\Exceptions\GeoCommandException;
use Vng\EvaCore\Models\Region;
use Vng\EvaCore\Services\GeoComparison\RegionComparison;
use Vng\EvaCore\Services\GeoComparison\RegionDataComparisonService;
use Vng\EvaCore\Services\GeoData\BasicRegionModel;
use Vng\EvaCore\Services\GeoData\RegionDataService;
use Vng\EvaCore\Services\GeoData\RegionService;
use Vng\EvaCore\Services\ReallocationService;

class RegionsUpdateDataFromSource extends GeoCheckCommand
{
    protected $signature = 'geo:regions-update-data-from-source {--yes-to-all}';
    protected $description = 'Update region data from source file';

    protected string $labelCollectionA = 'Current';
    protected string $labelCollectionB = 'Source';

    protected $sourceData;
    protected bool $yesToAll;

    public function handle(): int
    {
        $this->yesToAll = (bool) $this->option('yes-to-all');

        $this->output->writeln('updating regions');
        $this->output->writeln('');

        $data = RegionDataService::loadData();
        $this->sourceData = RegionDataService::createBasicGeoCollectionFromData($data);

        $this->call('geo:integrity');

        $this->output->writeln('checking for regions no longer in source');
        $result = $this->checkForRemovedRegions();
        if ($result === 1) {
            return 1;
        }
        $this->output->writeln('- checked');
        $this->output->writeln('');

        $this->output->writeln('checking for regions missing from database');
        $this->checkForMissingRegions();
        $this->output->writeln('- checked');
        $this->output->writeln('');

        $this->output->writeln('checking for regions with deviating content');
        $this->checkRegionBasicContent();
        $this->output->writeln('- checked');
        $this->output->writeln('');

        $this->output->writeln('updating regions finished!');
        $this->output->writeln('');
        $this->output->writeln('');
        return 0;
    }

    public function checkForMissingRegions()
    {
        $comparisonService = RegionDataComparisonService::createWithDatabaseCollection($this->sourceData);
        $missingRegions = $comparisonService->findItemsNotInCollectionA();
        $this->handleMissingItems($missingRegions, function(BasicRegionModel $geoModel) {
            $region = RegionService::createRegion($geoModel);
            $this->output->writeln('region created with id ['. $region->id .']');
        });
    }

    public function checkForRemovedRegions(): int
    {
        $comparisonService = RegionDataComparisonService::createWithDatabaseCollection($this->sourceData);
        $abandonedRegions = $comparisonService->findItemsNotInCollectionB();

        if ($this->yesToAll) {
            // Check if any missing regions has connected items
            try {
                $this->handleMissingItems($abandonedRegions, function (BasicRegionModel $geoModel) {
                    $abandonedRegion = $geoModel->getSourceRegion();
                    if ($abandonedRegion->ownedItemsCount() > 0) {
                        throw new GeoCommandException('Cannot handle all missing regions');
                    }
                });
            } catch (GeoCommandException $e) {
                $this->output->newLine(2);
                $this->output->caution('Cannot handle all missing regions');
                $this->output->writeln('Reallocate items of removed regions before running this command again');
                $this->output->newLine(2);
                return 1;
            }
        }

        $this->handleMissingItems($abandonedRegions, function (BasicRegionModel $geoModel) {
            $abandonedRegion = $geoModel->getSourceRegion();
            if ($abandonedRegion->ownedItemsCount() > 0) {
                $this->output->writeln('Region owns' . $abandonedRegion->ownedItemsCount() . ' items');
                if ($this->confirm('Reallocate data before removal?', true)) {
                    $slug = $this->askWithCompletion('Transfer to which region?', Region::all()->pluck('slug')->toArray());
                    $targetRegion = Region::query()->where('slug', $slug)->firstOrFail();
                    if ($this->confirm('Transfer data to ' . $targetRegion->name . ' [' . $targetRegion->code . '] ?')) {
                        ReallocationService::transferOwnership($abandonedRegion, $targetRegion);
                        $this->output->writeln('reallocated!');
                    }
                }
            }

            if ($abandonedRegion->ownedItemsCount() === 0 || $this->confirm('Region still owns items - remove anyway?')) {
                RegionService::deleteRegion($abandonedRegion);
                $this->output->writeln('- removed');
            }
        });

        return 0;
    }

    public function checkRegionBasicContent()
    {
        $comparisonService = RegionDataComparisonService::createWithDatabaseCollection($this->sourceData);
        $this->checkDeviatingItems($comparisonService, function (RegionComparison $comparison) {
            $regionModel = $comparison->getModelB();
            if (!is_null($regionModel)){
                RegionService::updateRegion($regionModel);
            }
        });
    }
}
