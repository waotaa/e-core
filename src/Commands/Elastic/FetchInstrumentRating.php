<?php

namespace Vng\EvaCore\Commands\Elastic;

use Vng\EvaCore\Jobs\FetchNewInstrumentRatingsJob;
use Vng\EvaCore\Models\Instrument;
use Illuminate\Console\Command;
use Vng\EvaCore\Repositories\InstrumentRepositoryInterface;

class FetchInstrumentRating extends Command
{
    protected $signature = 'elastic:fetch-rating {instrumentId}';
    protected $description = 'Check the instrument for new ratings and add them to the database';

    public function handle(): int
    {
        $this->output->writeln('fetching rating...');
        $this->output->writeln('used index-prefix: ' . config('elastic.prefix'));
        $this->output->writeln('');

        $instrumentId = $this->argument('instrumentId');

        /** @var InstrumentRepositoryInterface $instrumentRepository */
        $instrumentRepository = app(InstrumentRepositoryInterface::class);
        /** @var Instrument $instrument */
        $instrument = $instrumentRepository
            ->getElasticResourceBuilder()
            ->findOrFail($instrumentId);

        $this->getOutput()->writeln($instrument->name);
        dispatch(new FetchNewInstrumentRatingsJob($instrument));

        $this->output->newLine(2);
        $this->output->writeln('fetching rating finished!');
        return 0;
    }
}
