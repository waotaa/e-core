<?php

namespace Vng\EvaCore\Console\Commands\Format;

use Vng\EvaCore\Models\ClientCharacteristic;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Models\Location;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class MigrateToFormat2 extends Command
{
    protected $signature = 'format:migrate-2';
    protected $description = 'Migrate format 1 fields to format 2';

    public function handle(): int
    {
        $this->getOutput()->writeln('starting migrating to format 2...');

        $this->call('migrate', ['--force' => true]);
        $this->call('setup:seed-characteristics');

        $this->output->writeln('migrate instrument properties');
        $this->migrateInstrumentProperties();
        $this->output->newLine(2);

        $this->output->writeln('migrate providers');
        $this->migrateProviders();
        $this->output->newLine(2);

        $this->output->writeln('migrate themes to client characteristics');
        $this->migrateThemesToClientCharacteristics();
        $this->output->newLine(2);

        $this->output->writeln('migrate addresses');
        $this->migrateInstrumentAddresses();
        $this->output->newLine(2);

        $this->output->writeln('migrate location');
        $this->migrateLocation();
        $this->output->newLine(2);

        $this->getOutput()->writeln('migrating to format 2 finished!');
        return 0;
    }

    private function migrateInstrumentProperties()
    {
        $instruments = Instrument::withTrashed()->get();

        $instruments->each(function (Instrument $instrument) {
            $short_description = $instrument->short_description ?: ' ';
            $instrument->summary = $instrument->summary ?: $short_description;
            $instrument->participation_conditions = $instrument->participation_conditions ?: $instrument->conditions;
            $instrument->additional_information = $instrument->additional_information ?: $instrument->description;
            $instrument->total_duration_value = $instrument->total_duration_value ?: (int) $instrument->duration;
            $instrument->total_duration_unit = $instrument->total_duration_unit ?: $instrument->duration_unit;
            $instrument->total_costs = $instrument->total_costs ?: $instrument->costs;
            $instrument->intensity_duration_costs_description = $instrument->intensity_duration_costs_description ?: $instrument->duration;
            $instrument->saveQuietly();
        });
    }

    private function migrateThemesToClientCharacteristics()
    {
        $this->migrateThemeBasisvaardigheden();
        $this->migrateThemeWerknemersvaardigheden();
        $this->migrateThemePraktischeBelemmeringen();
        $this->migrateThemeSollicitatieVaardigheden();
        $this->migrateThemeDoenEnErvaren();
        $this->migrateThemeCertificaat();
        $this->migrateThemeDiplomeren();
    }

    private function migrateThemeBasisvaardigheden()
    {
        $instruments = Instrument::withTrashed()->whereHas('themes', function(Builder $query) {
            $query->where([
                'description' => 'Basisvaardigheden',
                'custom' => false
            ]);
        })->get();

        if ($instruments->count()) {
            /** @var ClientCharacteristic $characteristic */
            $characteristic = ClientCharacteristic::query()->where([
                'name' => 'Basisvaardigheden',
            ])->firstOrFail();

            ClientCharacteristic::withoutEvents(
                fn () => $characteristic->instruments()->sync($instruments->map(fn (Instrument $i) => $i->id))
            );

//            $characteristic->instruments()->sync($instruments->map(fn (Instrument $i) => $i->id));
        }
    }

    private function migrateThemeWerknemersvaardigheden()
    {
        $instruments = Instrument::withTrashed()->whereHas('themes', function(Builder $query) {
            $query->where([
                'description' => 'Werknemersvaardigheden',
                'custom' => false
            ]);
        })->get();

        if ($instruments->count()) {
            /** @var ClientCharacteristic $characteristic */
            $characteristic = ClientCharacteristic::query()->where([
                'name' => 'Basis werknemersvaardigheden',
            ])->firstOrFail();

            ClientCharacteristic::withoutEvents(
                fn () => $characteristic->instruments()->sync($instruments->map(fn (Instrument $i) => $i->id))
            );

//            $characteristic->instruments()->sync($instruments->map(fn (Instrument $i) => $i->id));
        }
    }

    private function migrateThemePraktischeBelemmeringen()
    {
        $instruments = Instrument::withTrashed()->whereHas('themes', function(Builder $query) {
            $query->where([
                'description' => 'Praktische belemmeringen',
                'custom' => false
            ]);
        })->get();

        if ($instruments->count()) {
            /** @var ClientCharacteristic $characteristic */
            $characteristic = ClientCharacteristic::query()->where([
                'name' => 'Praktische aandachtspunten',
            ])->firstOrFail();

            ClientCharacteristic::withoutEvents(
                fn () => $characteristic->instruments()->sync($instruments->map(fn (Instrument $i) => $i->id))
            );

//            $characteristic->instruments()->sync($instruments->map(fn (Instrument $i) => $i->id));
        }
    }

    private function migrateThemeSollicitatieVaardigheden()
    {
        $instruments = Instrument::withTrashed()->whereHas('themes', function(Builder $query) {
            $query->where([
                'description' => 'Sollicitatie vaardigheden',
                'custom' => false
            ]);
        })->get();

        if ($instruments->count()) {
            /** @var ClientCharacteristic $characteristic */
            $characteristic = ClientCharacteristic::query()->where([
                'name' => 'Sollicitatievaardigheden',
            ])->firstOrFail();

            ClientCharacteristic::withoutEvents(
                fn () => $characteristic->instruments()->sync($instruments->map(fn (Instrument $i) => $i->id))
            );

//            $characteristic->instruments()->sync($instruments->map(fn (Instrument $i) => $i->id));
        }
    }

    private function migrateThemeDoenEnErvaren()
    {
        $instruments = Instrument::withTrashed()->whereHas('themes', function(Builder $query) {
            $query->where([
                'description' => 'Doen en ervaren',
                'custom' => false
            ]);
        })->get();

        if ($instruments->count()) {
            /** @var ClientCharacteristic $characteristic */
            $characteristic = ClientCharacteristic::query()->where([
                'name' => 'Diplomeren, certificeren, en praktijkverklaring',
            ])->firstOrFail();

            ClientCharacteristic::withoutEvents(
                fn () => $characteristic->instruments()->sync($instruments->map(fn (Instrument $i) => $i->id))
            );

//            $characteristic->instruments()->sync($instruments->map(fn (Instrument $i) => $i->id));
        }
    }

    private function migrateThemeCertificaat()
    {
        $instruments = Instrument::withTrashed()->whereHas('themes', function(Builder $query) {
            $query->where([
                'description' => 'Certificaat',
                'custom' => false
            ]);
        })->get();

        if ($instruments->count()) {
            /** @var ClientCharacteristic $characteristic */
            $characteristic = ClientCharacteristic::query()->where([
                'name' => 'Diplomeren, certificeren, en praktijkverklaring',
            ])->firstOrFail();

            ClientCharacteristic::withoutEvents(
                fn () => $characteristic->instruments()->sync($instruments->map(fn (Instrument $i) => $i->id))
            );

//            $characteristic->instruments()->sync($instruments->map(fn (Instrument $i) => $i->id));
        }
    }

    private function migrateThemeDiplomeren()
    {
        $instruments = Instrument::withTrashed()->whereHas('themes', function(Builder $query) {
            $query->where([
                'description' => 'Diplomeren',
                'custom' => false
            ]);
        })->get();

        if ($instruments->count()) {
            /** @var ClientCharacteristic $characteristic */
            $characteristic = ClientCharacteristic::query()->where([
                'name' => 'Diplomeren, certificeren, en praktijkverklaring',
            ])->firstOrFail();

            ClientCharacteristic::withoutEvents(
                fn () => $characteristic->instruments()->sync($instruments->map(fn (Instrument $i) => $i->id))
            );

//            $characteristic->instruments()->sync($instruments->map(fn (Instrument $i) => $i->id));
        }
    }

    public function migrateInstrumentAddresses()
    {
        $instruments = Instrument::withTrashed()->has('address')->get();

        $instruments->each(function (Instrument $instrument) {
            $instrument->addresses()->syncWithoutDetaching($instrument->address);
        });
    }

    public function migrateLocation()
    {
        $employerInstruments = Instrument::withTrashed()
            ->where('location', 'Adres')
            ->orWhere('location', 'address')
            ->get();

        /** @var Location $employerLocation */
        $employerLocation = Location::query()->where('name', 'Adres')->firstOrFail();
        $employerLocation->instruments()->syncWithoutDetaching($employerInstruments->pluck('id'));


        $employerInstruments = Instrument::withTrashed()
            ->where('location', 'Aanbieder')
            ->orWhere('location', 'provider')
            ->get();

        /** @var Location $employerLocation */
        $employerLocation = Location::query()->where('name', 'Aanbieder')->firstOrFail();
        $employerLocation->instruments()->syncWithoutDetaching($employerInstruments->pluck('id'));


        $employerInstruments = Instrument::withTrashed()
            ->where('location', 'Werkgever')
            ->orWhere('location', 'employer')
            ->get();

        /** @var Location $employerLocation */
        $employerLocation = Location::query()->where('name', 'Werkgever')->firstOrFail();
        $employerLocation->instruments()->syncWithoutDetaching($employerInstruments->pluck('id'));


        $employerInstruments = Instrument::withTrashed()
            ->where('location', 'Gemeente')
            ->orWhere('location', 'township')
            ->get();

        /** @var Location $employerLocation */
        $employerLocation = Location::query()->where('name', 'Gemeente')->firstOrFail();
        $employerLocation->instruments()->syncWithoutDetaching($employerInstruments->pluck('id'));


        $employerInstruments = Instrument::withTrashed()
            ->where('location', 'Klant thuis')
            ->orWhere('location', 'client')
            ->get();

        /** @var Location $employerLocation */
        $employerLocation = Location::query()->where('name', 'Klant thuis')->firstOrFail();
        $employerLocation->instruments()->syncWithoutDetaching($employerInstruments->pluck('id'));
    }

    public function migrateProviders()
    {
        $instruments = Instrument::withTrashed()->get();

        $instruments->each(function(Instrument $instrument) {
            $firstProvider = $instrument->providers->first();
            if (!is_null($firstProvider)) {
                $instrument->provider()->associate($firstProvider);
                $instrument->save();
            }
        });
    }
}
