<?php

namespace Vng\EvaCore\Providers;

use Illuminate\Support\AggregateServiceProvider;
use Vng\EvaCore\Commands\AssignRegions;
use Vng\EvaCore\Commands\Dev\PasswordGenerationTest;
use Vng\EvaCore\Commands\Elastic\DeleteIndex;
use Vng\EvaCore\Commands\Elastic\FetchNewInstrumentRatings;
use Vng\EvaCore\Commands\Elastic\SyncAll;
use Vng\EvaCore\Commands\Elastic\SyncEnvironments;
use Vng\EvaCore\Commands\Elastic\SyncInstruments;
use Vng\EvaCore\Commands\Elastic\SyncInstrumentsDescription;
use Vng\EvaCore\Commands\Elastic\SyncNewsItems;
use Vng\EvaCore\Commands\Elastic\SyncProviders;
use Vng\EvaCore\Commands\Elastic\SyncRegions;
use Vng\EvaCore\Commands\Elastic\SyncThemes;
use Vng\EvaCore\Commands\ExportInstruments;
use Vng\EvaCore\Commands\ExportInstrumentsCosts;
use Vng\EvaCore\Commands\ExportOldInstruments;
use Vng\EvaCore\Commands\ExtractGeoData;
use Vng\EvaCore\Commands\Geo\GeoEnsureIntegrity;
use Vng\EvaCore\Commands\Geo\GeoSourceGenerate;
use Vng\EvaCore\Commands\Geo\RegionsAssign;
use Vng\EvaCore\Commands\Geo\RegionsCheckDataFromApi;
use Vng\EvaCore\Commands\Geo\RegionsCheckDataFromSource;
use Vng\EvaCore\Commands\Geo\RegionsCheckSourceFromApi;
use Vng\EvaCore\Commands\Geo\RegionsCreateDataFromSource;
use Vng\EvaCore\Commands\Geo\RegionsCreateDataSetFromApi;
use Vng\EvaCore\Commands\Geo\RegionsUpdateDataFromSource;
use Vng\EvaCore\Commands\Geo\TownshipsCheckDataFromApi;
use Vng\EvaCore\Commands\Geo\TownshipsCheckDataFromSource;
use Vng\EvaCore\Commands\Geo\TownshipsCheckSourceFromApi;
use Vng\EvaCore\Commands\Geo\TownshipsCreateDataFromSource;
use Vng\EvaCore\Commands\Geo\TownshipsCreateDataSetFromApi;
use Vng\EvaCore\Commands\Geo\TownshipsUpdateDataFromSource;
use Vng\EvaCore\Commands\ImportGeoData;
use Vng\EvaCore\Commands\ImportInstruments;
use Vng\EvaCore\Commands\ImportOldFormatInstruments;
use Vng\EvaCore\Commands\ImportRegions;
use Vng\EvaCore\Commands\ImportTownships;
use Vng\EvaCore\Commands\MakeAreas;
use Vng\EvaCore\Commands\Operations\CleanContacts;
use Vng\EvaCore\Commands\Operations\SetupGeoData;
use Vng\EvaCore\Commands\Professionals\CognitoGetConfig;
use Vng\EvaCore\Commands\Professionals\CognitoSetup;
use Vng\EvaCore\Commands\Professionals\CognitoSyncProfessionals;
use Vng\EvaCore\Commands\Professionals\ProfessionalPasswordExpirationCheck;
use Vng\EvaCore\Commands\Reallocation\DuplicateOwnedItems;
use Vng\EvaCore\Commands\Reallocation\MoveOwnedItems;
use Vng\EvaCore\Commands\Setup\SeedCharacteristics;
use Vng\EvaCore\Commands\Setup\Setup;
use Vng\EvaCore\Commands\Setup\Update;
use Vng\EvaCore\Commands\Elastic\SyncTiles;
use Vng\EvaCore\Commands\Format\MigrateToFormat2;

class EvaServiceProvider extends AggregateServiceProvider
{
    protected $providers = [
        EventServiceProvider::class
    ];

    protected $commands = [
        PasswordGenerationTest::class,
        DeleteIndex::class,
        FetchNewInstrumentRatings::class,
        SyncAll::class,
        SyncEnvironments::class,
        SyncInstruments::class,
        SyncInstrumentsDescription::class,
        SyncNewsItems::class,
        SyncProviders::class,
        SyncRegions::class,
        SyncThemes::class,
        SyncTiles::class,
        MigrateToFormat2::class,
        GeoEnsureIntegrity::class,
        GeoSourceGenerate::class,
        RegionsAssign::class,
        RegionsCheckDataFromApi::class,
        RegionsCheckDataFromSource::class,
        RegionsCheckSourceFromApi::class,
        RegionsCreateDataFromSource::class,
        RegionsCreateDataSetFromApi::class,
        RegionsUpdateDataFromSource::class,
        TownshipsCheckDataFromApi::class,
        TownshipsCheckDataFromSource::class,
        TownshipsCheckSourceFromApi::class,
        TownshipsCreateDataFromSource::class,
        TownshipsCreateDataSetFromApi::class,
        TownshipsUpdateDataFromSource::class,
        CleanContacts::class,
        SetupGeoData::class,
        CognitoGetConfig::class,
        CognitoSetup::class,
        CognitoSyncProfessionals::class,
        ProfessionalPasswordExpirationCheck::class,
        DuplicateOwnedItems::class,
        MoveOwnedItems::class,
        SeedCharacteristics::class,
        Setup::class,
        AssignRegions::class,
        ExportInstruments::class,
        ExportInstrumentsCosts::class,
        ExportOldInstruments::class,
        ExtractGeoData::class,
        ImportGeoData::class,
        ImportInstruments::class,
        ImportOldFormatInstruments::class,
        ImportRegions::class,
        ImportTownships::class,
        MakeAreas::class,
        Update::class
    ];

    public function register()
    {
        parent::register();
    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        $this->publishConfig();
        $this->publishTranslations();
        $this->registerCommands();
    }

    private function publishConfig()
    {
        $this->publishes([
            __DIR__.'/../../config/roles.php' => config_path('roles.php'),
        ], 'eva-config');
    }

    private function publishTranslations()
    {
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'eva');

        $this->publishes([
            __DIR__.'/../../resources/lang' => resource_path('lang/vendor/eva'),
        ], 'eva-translations');
    }

    private function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands($this->commands);
        }
    }
}
