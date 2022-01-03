<?php

namespace Vng\EvaCore\Commands\Setup;

use Illuminate\Console\Command;
use Vng\EvaCore\Models\Environment;

class InitializeEnvironment extends Command
{
    protected $signature = 'setup:init-environment';
    protected $description = 'Create an environment entity from .env variables';

    public function handle(): int
    {
        $this->getOutput()->writeln('initializing environment...');

        $slug = config('eva-core.environment.slug');

        if ($slug) {
            $name = config('eva-core.environment.name');
            $this->info('slug: '.$slug);
            $this->info('name: '.$name);

            Environment::onlyTrashed()->where('slug', $slug)->restore();
            Environment::query()->updateOrCreate([
                'slug' => $slug
            ], [
                'name' => $name,
            ]);
        } else {
            $this->warn('Could not create environment, no slug present in .env');
        }

        $this->getOutput()->writeln('initializing environment finished!');
        return 0;
    }
}
