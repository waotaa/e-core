<?php

namespace Vng\EvaCore\Commands\Geo;

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

        $this->output->writeln('Checking CBS Area API');
        $this->checkCbsAreaRegionsApiRegions();
        $this->output->writeln('');

        $this->output->writeln('Checking CBS Open Data API');
        $this->checkCbsOpenDataRegions();
        $this->output->writeln('');

        $this->output->writeln('check source data from api results finished!');
        $this->output->writeln('');
        $this->output->writeln('');
        return 0;
    }

    public function checkCbsAreaRegionsApiRegions()
    {
        $apiData = CbsAreaRegionApiService::fetchApiData();
        $testData = CbsAreaRegionApiService::getFormattedData($apiData['features']);
        $this->checkForMissingItems($testData);
    }

    public function checkCbsOpenDataRegions()
    {
        $apiData = CbsOpenDataApiService::fetchApiData();
        $testData = CbsOpenDataApiService::getFormattedRegionData($apiData['value']);
        $this->checkForMissingItems($testData);
    }

    public function checkForMissingItems($testData)
    {
        $geoCollection = RegionDataService::createBasicGeoCollectionFromData($testData);
        $comparisonService = RegionDataComparisonService::createWithSourceCollection($geoCollection);
        $this->checkItemsMissingFromCollectionA($comparisonService);
        $this->checkItemsMissingFromCollectionB($comparisonService);
    }
}
