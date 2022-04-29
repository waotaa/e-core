<?php

namespace Vng\EvaCore\Commands\Geo;

use Vng\EvaCore\Services\GeoComparison\GeoComparison;
use Vng\EvaCore\Services\GeoComparison\GeoDataComparisonService;
use Vng\EvaCore\Services\GeoData\BasicGeoModel;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

abstract class GeoCheckCommand extends Command
{
    protected string $labelCollectionA = 'A';
    protected string $labelCollectionB = 'B';

    protected bool $yesToAll = false;

    public function checkItemsMissingFromCollectionA(GeoDataComparisonService $comparisonService): Collection
    {
        $count = $comparisonService->getItemCountCollectionA();
        $this->output->writeln('Item count collection [' . $this->labelCollectionA . '] ' . $count);
        $missingItems = $comparisonService->findItemsNotInCollectionA();
        $this->outputMissingItems($missingItems, $this->labelCollectionA);
        return $missingItems;
    }

    public function checkItemsMissingFromCollectionB(GeoDataComparisonService $comparisonService): Collection
    {
        $count = $comparisonService->getItemCountCollectionB();
        $this->output->writeln('Item count collection [' . $this->labelCollectionB . '] ' . $count);
        $missingItems = $comparisonService->findItemsNotInCollectionB();
        $this->outputMissingItems($missingItems, $this->labelCollectionB);
        return $missingItems;
    }

    private function outputMissingItems(Collection $collection, string $label = null)
    {
        $collection->each(
            function (BasicGeoModel $geoModel) use ($label) {
                $message = 'Item [' . $geoModel->getName() . ' / ' . $geoModel->getCode() . '] not found in collection';
                if (!is_null($label)) {
                    $message .= ' ' . $label;
                }
                $this->output->warning($message);
            }
        );
    }

    protected function handleMissingItems(Collection $missingItems, callable $handleMissingItem)
    {
        $missingItems->each(function (BasicGeoModel $geoModel) use ($handleMissingItem) {
            $this->output->writeln($geoModel->getName() . ' [' . $geoModel->getCode() . '] not found');
            if ($this->yesToAll || $this->confirm('Do you want to handle (add/remove + reallocate) it?', true)) {
                $handleMissingItem($geoModel);
            }
        });
    }

    public function checkDeviatingItems(
        GeoDataComparisonService $comparisonService,
        callable $handleDeviation = null,
        array $attributes = null
    ) {
        $this->showDeviationSummary($comparisonService, $attributes);
        $this->walkTroughDeviations($comparisonService, $handleDeviation, $attributes);
    }

    private function showDeviationSummary(
        GeoDataComparisonService $comparisonService,
        array $attributes = null
    ) {
        $deviatingItems = $comparisonService->getDeviations($attributes);
        if ($deviatingItems->count() === 0) {
            $this->output->info('No deviations found');
            return;
        }

        $this->output->info($deviatingItems->count() . ' deviations found');
        $this->table(
            ['Code', 'Deviations'],
            $deviatingItems->map(function (GeoComparison $comparison) {
                return [
                    $comparison->getItemsCode(),
                    join(', ', $comparison->getDeviations())
                ];
            })
        );
    }

    private function walkTroughDeviations(
        GeoDataComparisonService $comparisonService,
        callable $handleDeviation = null,
        array $attributes = null
    ) {
        $deviatingItems = $comparisonService->getDeviations($attributes);
        if ($deviatingItems->count() === 0
            || !($this->yesToAll || $this->confirm('Walk through deviations?', true))) {
            return;
        }

        $deviatingItems->each(function (GeoComparison $comparison) use ($deviatingItems, $handleDeviation) {
            $this->table(
                ['Code', 'Attribute', $this->labelCollectionA, $this->labelCollectionB],
                array_map(function ($deviation) use ($comparison) {
                    return $comparison->getAttributeComparison($deviation);
                }, $comparison->getDeviations())
            );

            $isNotLastItem = $deviatingItems->last()->getItemsCode() !== $comparison->getItemsCode();
            if (!is_null($handleDeviation) && ($this->yesToAll || $this->confirm('handle deviation?'))) {
                $handleDeviation($comparison);
            } elseif ($isNotLastItem && !$this->confirm('continue?', true)) {
                // break
                return false;
            }
            return null;
        });
    }
}
