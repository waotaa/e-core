<?php

namespace Vng\EvaCore\Commands\Instruments;

use Illuminate\Console\Command;
use Vng\EvaCore\Interfaces\IsInstrumentWatcherInterface;
use Vng\EvaCore\Repositories\ManagerRepositoryInterface;

class InstrumentSignalingCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'instrument:signals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks for instrument signals';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        protected ManagerRepositoryInterface $managerRepository
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
        $this->info('Starting instrument signaling check');
        $this->checkForNotifications();
        $this->line('..done');
        return 0;
    }

    /**
     * Checks every manager with instrument trackers
     * Direct trackers: expiration (of publication) and modification
     * Periodic: expiration (of publication) and revision
     *
     * @return void
     */
    public function checkForNotifications()
    {
        $watchingUsers = $this->managerRepository->builder()->whereHas('watchedInstruments')->get();
        $this->line($watchingUsers->count() . ' with trackers found');
        $watchingUsers->each(function (IsInstrumentWatcherInterface $user) {
            $directSignals = $user->notifyOfDirectSignals();
            $periodicSignals = $user->notifyOfPeriodicSignals();
            $userId = $user->id ?? 'unknown';
            $this->line("user [{$userId}] - direct signals {$directSignals} - periodic signals {$periodicSignals}");
        });
    }
}

