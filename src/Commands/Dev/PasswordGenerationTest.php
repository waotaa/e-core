<?php

namespace Vng\EvaCore\Commands\Dev;

use Vng\EvaCore\Services\PasswordService;
use Illuminate\Console\Command;

class PasswordGenerationTest extends Command
{
    protected $signature = 'dev:password-gen';
    protected $description = 'Test password generator';

    public function handle(): int
    {
        $this->getOutput()->writeln('creating passwords');
        for($i = 0; $i < 10; $i++) {
            $this->output->writeln(PasswordService::generatePassword());
        }
        return 0;
    }
}
