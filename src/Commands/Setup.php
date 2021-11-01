<?php

namespace Vng\EvaCore\Commands;

use Illuminate\Console\Command;

class Setup extends Command
{
    protected $signature = 'eva:setup {--t|test} {--s|silent} {--l|lean}';
    protected $description = 'Initialize a fresh database and create an admin user';

    public function handle(): int
    {
        $this->getOutput()->writeln('setting up...');

        $silent = $this->option('silent');
        $test = $this->option('test');
        $lean = $this->option('lean');

        $this->call('key:generate');
        $this->call('migrate:fresh', ['--force' => true]);
        $this->call('setup:seed-characteristics');

        $this->call('eva:setup-roles-permissions');
        $this->call('eva:create-admin');

        if (!$lean) {
            $this->call('eva:setup-geo');
            $this->call('professionals:setup', ['--silent' => $silent]);
        }

        if ($test) {
            $this->call('eva:create-test-users', ['--silent' => $silent]);
        }

        $this->getOutput()->writeln('setting up finished!');
        return 0;
    }
}
