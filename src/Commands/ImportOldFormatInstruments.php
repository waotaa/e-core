<?php

namespace Vng\EvaCore\Commands;

use Vng\EvaCore\Enums\CostsUnitEnum;
use Vng\EvaCore\Enums\DurationUnitEnum;
use Vng\EvaCore\Models\Partnership;
use Vng\EvaCore\Models\Link;
use Vng\EvaCore\Models\Provider;
use Vng\EvaCore\Models\Address;
use Vng\EvaCore\Models\Contact;
use Vng\EvaCore\Models\TargetGroup;
use Vng\EvaCore\Models\Township;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Models\Region;
use Vng\EvaCore\Models\Tile;
use Vng\EvaCore\Models\Theme;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Webpatser\Uuid\Uuid;

class ImportOldFormatInstruments extends Command
{
    protected $signature = 'eva:import-old-instruments {json-file} {mark} {--r|region=} {--t|township=} {--p|partnership=}';

    protected $description = 'Import instruments from a export json';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->output->writeln('getting file...');

        $instruments = $this->getInstrumentsFromFile();

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

    private function getOwnerFromOptions(): ?Model
    {
        $regionSlug = $this->option('region');
        $townshipSlug = $this->option('township');
        $partnershipSlug = $this->option('partnership');

        if (!$regionSlug && !$townshipSlug && !$partnershipSlug) {
            return null;
        }

        $this->output->writeln('fetching owner...');

        if (!is_null($regionSlug) && !is_null($townshipSlug)) {
            exit('cannot pass both region and township');
        }
        if (!is_null($regionSlug) && !is_null($partnershipSlug)) {
            exit('cannot pass both region and environment');
        }
        if (!is_null($townshipSlug) && !is_null($partnershipSlug)) {
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

        if ($partnershipSlug) {
            $partnership = Partnership::query()->where('slug', $partnershipSlug)->first();
            if (is_null($partnership)) {
                exit('given partnership not found');
            }
            $this->output->writeln($partnership->name . ' found!');
            return $partnership;
        }
        return null;
    }

    private function addInstrument($instrumentData, $owner = null): Instrument
    {
        if (is_null($owner)) {
            $owner = $this->findOwner($instrumentData['owner']);
        }

        $instrument = new Instrument();
        $instrument->fill([
            'uuid' => $instrumentData['uuid'] ?? (string)Uuid::generate(4),
            'did' => $instrumentData['did'],

            'name' => $instrumentData['name'],
            'is_active' => (bool)$instrumentData['is_active'],
            'is_nationally_available' => (bool)$instrumentData['is_nationally_available'] ?? false,

            // descriptions
            'short_description' => $instrumentData['short_description'],
            'description' => $instrumentData['description'],
            'application_instructions' => $instrumentData['application_instructions'],
            'conditions' => $instrumentData['conditions'],
            'distinctive_approach' => $instrumentData['distinctive_approach'],
            'cooperation_partners' => $instrumentData['cooperation_partners'],

            // right sidebar
            'aim' => $instrumentData['aim'],

            // info section
            'costs' => (float)$instrumentData['costs'],
            'costs_unit' => $this->getCostsUnit($instrumentData['costs_unit']),
            'duration' => $instrumentData['duration'],
            'duration_unit' => $this->getDurationUnit($instrumentData['duration_unit']),

            'import_mark' => $this->argument('mark'),
        ]);

        // Owner
        $instrument->saveQuietly();
        if (!is_null($owner)) {
            $owner->ownedInstruments()->save($instrument);
        }

        // Providers
        collect($instrumentData['providers'])->each(function ($provider) use ($instrument, $owner) {
            $provider = $this->findOrCreateProvider($provider, $owner);
            $instrument->providers()->attach($provider);
        });

        // Work Landscape
        $tileInstances = $this->findTiles($instrumentData['tiles']);
        $tileInstances->each(function ($tile) use ($instrument) {
            if (is_null($tile)) {
                return;
            }
            $instrument->tiles()->attach($tile->id);
        });

        if (isset($instrumentData['address'])
            && !is_null($instrumentData['address']['straatnaam'])
            && !is_null($instrumentData['address']['huisnummer'])
            && !is_null($instrumentData['address']['postcode'])
            && !is_null($instrumentData['address']['woonplaats'])
        ) {
            $this->findOrCreateAddress($instrument, $instrumentData['address']);
        }

        if ($instrumentData['contact_persoon_intern']['name']) {
            $this->findOrCreateContact($instrument, [
                'name' => $instrumentData['contact_persoon_intern']['name'],
                'phone' => $instrumentData['contact_persoon_intern']['phone'],
                'email' => $instrumentData['contact_persoon_intern']['email'],
            ]);
        }

        if ($instrumentData['contact_persoon_extern']['name']) {
            $this->findOrCreateContact($instrument, [
                'name' => $instrumentData['contact_persoon_extern']['name'],
                'phone' => $instrumentData['contact_persoon_extern']['phone'],
                'email' => $instrumentData['contact_persoon_extern']['email'],
            ]);
        }


        $themeInstances = $this->findThemes($instrumentData['themes']);
        $themeInstances->each(function ($theme) use ($instrument) {
            if (!is_null($theme)) {
                $instrument->themes()->attach($theme->id);
            }
        });

        $targetGroupInstances = $this->findOrCreateTargetGroups($instrumentData['target_groups']);
        $targetGroupInstances->each(function ($targetGroup) use ($instrument) {
            if (!is_null($instrument)) {
                $instrument->targetGroups()->attach($targetGroup->id);
            }
        });

        $this->importLinks($instrument, $instrumentData['links']);

        return $instrument;
    }

    private function findOwner($ownerData): ?Model
    {
        if (is_null($ownerData)) {
            return null;
        }

        switch ($ownerData['type']) {
            case "App\\Models\\Former\\Regio":
                return Region::query()->where('name', $ownerData['name'])->firstOrFail();
            case "App\\Models\\Former\\Gemeente":
                return Township::query()->where('name', $ownerData['name'])->firstOrFail();
        }
        return null;
    }

    public function getCostsUnit($data)
    {
        switch ($data) {
            case 'Per VOG':
                return CostsUnitEnum::once()->getKey();
            case 'uur':
            case 'per uur':
            case 'uurtarief':
            case 'euro\'s per uur':
                return CostsUnitEnum::hour()->getKey();
            case 'per maand':
                return CostsUnitEnum::month()->getKey();
            case 'lesgeld per schooljaar':
                return CostsUnitEnum::year()->getKey();
            case 'per advies':
            case 'per intake':
                return CostsUnitEnum::advice()->getKey();
            case 'per training':
                return CostsUnitEnum::session()->getKey();
            case 'traject':
            case 'per traject':
            case 'Per traject.':
            case 'per onderzoek':
            case 'Per onderzoek':
                return CostsUnitEnum::package()->getKey();
        }

        return null;
    }

    public function getDurationUnit($data)
    {
        switch ($data) {
            case 'uren':
            case 'Uren':
            case 'per uur':
                return DurationUnitEnum::hour()->getKey();
            case 'dagen':
            case 'dagdelen':
                return DurationUnitEnum::day()->getKey();
            case 'weken':
                return DurationUnitEnum::week()->getKey();
            case 'maanden':
                return DurationUnitEnum::month()->getKey();
            case 'jaren':
                return DurationUnitEnum::year()->getKey();
        }

        return null;
    }

    public function findOrCreateProvider($providerData, $owner): Model
    {
        $provider = Provider::query()->firstOrCreate(['name' => $providerData['name']]);
        $provider->save();

        if (isset($providerData['address'])
            && !is_null($providerData['address']['straatnaam'])
            && !is_null($providerData['address']['postcode'])
            && !is_null($providerData['address']['woonplaats'])
        ) {
            Address::query()->firstOrCreate([
                'addressable_id' => $provider->id,
                'addressable_type' => Provider::class,
                'straatnaam' => $providerData['address']['straatnaam'],
                'huisnummer' => $providerData['address']['huisnummer'],
                'postcode' => $providerData['address']['postcode'],
                'woonplaats' => $providerData['address']['woonplaats'],
            ]);
        }

        if (is_null($owner)) {
            return $provider;
        }

        $provider->owner()->associate($owner);
        $provider->save();

        return $provider;
    }

    public function findTiles(array $tiles): Collection
    {
        return collect($tiles)->map(fn($tile) => $this->findTile($tile));
    }

    public function findTile($tile): ?Model
    {
        $tileName = $tile['name'];
        switch ($tileName) {
            case 'Werkfit':
                $tileKey = 'werk_fit';
                break;
            case 'Oriënteren':
                $tileKey = 'orientatie';
                break;
            case 'Intake':
                $tileKey = 'diagnostiek';
                break;
            case 'Maatschappelijk fit':
                $tileKey = 'maatschappelijk_fit';
                break;
            default:
                $tileKey = strtolower($tileName);
                break;
        }

        return Tile::query()
            ->where('key', $tileKey)
            ->first();
    }

    public function findOrCreateAddress(Instrument $instrument, $addressData): Model
    {
        return Address::query()
            ->firstOrCreate([
                'addressable_id' => $instrument->id,
                'addressable_type' => Instrument::class,
                'straatnaam' => $addressData['straatnaam'],
                'huisnummer' => $addressData['huisnummer'],
                'postcode' => $addressData['postcode'],
                'woonplaats' => $addressData['woonplaats'],
            ]);
    }

    public function findOrCreateContact(Instrument $instrument, $contactData): Model
    {
        return Contact::query()
            ->updateOrCreate(
                [
                    'contactable_id' => $instrument->id,
                    'contactable_type' => Instrument::class,
                    'name' => $contactData['name'],
                ], [
                    'phone' => $contactData['phone'],
                    'email' => $contactData['email'],
                    'type' => $contactData['type'] ?? null,
                ]
            );
    }

    public function findThemes(array $themes): Collection
    {
        return collect($themes)->map(fn($theme) => $this->findTheme($theme));
    }

    public function findTheme($theme): ?Model
    {
        return Theme::query()->where('description', $theme['description'])->first();
    }

    public function findOrCreateTargetGroups(array $targetGroups): Collection
    {
        return collect($targetGroups)->map(fn($targetGroup) => $this->findOrCreateTargetGroup($targetGroup));
    }

    public function findOrCreateTargetGroup($targetGroup): Model
    {
        return TargetGroup::query()
            ->firstOrCreate([
                'description' => $targetGroup['description'],
            ], [
                'description' => $targetGroup['description'],
                'custom' => true,
            ]);
    }

    public function importLinks(Instrument $instrument, $linkData)
    {
        foreach ($linkData as $linkDataEntry) {
            $link = new Link();
            $link->fill([
                'label' => $linkDataEntry['label'],
                'url' => $linkDataEntry['url'],
            ]);
            $instrument->links()->save($link);
        }
    }
}
