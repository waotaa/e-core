<?php

namespace Vng\EvaCore\Commands\Instruments;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Vng\EvaCore\Jobs\NotifyInstrumentRevisionJob;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Models\InstrumentTracker;
use Vng\EvaCore\Repositories\InstrumentRepositoryInterface;
use Vng\EvaCore\Repositories\InstrumentTrackerRepositoryInterface;
use Vng\EvaCore\Repositories\ManagerRepositoryInterface;

class InstrumentRevisionCheck extends Command
{
    protected $signature = 'instrument:revision';
    protected $description = 'Checks for instrument revision';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        protected InstrumentRepositoryInterface $instrumentRepository,
        protected InstrumentTrackerRepositoryInterface $instrumentTrackerRepository,
        protected ManagerRepositoryInterface $managerRepository,
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
        $this->info('Checking revision');

//        // Per manager approach
//        $watchingManagers = $this->managerRepository
//            ->builder()
//            ->whereHas('watchedInstruments')
//            ->get();
//
//        $watchingManagers->each(function (IsInstrumentWatcherInterface $manager) {
//            $instrumentsOnRevisionDay = $manager->instrumentTrackers->filter(function (InstrumentTracker $tracker) {
//                return $tracker->isInstrumentRevisionDay();
//            });
//        });

        // Per instrument approach
        $instruments = $this->instrumentRepository
            ->builder()
            ->with(['instrumentTrackers', 'instrumentTrackers.manager'])
            ->whereHas('instrumentTrackers')
            ->get();
        $this->line($instruments->count() . ' tracked instruments found');

        $instrumentsOnRevisionDay = $instruments->filter(function (Instrument $instrument) {
            /** @var Collection $trackers */
            $trackers = $instrument->instrumentTrackers;
            return $trackers->filter(function (InstrumentTracker $tracker) {
                return $tracker->isInstrumentRevisionDay();
            })->isNotEmpty();
        });

        $this->line($instrumentsOnRevisionDay->count() . ' triggered instruments found');
        $instrumentsOnRevisionDay->each(fn($instrument) => NotifyInstrumentRevisionJob::dispatch($instrument));

//        // All trackers approach
//        $instrumentTrackers = $this->instrumentTrackerRepository
//            ->builder()
//            ->with(['manager', 'instrument'])
//            ->get();
//        $instrumentsOnRevisionDay = $instrumentTrackers->filter(function (InstrumentTracker $tracker) {
//            return $tracker->isInstrumentRevisionDay();
//        });
//
//        $this->line($instrumentsOnRevisionDay->count() . ' triggered instruments found');
//        $instrumentsOnRevisionDay->each(function (InstrumentTracker $tracker) {
//
//            $notification = app(InstrumentRevisionNotificationInterface::class, [
//                'instrument' => $tracker->instrument
//            ]);
//            Notification::send($tracker->manager, $notification);
//        });

        return 0;
    }
}

