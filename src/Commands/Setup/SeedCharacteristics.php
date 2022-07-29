<?php

namespace Vng\EvaCore\Commands\Setup;

use Illuminate\Console\Command;

class SeedCharacteristics extends Command
{
    protected $signature = 'setup:seed-characteristics';
    protected $description = 'Seed all instrument characteristics';

    public function handle(): int
    {
        $this->output->writeln('running seed characteristics');
        $this->call('db:seed', ['--class' => 'Database\Seeders\Admin\TileSeeder', '--force' => true]);

        $this->call('db:seed', ['--class' => 'Database\Seeders\InstrumentProps\ClientCharacteristicSeeder', '--force' => true]);
        $this->call('db:seed', ['--class' => 'Database\Seeders\InstrumentProps\GroupFormSeeder', '--force' => true]);
        $this->call('db:seed', ['--class' => 'Database\Seeders\InstrumentProps\ImplementationSeeder', '--force' => true]);
        $this->call('db:seed', ['--class' => 'Database\Seeders\InstrumentProps\InstrumentTypeSeeder', '--force' => true]);
        $this->call('db:seed', ['--class' => 'Database\Seeders\InstrumentProps\LocationTypeSeeder', '--force' => true]);
        $this->call('db:seed', ['--class' => 'Database\Seeders\InstrumentProps\TargetGroupSeeder', '--force' => true]);

        $this->output->writeln('seed characteristics finished!');
        return 0;
    }
}
