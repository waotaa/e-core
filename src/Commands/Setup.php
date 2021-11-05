<?php

namespace Vng\EvaCore\Commands;

use Illuminate\Console\Command;

class Setup extends Command
{
    protected $signature = 'eva:setup {--s|silent} {--l|lean}';
    protected $description = 'Initialize a fresh database and setup';

    public function handle(): int
    {
        $this->getOutput()->writeln('setting up...');

        $silent = $this->option('silent');
        $lean = $this->option('lean');

        $this->call('key:generate');
        $this->call('migrate:fresh', ['--force' => true]);
        $this->call('setup:seed-characteristics');

        if (!$lean) {
            $this->call('eva:setup-geo');
            $this->call('professionals:setup', ['--silent' => $silent]);
        }

        $this->getOutput()->writeln('setting up finished!');
        return 0;
    }
}
