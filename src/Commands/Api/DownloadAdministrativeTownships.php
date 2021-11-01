<?php

namespace Vng\EvaCore\Commands\Api;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class DownloadAdministrativeTownships extends Command
{
    const INTERNAL_FILENAME = 'latest-townships-administrative.json';

    protected $signature = 'eva:download-administrative-townships {--g|geom}';
    protected $description = 'Download (administrative borders) township geo-data from API';

    public function handle(): int
    {
        $withGeom = $this->option('geom');
        $this->getOutput()->writeln('downloading township admin...');
        $this->getOutput()->writeln('fetching from API');

        $client = new Client(['base_uri' => 'https://geodata.nationaalgeoregister.nl']);
        $response = $client->request('GET', 'bestuurlijkegrenzen/wfs', [
            'query' => [
                'request' => 'GetFeature',
                'service' => 'WFS',
                'version' => '2.0.0',
                'typeName' => 'gemeenten',
                'outputFormat' => 'json',
                'srsName' => 'EPSG:4326',
                'propertyName' => $withGeom ? 'code,gemeentenaam,geom' : 'code,gemeentenaam',
            ]
        ]);

        $responseContents = $response->getBody()->getContents();

        $name_prefix = date('dmy');
        $filename = $name_prefix . '-townships-administrative';

        $storage = $this->getStorage();
        $storage->put("fixed/{$filename}.json", $responseContents);
        $storage->put("fixed/" . self::INTERNAL_FILENAME, $responseContents);

        $this->output->writeln('downloading township admin finished!');
        return 0;
    }

    private function getStorage()
    {
        $storage = Storage::disk('local');
        if (!App::environment('local')) {
            $storage = Storage::disk('s3');
        }
        return $storage;
    }
}
