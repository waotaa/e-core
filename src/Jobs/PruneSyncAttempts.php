<?php

namespace Vng\EvaCore\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Vng\EvaCore\Models\SyncAttempt;

class PruneSyncAttempts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function handle(): void
    {
        $weekAgo = Carbon::now()->subDays(7)->startOfDay()->toDateTimeString();
        SyncAttempt::query()->where('created_at', '<=', $weekAgo)->delete();
    }
}
