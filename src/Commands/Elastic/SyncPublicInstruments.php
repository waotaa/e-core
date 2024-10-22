<?php

namespace Vng\EvaCore\Commands\Elastic;

use Illuminate\Console\Command;
use Vng\EvaCore\ElasticResources\Original\Shared\InstrumentResource;
use Vng\EvaCore\Jobs\ElasticPublic\RemoveResourceFromPublicElasticJob;
use Vng\EvaCore\Jobs\ElasticPublic\SyncResourceToPublicElasticJob;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Repositories\InstrumentRepositoryInterface;
use Vng\EvaCore\Services\ElasticSearch\ElasticPublicClientBuilder;

class SyncPublicInstruments extends Command
{
    protected $signature = 'elastic:sync-public-instruments {--f|fresh}';
    protected $description = 'Sync public instruments resources to public ES instance';

    public function handle(): int
    {
        $this->getOutput()->writeln('syncing public instruments');

        if ($this->option('fresh')) {
            $this->call(DeletePublicIndex::class, ['index' => 'instruments', '--force' => true]);
        }

        if (!ElasticPublicClientBuilder::hasSettings()){
            $this->output->writeln('public instance settings missing');
            return 0;
        }

        $this->output->writeln('');

        /** @var InstrumentRepositoryInterface $instrumentRepository */
        $instrumentRepository = app(InstrumentRepositoryInterface::class);
        $instruments = $instrumentRepository
            ->builder()
            ->with([
                'organisation',
                'implementation',
                'groupForms',
                'locations',
                'registrationCodes',
                'ratings',
                'tiles',
                'targetGroups',
                'clientCharacteristics',
                'links',
                'videos',
                'downloads',
                'provider',
                'contacts',
                'availableRegions',
                'availableTownships',
                'availableNeighbourhoods',
                'parentInstrument'
            ])
            ->get();

        foreach ($instruments as $instrument) {
            $this->getOutput()->write('.');
            dispatch(new SyncResourceToPublicElasticJob(
                $instrument,
                'instruments',
                InstrumentResource::class,
            ));
        }

        foreach (Instrument::onlyTrashed()->get() as $instrument) {
            dispatch(new RemoveResourceFromPublicElasticJob(
                'instruments',
                $instrument->getSearchId()
            ));
        }

        $this->output->writeln('');
        $this->output->writeln('');
        return 0;
    }
}
