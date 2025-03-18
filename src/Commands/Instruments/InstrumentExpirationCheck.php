<?php

namespace Vng\EvaCore\Commands\Instruments;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Vng\EvaCore\Jobs\NotifyInstrumentExpirationJob;
use Vng\EvaCore\Repositories\InstrumentRepositoryInterface;

class InstrumentExpirationCheck extends Command
{
    protected $signature = 'instrument:expiration';
    protected $description = 'Checks for instrument publication expirations';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        protected InstrumentRepositoryInterface $instrumentRepository,
    )
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Checking expiration');

        $instrumentsExpiredYesterday = $this->instrumentRepository
            ->builder()
            ->whereNotNull('publish_to')
            ->WhereDate('publish_to', Carbon::yesterday())
            ->get();

        $this->line($instrumentsExpiredYesterday->count() . ' expired instruments found');
        $instrumentsExpiredYesterday->each(fn($instrument) => NotifyInstrumentExpirationJob::dispatch($instrument));

        return 0;
    }
}

