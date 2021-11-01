<?php

namespace Vng\EvaCore\Commands\Elastic;

use Vng\EvaCore\Jobs\RemoveResourceFromElastic;
use Vng\EvaCore\Jobs\SyncResourceToElastic;
use Vng\EvaCore\Models\Theme;
use Illuminate\Console\Command;

class SyncThemes extends Command
{
    protected $signature = 'elastic:sync-themes {--f|fresh}';
    protected $description = 'Sync all themes to ES';

    public function handle(): int
    {
        $this->getOutput()->writeln('syncing themes');
        $this->getOutput()->writeln('used index-prefix: ' . config('elastic.prefix'));

        if ($this->option('fresh')) {
            $this->call('elastic:delete-index', ['index' => 'themes', '--force' => true]);
        }

        $this->getOutput()->writeln('');
        foreach (Theme::all() as $theme) {
            $this->getOutput()->write('.');
            dispatch(new SyncResourceToElastic($theme));
        }

        foreach (Theme::onlyTrashed()->get() as $theme) {
            dispatch(new RemoveResourceFromElastic($theme));
        }

        $this->getOutput()->writeln('');
        $this->getOutput()->writeln('');
        return 0;
    }
}
