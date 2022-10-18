<?php

namespace Vng\EvaCore\Commands\Setup;

use Illuminate\Console\Command;
use Vng\EvaCore\Models\Implementation;
use Vng\EvaCore\Models\Instrument;
use Webpatser\Uuid\Uuid;

class CreateTestInstrument extends Command
{
    protected $signature = 'setup:create-test-instrument';
    protected $description = 'Create a instrument for testing purposes';

    public function handle(): int
    {
        $this->getOutput()->writeln('creating test instrument...');

        (new Instrument([
            'name' => 'Test instrument',
            'uuid' => (string) Uuid::generate(4),
            'is_active' => true,
            'summary' => 'samenvatting',
            'method' => 'werkwijze',
        ]))
            ->implementation()->associate(Implementation::query()->first())
            ->saveQuietly();

        $this->getOutput()->writeln('creating test instrument finished!');
        return 0;
    }
}
