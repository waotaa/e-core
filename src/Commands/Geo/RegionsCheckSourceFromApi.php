<?php

namespace Vng\EvaCore\Commands\Geo;

use Illuminate\Support\Collection;
use Vng\EvaCore\Services\GeoApi\CbsAreaRegionApiService;
use Vng\EvaCore\Services\GeoApi\CbsOpenDataApiService;
use Vng\EvaCore\Services\GeoComparison\RegionDataComparisonService;
use Vng\EvaCore\Services\GeoData\RegionDataService;

class RegionsCheckSourceFromApi extends GeoCheckCommand
{
    protected $signature = 'geo:regions-check-source-from-api';
    protected $description = 'Check region source compared to api results';

    protected string $labelCollectionA = 'Source';
    protected string $labelCollectionB = 'API';

    public function handle(): int
    {
        $this->output->writeln('check source data from api results..');
        $this->output->writeln('');

        $this->output->writeln('Checking CBS Open Data API');
        $this->checkCbsOpenDataRegions();
        $this->output->writeln('');

        if ($this->confirm('Check other API\'s as well?')) {
            $this->output->writeln('Checking CBS Area API');
            $this->checkCbsAreaRegionsApiRegions();
            $this->output->writeln('');
        }

        $this->output->writeln('check source data from api results finished!');
        $this->output->writeln('');
        $this->output->writeln('');
        return 0;
    }

    public function checkCbsOpenDataRegions()
    {
        $apiData = CbsOpenDataApiService::getData();
        $testData = CbsOpenDataApiService::getRegionsFromData($apiData);
        $testData = CbsOpenDataApiService::getFormattedRegionData($testData);
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
        $comparisonService = RegionDataComparisonService::createWithSourceCollection($geoCollection);
        $this->checkItemsMissingFromCollectionA($comparisonService);
        $this->checkItemsMissingFromCollectionB($comparisonService);
    }

    public function checkForContentDeviation(Collection $geoCollection)
    {
        $comparisonService = RegionDataComparisonService::createWithSourceCollection($geoCollection);
        $this->checkDeviatingItems($comparisonService, null, ['name', 'slug']);
    }
}
