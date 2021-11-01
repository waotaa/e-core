<?php

namespace Vng\EvaCore\Commands\Api;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class DownloadRegions extends Command
{
    const INTERNAL_FILENAME = 'latest-regions.json';

    protected $signature = 'eva:download-regions {--g|geom}';
    protected $description = 'Download work-region (CBS) geo data from API';

    public function handle(): int
    {
        $withGeom = $this->option('geom');

        $this->getOutput()->writeln('downloading regions...');
        $this->getOutput()->writeln('fetching from API');

        $client = new Client(['base_uri' => 'https://geodata.nationaalgeoregister.nl']);
        $response = $client->request('GET', 'cbsgebiedsindelingen/wfs', [
            'query' => [
                'request' => 'GetFeature',
                'service' => 'WFS',
                'version' => '2.0.0',
                'typeName' => 'cbsgebiedsindelingen:cbs_arbeidsmarktregio_2020_gegeneraliseerd',
                'outputFormat' => 'json',
                'srsName' => 'EPSG:4326',
                'propertyName' => $withGeom ? 'statcode,statnaam,geom' : 'statcode,statnaam',
            ]
        ]);

        $responseContents = $response->getBody()->getContents();

        $name_prefix = date('dmy');
        $filename = $name_prefix . '-regions-cbs';

        $storage = $this->getStorage();
        $storage->put("fixed/{$filename}.json", $responseContents);
        $storage->put("fixed/" . self::INTERNAL_FILENAME, $responseContents);

        $this->getOutput()->writeln('downloading regions finished!');
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
