<?php

namespace Vng\EvaCore\Commands\Elastic;

use Vng\EvaCore\Jobs\FetchNewInstrumentRatingsJob;
use Vng\EvaCore\Models\Instrument;
use Illuminate\Console\Command;

class FetchNewInstrumentRatings extends Command
{
    protected $signature = 'elastic:fetch-ratings';
    protected $description = 'Check the instrument index for new ratings and add them to the database';

    public function handle(): int
    {
        $this->output->writeln('fetching ratings...');
        $this->output->writeln('used index-prefix: ' . config('elastic.prefix'));

        $this->call('professionals:sync');

        foreach (Instrument::all() as $instrument) {
            $this->getOutput()->write('.');
            dispatch(new FetchNewInstrumentRatingsJob($instrument));
        }

        $this->output->newLine(2);
        $this->output->writeln('fetching ratings finished!');
        return 0;
    }
}
