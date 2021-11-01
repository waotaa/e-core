<?php

namespace Vng\EvaCore\Commands\Reallocation;

use Vng\EvaCore\Models\Environment;
use Vng\EvaCore\Models\Partnership;
use Vng\EvaCore\Models\Region;
use Vng\EvaCore\Models\Township;
use Illuminate\Console\Command;

abstract class ReallocationBaseCommand extends Command
{
    private $ownerTypes = [
        'environment',
        'partnership',
        'region',
        'township'
    ];

    protected function getInput()
    {
        $force = $this->option('force');
        $currentOwner = $this->argument('currentOwner');
        $newOwner = $this->argument('newOwner');
        $currentOwnerType = $this->argument('currentOwnerTypeOrNewOwner');
        $newOwnerType = $this->argument('newOwnerType');

        $ownerTypeProvided = !is_null($newOwner);
        if (!$ownerTypeProvided) {
            $newOwner = $this->argument('currentOwnerTypeOrNewOwner');
        }

        if (is_null($currentOwnerType) || !in_array($currentOwnerType, $this->ownerTypes)) {
            $this->output->writeln('no valid owner type found for the current owner');
            if ($force) {
                return null;
            }
            $currentOwnerType = $this->askForType();
        }

        if (is_null($newOwnerType) || !in_array($newOwnerType, $this->ownerTypes)) {
            $this->output->writeln('no valid owner type found for the new owner');
            if ($force) {
                return null;
            }
            $newOwnerType = $this->askForType();
        }

        $currentOwnerInstance = $this->findOwnerModel($currentOwnerType, $currentOwner);
        $newOwnerInstance = $this->findOwnerModel($newOwnerType, $newOwner);

        $this->table(['owner', 'type', 'name/slug', 'id'], [
            ['current', $currentOwnerType, $currentOwner, $currentOwnerInstance->id],
            ['new', $newOwnerType, $newOwner, $newOwnerInstance->id],
        ]);

        if (!$force && !$this->confirm('Is the data shown above correct?')) {
            $this->output->writeln('exiting command, please try again');
            return null;
        }

        return [
            'current' => $currentOwnerInstance,
            'new' => $newOwnerInstance,
        ];
    }

    private function askForType()
    {
        return $this->choice('What type of owner is that?', $this->ownerTypes);
    }

    private function findOwnerModel(string $type, string $query)
    {
        if ($type === 'environment') {
            return Environment::query()
                ->where('name', $query)
                ->orWhere('slug', $query)
                ->firstOrFail();
        } elseif ($type === 'region') {
            return Region::query()
                ->where('name', $query)
                ->orWhere('slug', $query)
                ->firstOrFail();
        } elseif ($type === 'township') {
            return Township::query()
                ->where('name', $query)
                ->orWhere('slug', $query)
                ->firstOrFail();
        } elseif($type === 'partnership') {
            return Partnership::query()
                ->where('name', $query)
                ->orWhere('slug', $query)
                ->firstOrFail();
        }
        return null;
    }
}
