<?php

namespace Vng\EvaCore\Commands;

use Vng\EvaCore\Models\Area;
use Vng\EvaCore\Models\Township;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class ImportTownships extends Command
{
    protected $signature = 'eva:import-townships {--d|download}';
    protected $description = 'Import township data from API';

    public function handle(): int
    {
        $this->getOutput()->writeln('importing townships...');

        $geoData = $this->attemptLoadingFile();

        if (is_null($geoData)) {
            $this->error('file not found (from ' . App::environment() . ' env)');
            $this->info('use `php artisan eva:download-administrative-townships` to download the required file');
            return 1;
        }

        $this->importTownshipsFromGeoData($geoData);

        $this->output->writeln('');
        $this->getOutput()->writeln('importing townships finished!');
        $this->output->writeln('');
        $this->output->writeln('');
        return 0;
    }

    private function attemptLoadingFile($firstAttempt = true)
    {
        if ($this->getStorage()->exists($this->getFilePath())) {
            return $this->getTownshipDataFromFile();
        }

        $this->warn('file doesn\'t exist (on ' . App::environment() . ' env)');

        $downloadOption = $this->option('download');
        if ($downloadOption && $firstAttempt) {
            $this->call('eva:download-administrative-townships');
            return $this->attemptLoadingFile(false);
        }
        return null;
    }

    private function getTownshipDataFromFile()
    {
        $contentString = $this->getStorage()->get($this->getFilePath());

        $this->output->writeln('file found!');
        return json_decode($contentString, true);
    }

    private function getFilePath()
    {
        return 'fixed/' . DownloadAdministrativeTownships::INTERNAL_FILENAME;
    }

    private function getStorage()
    {
        $storage = Storage::disk('local');
        if (!App::environment('local')) {
            $storage = Storage::disk('s3');
        }
        return $storage;
    }

    private function importTownshipsFromGeoData($geoData)
    {
        $this->getOutput()->writeln('processing data');
        $this->output->writeln('');
        foreach ($geoData['features'] as $townshipData) {
            $this->output->write('.');
//            $this->output->write('- ' . $townshipData['properties']['gemeentenaam']);

            /** @var Township $township */
            $township = Township::query()->updateOrCreate([
                'code' => $townshipData['properties']['code']
            ], [
                'name' => $townshipData['properties']['gemeentenaam'],
                'featureId' => $townshipData['id'],
            ]);

            Area::query()->firstOrCreate([
                'area_id' => $township->id,
                'area_type' => Township::class
            ]);
        }
    }
}
