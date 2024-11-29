<?php

namespace Vng\EvaCore\Commands;

use Vng\EvaCore\Repositories\EnvironmentRepositoryInterface;

trait EnvironmentArgument
{
    public function getTargetedEnvironments($environmentArgument = null): array
    {
        /** @var EnvironmentRepositoryInterface $environmentRepository */
        $environmentRepository = app(EnvironmentRepositoryInterface::class);

        if (is_null($environmentArgument)) {
            return $environmentRepository
                ->all()
                ->toArray();
        }

        if (is_string($environmentArgument)) {
            return $environmentRepository
                ->builder()
                ->where('slug', $environmentArgument)
                ->get()
                ->toArray();
        }

        return $environmentRepository
            ->builder()
            ->where('id', $environmentArgument)
            ->get()
            ->toArray();
    }
}