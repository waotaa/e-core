<?php

namespace Vng\EvaCore\Commands\Api;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class DownloadTopographicTownships extends Command
{
    const INTERNAL_FILENAME = 'latest-townships-topographic.json';

    protected $signature = 'eva:download-topographic-townships {--g|geom}';
    protected $description = 'Download (topographic neighbourhood) township geo-data from API';

    public function handle(): int
    {
        $withGeom = $this->option('geom');
        $this->getOutput()->writeln('downloading township topo...');
        $this->getOutput()->writeln('fetching from API');

        $client = new Client(['base_uri' => 'https://geodata.nationaalgeoregister.nl']);
        $response = $client->request('GET', 'wijkenbuurten2020/wfs', [
            'query' => [
                'request' => 'GetFeature',
                'service' => 'WFS',
                'version' => '2.0.0',
                'typeName' => 'gemeenten2020',
                'outputFormat' => 'json',
                'srsName' => 'EPSG:4326',
                'propertyName' => $withGeom ? 'gemeentecode,gemeentenaam,geom' : 'gemeentecode,gemeentenaam',
            ]
        ]);

        $responseContents = $response->getBody()->getContents();

        $name_prefix = date('dmy');
        $filename = $name_prefix . '-townships-topographic';

        $storage = $this->getStorage();
        $storage->put("fixed/{$filename}.json", $responseContents);
        $storage->put("fixed/" . self::INTERNAL_FILENAME, $responseContents);

        $this->getOutput()->writeln('downloading township topo finished!');
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
