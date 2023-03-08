<?php

namespace Vng\EvaCore\Commands\Ratings;

use Vng\EvaCore\Commands\Professionals\CognitoSyncProfessionals;
use Vng\EvaCore\Jobs\MatchRatingToProfessionalJob;
use Illuminate\Console\Command;
use Vng\EvaCore\Models\Rating;

class MatchInstrumentRatingsWithProfessionals extends Command
{
    protected $signature = 'ratings:match';
    protected $description = 'Attempts to match all ratings with a professional';

    public function handle(): int
    {
        $this->output->writeln('matching ratings...');

        $this->call(CognitoSyncProfessionals::class);

        foreach (Rating::all() as $rating) {
            $this->getOutput()->write('.');
            dispatch(new MatchRatingToProfessionalJob($rating));
        }

        $this->output->newLine(2);
        $this->output->writeln('matching ratings finished!');
        return 0;
    }
}
