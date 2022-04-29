<?php

namespace Vng\EvaCore\Commands\Geo;

use Illuminate\Support\Collection;
use Vng\EvaCore\Services\GeoApi\CbsAreaRegionApiService;
use Vng\EvaCore\Services\GeoApi\CbsOpenDataApiService;
use Vng\EvaCore\Services\GeoComparison\RegionDataComparisonService;
use Vng\EvaCore\Services\GeoData\RegionDataService;

class RegionsCheckDataFromApi extends GeoCheckCommand
{
    protected $signature = 'geo:regions-check-data-from-api';
    protected $description = 'Check region data compared to api results';

    protected string $labelCollectionA = 'Current';
    protected string $labelCollectionB = 'API';

    public function handle(): int
    {
        $this->output->writeln('check database data from api results..');
        $this->output->writeln('');

        $this->call('geo:integrity');

        $this->output->writeln('Checking CBS Open Data API');
        $this->checkCbsOpenDataRegions();
        $this->output->writeln('');

        if ($this->confirm('Check other API\'s as well?')) {
            $this->output->writeln('Checking CBS Area API');
            $this->checkCbsAreaRegionsApiRegions();
            $this->output->writeln('');
        }

        $this->output->writeln('check database data from api results finished!');
        $this->output->writeln('');
        $this->output->writeln('');
        return 0;
    }

    public function checkCbsOpenDataRegions()
    {
        $apiData = CbsOpenDataApiService::getData();
        $apiData = CbsOpenDataApiService::getRegionsFromData($apiData);
        $testData = CbsOpenDataApiService::getFormattedRegionData($apiData);
        $geoCollection = RegionDataService::createBasicGeoCollectionFromData($testData);

        $this->checkForMissingItems($geoCollection);
        $this->checkForContentDeviation($geoCollection);
    }

    public function checkCbsAreaRegionsApiRegions()
    {
        $apiData = CbsAreaRegionApiService::getData();
        $testData = CbsAreaRegionApiService::getFormattedData($apiData['features']);
        $geoCollection = RegionDataService::createBasicGeoCollectionFromData($testData);

        $this->checkForMissingItems($geoCollection);
        $this->checkForContentDeviation($geoCollection);
    }

    public function checkForMissingItems(Collection $geoCollection)
    {
        $comparisonService = RegionDataComparisonService::createWithDatabaseCollection($geoCollection);
        $this->checkItemsMissingFromCollectionA($comparisonService);
        $this->checkItemsMissingFromCollectionB($comparisonService);
    }

    public function checkForContentDeviation(Collection $geoCollection)
    {
        $comparisonService = RegionDataComparisonService::createWithDatabaseCollection($geoCollection);
        $this->checkDeviatingItems($comparisonService, null, ['name', 'slug']);
    }
}
