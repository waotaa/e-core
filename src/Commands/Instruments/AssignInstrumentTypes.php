<?php

namespace Vng\EvaCore\Commands\Instruments;

use Illuminate\Console\Command;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Models\InstrumentType;

class AssignInstrumentTypes extends Command
{
    protected $signature = 'instrument:assign-type {type?}';
    protected $description = 'Assigns an instrument type to instruments without them';

    public function handle(): int
    {
        $this->output->writeln('assigning instrument types...');

        $instrumentType = $this->getInstrumentType();

        $affectedInstruments = Instrument::query()->whereNull('instrument_type_id')->update([
            'instrument_type_id' => $instrumentType->id
        ]);
        $this->output->writeln('applied to ' . $affectedInstruments . ' instruments');

        $this->output->writeln('assigning instrument types finished!');
        return 0;
    }

    private function getInstrumentType(): InstrumentType
    {
        $type = $this->argument('type');
        if (is_null($type)) {
            $type = config('eva-core.instrument.dedicatedType');
        }
        /** @var ?InstrumentType $instrumentType */
        $instrumentType = InstrumentType::query()->where('name', $type)->firstOrFail();
        if (is_null($instrumentType)) {
            throw new \Exception('Cannot find instrument type with given input');
        }
        return $instrumentType;
    }
}
