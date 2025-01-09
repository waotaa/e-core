<?php

namespace Vng\EvaCore\Commands\Format;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupSyncAttempts extends Command
{
    protected $signature = 'format:clean-sync {days? : Number of days to keep sync attempts (default: 7)}';
    protected $description = 'Removes outdated entries from the sync_attempts table';

    public function handle(): int
    {
        $this->getOutput()->writeln('Starting clean sync-attempts...');

        // Haal het aantal dagen op uit het argument, gebruik 7 als default
        $days = $this->argument('days') ?? 7;

        $this->removeOldEntries((int) $days);

        $this->getOutput()->writeln('Clean sync-attempts finished!');
        return 0;
    }

    public function removeOldEntries(int $days)
    {
        // Bereken de datum op basis van het aantal dagen
        $dateThreshold = Carbon::today()->subDays($days);

        // Verwijder oude records
        DB::table('sync_attempts')
            ->whereDate('created_at', '<', $dateThreshold)
            ->delete();
    }
}
