<?php

namespace Vng\EvaCore\Commands\Geo;

use Illuminate\Support\Collection;
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

        $this->output->writeln('Checking CBS Open Data API');
        // This source seems to update late
        $this->checkCbsOpenDataTownships();
        $this->output->writeln('');

        if ($this->confirm('Check other API\'s as well?')) {
            $this->output->writeln('Checking CBS Area API');
            $this->checkCbsAreaTownshipApiTownships();
            $this->output->writeln('');

            $this->output->writeln('Checking CBS District Neighborhood API');
            $this->checkCbsDistrictNeighborhoodTownshipApiTownships();
            $this->output->writeln('');

//          NO LONGER AVAILABLE..?
//
//            $this->output->writeln('Checking Kadaster API');
//            // This source seems to update late
//            $this->checkKadasterTownshipApiTownships();
//            $this->output->writeln('');
        }

        $this->output->writeln('check source data from api results finished!');
        $this->output->writeln('');
        $this->output->writeln('');
        return 0;
    }

    public function checkCbsOpenDataTownships()
    {
        $apiData = CbsOpenDataApiService::getData();
        $testData = CbsOpenDataApiService::getFormattedTownshipData($apiData);
        $geoCollection = TownshipDataService::createBasicGeoCollectionFromData($testData);

        $this->checkForMissingItems($geoCollection);
        $this->checkForContentDeviation($geoCollection);
    }

    public function checkCbsAreaTownshipApiTownships()
    {
        $apiData = CbsAreaTownshipApiService::getData();
        $testData = CbsAreaTownshipApiService::getFormattedData($apiData['features']);
        $geoCollection = TownshipDataService::createBasicGeoCollectionFromData($testData);

        $this->checkForMissingItems($geoCollection);
        $this->checkForContentDeviation($geoCollection);
    }

    public function checkCbsDistrictNeighborhoodTownshipApiTownships()
    {
        $apiData = CbsDistrictNeighborhoodTownshipApiService::getData();
        $testData = CbsDistrictNeighborhoodTownshipApiService::getFormattedData($apiData['features']);
        $geoCollection = TownshipDataService::createBasicGeoCollectionFromData($testData);

        $this->checkForMissingItems($geoCollection);
        $this->checkForContentDeviation($geoCollection);
    }

    public function checkKadasterTownshipApiTownships()
    {
        $apiData = KadasterTownshipApiService::getData();
        $testData = KadasterTownshipApiService::getFormattedData($apiData['features']);
        $geoCollection = TownshipDataService::createBasicGeoCollectionFromData($testData);

        $this->checkForMissingItems($geoCollection);
        $this->checkForContentDeviation($geoCollection);
    }

    public function checkForMissingItems(Collection $geoCollection)
    {
        $comparisonService = TownshipDataComparisonService::createWithSourceCollection($geoCollection);
        $this->checkItemsMissingFromCollectionA($comparisonService);
        $this->checkItemsMissingFromCollectionB($comparisonService);
    }

    public function checkForContentDeviation(Collection $geoCollection, $attributes = null)
    {
        if (is_null($attributes)) {
            $attributes = ['name', 'slug', 'region_code'];
        }
        $comparisonService = TownshipDataComparisonService::createWithSourceCollection($geoCollection);
        $this->checkDeviatingItems($comparisonService, null, $attributes);
    }

}
