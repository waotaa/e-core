<?php

namespace Vng\EvaCore\Commands\Professionals;

use Vng\EvaCore\Models\Professional;
use Vng\EvaCore\Models\User;
use Illuminate\Console\Command;

class CreateProfessionalFromUsers extends Command
{
    protected $signature = 'professionals:create-from-users';
    protected $description = 'Create a professional for every user that isn\'t assigned a role';

    public function handle(): int
    {
        $users = User::query()->doesntHave('roles')->get();

        $users->each(function(User $user) {
            Professional::query()->firstOrCreate([
                'email' => $user->getAttribute('email')
            ]);
            $user->forceDelete();
        });

        return 0;
    }
}
