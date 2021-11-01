<?php

namespace Tests;

use Exception;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    /**
     * Overwrite the method from the CreatesApplication trait
     * Clears the cache so we're sure the .env.testing gets used
     *
     * @return Application
     */
    public function createApplication()
    {
        $createApp = function() {
            $app = require __DIR__.'/../bootstrap/app.php';
            $app->make(Kernel::class)->bootstrap();
            return $app;
        };

        $app = $createApp();
        if ($app->environment() !== 'testing') {
            $app[Kernel::class]->call('clear-compiled');
            $app[Kernel::class]->call('config:clear');
            $app = $createApp();
        }

        return $app;
    }

    /**
     * Overwrite the method from the RefreshDatabase trait
     * Prevenst a double migrate:fresh
     *
     * @return void
     */
    protected function refreshTestDatabase()
    {
        if (! RefreshDatabaseState::$migrated) {
            $this->artisan('eva:setup', [
                '--lean' => true,
            ]);

            $this->app[Kernel::class]->setArtisan(null);

            RefreshDatabaseState::$migrated = true;
        }

        $this->beginDatabaseTransaction();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->checkEnvironment();

        // Enable foreign key constraints for sqlite.
        if (DB::connection() instanceof SQLiteConnection) {
            DB::statement(DB::raw('PRAGMA foreign_keys=1'));
        }
    }

    private function checkEnvironment()
    {
        if (!$this->app->environment('testing')) {
            throw new Exception('wrong environment ['. $this->app->environment() .'] provided, check .env / .env.testing');
        }
        $databaseName = config('database.connections.mysql.database');
        if ($databaseName === 'dennis' || $databaseName === 'eva') {
            throw new Exception('wrong database selected');
        }
    }

    protected function clearCache()
    {
        $this->artisan('clear-compiled');
        $this->artisan('cache:clear');
        $this->artisan('config:clear');
        $this->artisan('route:clear');
        $this->artisan('view:clear');
    }

    protected function setupScript()
    {
        $this->app[Kernel::class]->call('eva:setup -l');
    }

    protected function setupGeo()
    {
        $this->app[Kernel::class]->call('eva:setup-geo');
    }
}
