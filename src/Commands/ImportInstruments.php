<?php

namespace Vng\EvaCore\Commands;

use Vng\EvaCore\Models\Environment;
use Vng\EvaCore\Models\Township;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Models\Region;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class ImportInstruments extends Command
{
    protected $signature = 'eva:import-instruments {json-file} {mark} {--r|region=} {--g|township=} {--e|environment=}';

    protected $description = 'Import instruments from a export json';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->output->writeln('getting file...');

        $instruments = $this->getInstrumentsFromFile();
        $this->validateData($instruments);

        $owner = $this->getOwnerFromOptions();

        $this->output->writeln('importing instruments...');
        $this->output->writeln('');

        foreach ($instruments as $instrument) {
            $this->output->write('.');
            $this->addInstrument($instrument, $owner);
        }

        $this->output->writeln('');
        $this->output->writeln('');

        $this->output->writeln('syncing providers...');
        $this->call('elastic:sync-providers');
        $this->output->writeln('syncing instruments...');
        $this->call('elastic:sync-instruments');

        $this->output->writeln('');
        $this->output->writeln('finished!');
        return 0;
    }

    private function getInstrumentsFromFile()
    {
        $filename = $this->argument('json-file');
        try {
            $storage = Storage::disk('local');
            if (!App::environment('local')) {
                $storage = Storage::disk('s3');
            }
            $contentString = $storage->get('imports/' . $filename);

            $this->output->writeln('file found!');
            return json_decode($contentString, true);
        } catch (FileNotFoundException $e) {
            exit('file not found');
        }
    }

    private function validateData($data)
    {
        $this->output->writeln('validating data...');
        $valid = true;

        // Check tiles
        // check themes

        if (!$valid) {
            exit('invalid data');
        }
        $this->output->writeln('data valid!');
    }

    private function getOwnerFromOptions(): ?Model
    {
        $regionSlug = $this->option('region');
        $townshipSlug = $this->option('township');
        $environmentSlug = $this->option('environment');

        if (!$regionSlug && !$townshipSlug && !$environmentSlug) {
            return null;
        }

        $this->output->writeln('fetching owner...');

        if (!is_null($regionSlug) && !is_null($townshipSlug)) {
            exit('cannot pass both region and township');
        }
        if (!is_null($regionSlug) && !is_null($environmentSlug)) {
            exit('cannot pass both region and environment');
        }
        if (!is_null($townshipSlug) && !is_null($environmentSlug)) {
            exit('cannot pass both township and environment');
        }

        if ($regionSlug) {
            $region = Region::query()->where('slug', $regionSlug)->first();
            if (is_null($region)) {
                exit('given region not found');
            }
            $this->output->writeln($region->name . ' found!');
            return $region;
        }

        if ($townshipSlug) {
            $township = Township::query()->where('slug', $townshipSlug)->first();
            if (is_null($township)) {
                exit('given township not found');
            }
            $this->output->writeln($township->name . ' found!');
            return $township;
        }

        if ($environmentSlug) {
            $environment = Environment::query()->where('slug', $environmentSlug)->first();
            if (is_null($environment)) {
                exit('given environment not found');
            }
            $this->output->writeln($environment->name . ' found!');
            return $environment;
        }
        return null;
    }

    private function addInstrument($instrumentData, $owner = null): Instrument
    {
        exit('to be implemented');
//        Similar to ImportOldFormatInstruments
//        Create service for it
    }
}
