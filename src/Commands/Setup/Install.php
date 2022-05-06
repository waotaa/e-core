<?php

namespace Vng\EvaCore\Commands\Setup;

use Illuminate\Console\Command;

class Install extends Command
{
    protected $signature = 'eva-core:install {--n|no-interaction}';
    protected $description = 'Installs the package';

    public function handle(): int
    {
        $this->info("\n[ Installing eva core ]\n");

        $this->publishPackage();

        $this->call(Setup::class, [
            '--no-interaction' => $this->option('no-interaction'),
        ]);

        $this->info("\n[ Installling eva core ] - finished!\n");
        return 0;
    }

    private function publishPackage()
    {
        $this->call('vendor:publish', [
            '--provider' => 'Vng\EvaCore\Providers\EvaServiceProvider',
            '--force' => true,
        ]);
    }
}
