<?php

namespace Vng\EvaCore\Traits;

use Illuminate\Support\Facades\Schema;
use Vng\EvaCore\Models\Release;

trait ReleaseTrait
{
    protected ?string $version = null;
    protected ?Release $release = null;
    protected array $tasks = [];

    public function hasReleasesTable(): bool
    {
        $table = (new Release())->getTable();
        return Schema::hasTable($table);
    }

    public function hasReleaseEntitiy(): bool
    {
        return !is_null($this->release);
    }

    public function initializeRelease(?string $version = null): void
    {
        $this->setVersion($version);
        $this->createRelease();
    }

    protected function createRelease()
    {
        if (!$this->hasReleasesTable()) {
            return;
        }

        $version = $this->version;
        if (is_null($version))
        {
            throw new \Exception('Version number missing');
        }

        $this->release = Release::create([
            'version' => $version,
            'status' => Release::STATUS_INITIATED
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

        if (!$this->hasReleaseEntitiy()) {
            // attempt to create a release if it's not there yet
            $this->initializeRelease();

            if (!$this->hasReleaseEntitiy()) {
                // if still no release available we can't update
                return;
            }
        }

        $this->release->update([
            'status' => Release::STATUS_TASK_DONE,
            'tasks' => $tasks,
        ]);
    }

    protected function addTask($task): array
    {
        $this->tasks[] = $task;
        return $this->tasks;
    }

    public function completeRelease()
    {
        if (!$this->hasReleaseEntitiy()) {
            throw new \Exception('Release functionality was not implemented during release');
        }

        $this->release->update([
            'status' => Release::STATUS_COMPLETED
        ]);
    }
}
