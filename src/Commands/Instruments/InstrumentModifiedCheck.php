<?php

namespace Vng\EvaCore\Commands\Instruments;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Vng\EvaCore\Jobs\NotifyInstrumentModificationJob;
use Vng\EvaCore\Repositories\InstrumentRepositoryInterface;

class InstrumentModifiedCheck extends Command
{
    protected $signature = 'instrument:modified';
    protected $description = 'Checks for instrument modification';

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
        $this->info('Checking modified');

        $instrumentsModifiedYesterday = $this->instrumentRepository
            ->builder()
            ->whereNotNull('updated_at')
            ->WhereDate('updated_at', Carbon::yesterday())
            ->get();

        $this->line($instrumentsModifiedYesterday->count() . ' modified instruments found');
        $instrumentsModifiedYesterday->each(fn($instrument) => NotifyInstrumentModificationJob::dispatch($instrument));

        return 0;
    }
}

