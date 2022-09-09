<?php

namespace Vng\EvaCore\Commands\Instruments;

use Illuminate\Console\Command;
use Vng\EvaCore\Interfaces\IsInstrumentWatcherInterface;
use Vng\EvaCore\Repositories\UserRepositoryInterface;

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
        protected UserRepositoryInterface $userRepository
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
        $this->checkForNotifications();
        return 0;
    }

    public function checkForNotifications()
    {

        $watchingUsers = $this->userRepository->builder()->whereHas('watchedInstruments')->get();
        $watchingUsers->each(function (IsInstrumentWatcherInterface $user) {
            $user->notifyOfDirectSignals();
            $user->notifyOfPeriodicSignals();
        });
    }
}

