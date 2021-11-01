<?php

namespace Vng\EvaCore\Commands\Geo;

use Vng\EvaCore\Services\GeoComparison\TownshipDataComparisonService;
use Illuminate\Support\Collection;

class TownshipsCheckDataFromSource extends GeoCheckCommand
{
    protected $signature = 'geo:townships-check-data-from-source';
    protected $description = 'Check township data compared to source file';

    protected string $labelCollectionA = 'Current';
    protected string $labelCollectionB = 'Source';

    public function handle(): int
    {
        $this->output->writeln('check database data from source data..');
        $this->output->writeln('');

        $this->call('geo:integrity');

        $geoCollection = TownshipDataComparisonService::getSourceCollection();

        $this->output->writeln('checking data entries');
        $this->checkForMissingItems($geoCollection);
        $this->output->writeln('');

        $this->output->writeln('checking data entries content');
        $this->checkForDeviation($geoCollection);
        $this->output->writeln('');

        $this->output->writeln('check database data from source data finished!');
        $this->output->writeln('');
        $this->output->writeln('');
        return 0;
    }

    public function checkForMissingItems(Collection $testData)
    {
        $comparisonService = TownshipDataComparisonService::createWithDatabaseCollection($testData);
        $this->checkItemsMissingFromCollectionA($comparisonService);
        $this->checkItemsMissingFromCollectionB($comparisonService);
    }

    public function checkForDeviation(Collection $testData)
    {
        $comparisonService = TownshipDataComparisonService::createWithDatabaseCollection($testData);
        $this->checkDeviatingItems($comparisonService);
    }
}
