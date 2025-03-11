<?php

namespace Vng\EvaCore\Commands;

use Illuminate\Support\Collection;
use Vng\EvaCore\Repositories\EnvironmentRepositoryInterface;

trait EnvironmentArgumentTrait
{
    public function getTargetedEnvironments($environmentArgument = null): Collection
    {
        /** @var EnvironmentRepositoryInterface $environmentRepository */
        $environmentRepository = app(EnvironmentRepositoryInterface::class);

        if (is_null($environmentArgument)) {
            return $environmentRepository
                ->all();
        }

        if (is_string($environmentArgument)) {
            return $environmentRepository
                ->builder()
                ->where('slug', $environmentArgument)
                ->get();
        }

        return $environmentRepository
            ->builder()
            ->where('id', $environmentArgument)
            ->get();
    }
}