<?php

namespace Vng\EvaCore\Commands\Format;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupActionLog extends Command
{
    protected $signature = 'format:clean-actions';
    protected $description = 'removes outdated entries from the mutations table';

    public function handle(): int
    {
        $this->getOutput()->writeln('starting clean actions...');

        $this->removeAreaEntries();
        $this->removeThemeEntries();
        $this->removeOldEntries();

        $this->getOutput()->writeln('clean actions finished!');
        return 0;
    }

    public function removeAreaEntries()
    {
        $this->removeEntriesFromClass('App\Models\Area');
    }

    public function removeThemeEntries()
    {
        $this->removeEntriesFromClass('App\Models\Theme');
    }

    public function removeEntriesFromClass($typeClass)
    {
        DB::table('mutations')
            ->where('actionable_type', $typeClass)
            ->orWhere('target_type', $typeClass)
            ->orWhere('model_type', $typeClass)
            ->delete();
    }

    public function removeOldEntries()
    {
        $daysAgoThreshold = Carbon::today()->subDays(100);
        DB::table('mutations')
            ->whereDate('created_at', '<', $daysAgoThreshold)
            ->delete();
    }
}
