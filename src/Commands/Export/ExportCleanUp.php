<?php

namespace Vng\EvaCore\Commands\Export;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Vng\EvaCore\Enums\ExportStatusEnum;
use Vng\EvaCore\Models\Export;
use Vng\EvaCore\Repositories\ExportRepositoryInterface;

class ExportCleanUp extends Command
{
    protected $signature = 'export:clean';
    protected $description = 'Clean up expired exports';

    public function __construct(
        protected ExportRepositoryInterface $exportRepository,
    )
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Starting export cleanup...');

        // Huidige tijd
        $now = Carbon::now();

        // Verwijder exports met status 'Done' ouder dan een maand

        $expiredDoneExports = $this->exportRepository->builder()
            ->where('status', ExportStatusEnum::done())
            ->where('updated_at', '<', $now->subMonth())
            ->get();

        $deletedDoneCount = $this->deleteExports($expiredDoneExports);

        // Verwijder exports met andere statussen ouder dan 48 uur
        $expiredOtherExports = $this->exportRepository->builder()
            ->where('status', '!=', ExportStatusEnum::done())
            ->where('updated_at', '<', $now->subHours(48))
            ->get();

        $deletedOtherCount = $this->deleteExports($expiredOtherExports);

        $this->info("Deleted $deletedDoneCount exports with status 'Done'.");
        $this->info("Deleted $deletedOtherCount exports with other statuses.");

        return self::SUCCESS;
    }

    private function deleteExports($exports): int
    {
        $count = 0;
        /** @var Export $export */
        foreach ($exports as $export) {
            $export->delete();
            $count++;
        }
        return $count;
    }
}
