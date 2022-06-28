<?php

namespace Vng\EvaCore\Commands\Format;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupSyncAttempts extends Command
{
    protected $signature = 'format:clean-sync';
    protected $description = 'removes outdated entries from the sync_attempts table';

    public function handle(): int
    {
        $this->getOutput()->writeln('starting clean sync-attempts...');

        $this->removeOldEntries();

        $this->getOutput()->writeln('clean sync-attempts finished!');
        return 0;
    }

    public function removeOldEntries()
    {
        $weekAgo = Carbon::today()->subDays(7);
        DB::table('sync_attempts')
            ->whereDate('created_at', '<', $weekAgo)
            ->delete();
    }
}
