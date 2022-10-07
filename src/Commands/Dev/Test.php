<?php

namespace Vng\EvaCore\Commands\Dev;

use Vng\EvaCore\Models\Environment;
use Vng\EvaCore\Models\Professional;
use Illuminate\Console\Command;

class Test extends Command
{
    protected $signature = 'dev:test';
    protected $description = 'Custom test';

    public function handle(): int
    {
        $this->getOutput()->writeln('testing');
        $p = new Professional([
            'email' => 'test@example.com',
        ]);
        $e = Environment::query()->where('slug', 'rsd-de-liemers')->firstOrFail();
        $p->environment()->associate($e);
        $p->save();
        return 0;
    }
}
