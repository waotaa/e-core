<?php

namespace Vng\EvaCore\Commands\Geo;

use Vng\EvaCore\Services\GeoApi\CbsAreaTownshipApiService;
use Vng\EvaCore\Services\GeoApi\CbsDistrictNeighborhoodTownshipApiService;
use Vng\EvaCore\Services\GeoApi\CbsOpenDataApiService;
use Vng\EvaCore\Services\GeoApi\KadasterTownshipApiService;
use Vng\EvaCore\Services\GeoComparison\TownshipDataComparisonService;
use Vng\EvaCore\Services\GeoData\TownshipDataService;

class TownshipsCheckSourceFromApi extends GeoCheckCommand
{
    protected $signature = 'geo:townships-check-source-from-api';
    protected $description = 'Check township source compared to api results';

    protected string $labelCollectionA = 'Source';
    protected string $labelCollectionB = 'API';

    public function handle(): int
    {
        $this->output->writeln('check source data from api results..');
        $this->output->writeln('');

        $this->output->writeln('Checking CBS Area API');
        $this->checkCbsAreaTownshipApiTownships();
        $this->output->writeln('');

        $this->output->writeln('Checking CBS District Neighborhood API');
        $this->checkCbsDistrictNeighborhoodTownshipApiTownships();
        $this->output->writeln('');

        $this->output->writeln('Checking CBS Open Data API');
        // This source seems to update late
        $this->checkCbsOpenDataTownships();
        $this->output->writeln('');

        $this->output->writeln('Checking Kadaster API');
        // This source seems to update late
        $this->checkKadasterTownshipApiTownships();
        $this->output->writeln('');

        $this->output->writeln('check source data from api results finished!');
        $this->output->writeln('');
        $this->output->writeln('');
        return 0;
    }

    public function checkCbsAreaTownshipApiTownships()
    {
        $apiData = CbsAreaTownshipApiService::fetchApiData();
        $testData = CbsAreaTownshipApiService::getFormattedData($apiData['features']);
        $this->checkForMissingItems($testData);
    }

    public function checkCbsDistrictNeighborhoodTownshipApiTownships()
    {
        $apiData = CbsDistrictNeighborhoodTownshipApiService::fetchApiData();
        $testData = CbsDistrictNeighborhoodTownshipApiService::getFormattedData($apiData['features']);
        $this->checkForMissingItems($testData);
    }

    public function checkCbsOpenDataTownships()
    {
        $apiData = CbsOpenDataApiService::fetchApiData();
        $testData = CbsOpenDataApiService::getFormattedTownshipData($apiData['value']);
        $this->checkForMissingItems($testData);
    }

    public function checkKadasterTownshipApiTownships()
    {
        $apiData = KadasterTownshipApiService::fetchApiData();
        $testData = KadasterTownshipApiService::getFormattedData($apiData['features']);
        $this->checkForMissingItems($testData);
    }

    public function checkForMissingItems($testData)
    {
        $geoCollection = TownshipDataService::createBasicGeoCollectionFromData($testData);
        $comparisonService = TownshipDataComparisonService::createWithSourceCollection($geoCollection);
        $this->checkItemsMissingFromCollectionA($comparisonService);
        $this->checkItemsMissingFromCollectionB($comparisonService);
    }
}
