<?php

namespace Vng\EvaCore\Traits;

use Illuminate\Support\Facades\Schema;
use Vng\EvaCore\Models\Update;

trait UpdateTrait
{
    protected ?string $version = null;
    protected ?Update $update = null;
    protected array $tasks = [];

    public function hasUpdatesTable(): bool
    {
        $table = (new Update())->getTable();
        return Schema::hasTable($table);
    }

    public function hasUpdateEntitiy(): bool
    {
        return !is_null($this->update);
    }

    public function initializeUpdate(?string $version = null): void
    {
        $this->setVersion($version);
        $this->createUpdate();
    }

    protected function createUpdate()
    {
        if (!$this->hasUpdatesTable()) {
            return;
        }

        $version = $this->version;
        if (is_null($version))
        {
            throw new \Exception('Version number missing');
        }

        $this->update = Update::create([
            'version' => $version,
            'status' => Update::STATUS_INITIATED
        ]);
    }

    protected function setVersion(?string $version = null)
    {
        if (!is_null($version)) {
            $this->version = $version;
        }
    }

    public function completeTask($task)
    {
        $tasks = $this->addTask($task);

        if (!$this->hasUpdateEntitiy()) {
            // attempt to create a release if it's not there yet
            $this->initializeUpdate();

            if (!$this->hasUpdateEntitiy()) {
                // if still no release available we can't update
                return;
            }
        }

        $this->update->update([
            'status' => Update::STATUS_TASK_DONE,
            'tasks' => $tasks,
        ]);
    }

    protected function addTask($task): array
    {
        $this->tasks[] = $task;
        return $this->tasks;
    }

    public function completeUpdate()
    {
        if (!$this->hasUpdateEntitiy()) {
            throw new \Exception('Release functionality was not implemented during release');
        }

        $this->update->update([
            'status' => Update::STATUS_COMPLETED
        ]);
    }
}
