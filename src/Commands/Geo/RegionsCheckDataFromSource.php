<?php

namespace Vng\EvaCore\Commands\Geo;

use Vng\EvaCore\Services\GeoComparison\RegionDataComparisonService;
use Illuminate\Support\Collection;

class RegionsCheckDataFromSource extends GeoCheckCommand
{
    protected $signature = 'geo:regions-check-data-from-source';
    protected $description = 'Check region data compared to source file';

    protected string $labelCollectionA = 'Current';
    protected string $labelCollectionB = 'Source';

    public function handle(): int
    {
        $this->output->writeln('checking for internal region changes..');
        $this->output->writeln('');

        $this->call('geo:integrity');

        $geoCollection = RegionDataComparisonService::getSourceCollection();

        $this->output->writeln('checking data entries');
        $this->checkForMissingItems($geoCollection);
        $this->output->writeln('');

        $this->output->writeln('checking data entries content');
        $this->checkForContentDeviation($geoCollection);
        $this->output->writeln('');

        $this->output->writeln('checking for internal region changes finished!');
        $this->output->writeln('');
        $this->output->writeln('');
        return 0;
    }

    public function checkForMissingItems(Collection $testData)
    {
        $comparisonService = RegionDataComparisonService::createWithDatabaseCollection($testData);
        $this->checkItemsMissingFromCollectionA($comparisonService);
        $this->checkItemsMissingFromCollectionB($comparisonService);
    }

    public function checkForContentDeviation(Collection $testData)
    {
        $comparisonService = RegionDataComparisonService::createWithDatabaseCollection($testData);
        $this->checkDeviatingItems($comparisonService);
    }
}
