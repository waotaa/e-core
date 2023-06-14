<?php

namespace Vng\EvaCore\Commands\ImExport;

use Illuminate\Console\Command;
use Vng\EvaCore\Repositories\OrganisationRepositoryInterface;
use Vng\EvaCore\Services\Instrument\InstrumentExportService;

class ExportInstruments extends Command
{
    protected $signature = 'export:instruments {mark?} {--o|org=}';
    protected $description = 'Create a json file with all instrument data. This json can also be used for an import';

    public function handle(): int
    {
        $this->output->writeln('exporting instruments');

        $mark = $this->argument('mark');
        $service = InstrumentExportService::make($mark);

        if ($mark) {
            $this->output->info('With mark: ' . $mark);
        }

        $organisationOption = $this->option('org');
        if ($organisationOption) {
            /** @var OrganisationRepositoryInterface $orgRepo */
            $orgRepo = app(OrganisationRepositoryInterface::class);

            if (is_numeric($organisationOption)) {
                $organisation = $orgRepo->find($organisationOption)->first();
            } else {
                $organisations = $orgRepo->addSlugCondition($orgRepo->builder(), $organisationOption)->get();
                if ($organisations->count() !== 1) {
                    throw new \Exception('non singular result found for this input');
                }
                $organisation = $organisations[0];
            }

            $this->output->info('Exporting instruments for organisation: ' . $organisation->id . ' ' . $organisation->name);

            $service->setItems($organisation->instruments);
        }

        $service->handle();
        $this->output->writeln('finished');
        return 0;
    }
}
