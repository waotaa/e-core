<?php

namespace Vng\EvaCore\Commands\Elastic;

use Illuminate\Console\Command;
use Vng\EvaCore\Jobs\SyncResourceToElastic;
use Vng\EvaCore\Models\Professional;

class SyncProfessionals extends Command
{
    protected $signature = 'elastic:sync-professionals {--f|fresh}';
    protected $description = 'Sync all professionals to ES';

    public function handle(): int
    {
        $this->getOutput()->writeln('syncing professionals');
        $this->getOutput()->writeln('used index-prefix: ' . config('elastic.prefix'));

        if ($this->option('fresh')) {
            $this->call('elastic:delete-index', ['index' => 'professionals', '--force' => true]);
        }

        $this->getOutput()->writeln('');
        foreach (Professional::all() as $professional) {
            $this->getOutput()->write('.');
//            $this->getOutput()->write('- ' . $professional->name);
            dispatch(new SyncResourceToElastic($professional));
        }

        $this->getOutput()->writeln('');
        $this->getOutput()->writeln('');
        return 0;
    }
}

