<?php

namespace Vng\EvaCore\Commands\Format;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Vng\EvaCore\Models\TargetGroup;
use Vng\EvaCore\Repositories\InstrumentRepositoryInterface;
use Vng\EvaCore\Repositories\TargetGroupRepositoryInterface;

class MigrateTargetGroups extends Command
{
    protected $signature = 'format:target-group-migrate {--d|delete}';
    protected $description = 'Migrate target group options before removing custom options';

    public function __construct(
        public InstrumentRepositoryInterface $instrumentRepository,
        public TargetGroupRepositoryInterface $targetGroupRepository
    )
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->getOutput()->writeln('starting migrating target groups...');

        $this->call('migrate', ['--force' => true]);
        /**
         * Makes all current items custom
         * Creates new items for the new data set (non custom)
         * Once this migration has ran we can delete all custom items
         */
        $this->call('setup:seed-characteristics');

        $this->migrateTargetGroups();

        $this->getOutput()->writeln('migrating target groups finished!');
        return 0;
    }

    private function migrateTargetGroups()
    {
        $this->migrateItem('Ex-Ondernemer', 'ED07'); // 02
        $this->migrateItem('Laaggeletterdheid', 'ED09'); // 05
        $this->migrateItem('IBA', 'ED12'); // 06
        $this->migrateItem('Doelgroepenregister', 'ED12'); // 09
        $this->migrateItem('Arbeidsbeperking', 'ED04'); // 10
        $this->migrateItem('ZZP\'er', 'ED10'); // 11
        $this->migrateItem('Met werkloosheid bedreigd', 'ED11'); // 14
        $this->migrateItem('Banenafspraak', 'ED12'); // 15
        $this->migrateItem('Doelgroep-professional', 'ED14'); // 17
        $this->migrateItem('Beschut', 'ED04'); // 18
        $this->migrateItem('Taalbehoeftigen', 'ED09'); // 19
        $this->migrateItem('WSW', 'ED03'); // 25
        $this->migrateItem('Banenafspraak (doelgroepregister)', 'ED12'); // 27
        $this->migrateItem('Met werkloosheid bedreigd', 'ED11'); // 37
        $this->migrateItem('ZZP-er', 'ED10'); // 38
        $this->migrateItem('NUGGERS', 'ED13'); // 39
        $this->migrateItem('SW-medewerkers', 'ED14'); // 42
        $this->migrateItem('Nug-gers', 'ED13'); // 44
        $this->migrateItem('Pro-VSO jongeren', 'ED01'); // 50
        $this->migrateItem('Inburgering', 'ED06'); // 51
        $this->migrateItem('Niet-uitkeringsgerechtigde', 'ED13'); // 55
        $this->migrateItem('NUG', 'ED13'); // 57
        $this->migrateItem('Laaggeletterden en analfabeten', 'ED09'); // 58
        $this->migrateItem('50+', 'ED02'); // 60

    }

    private function migrateItem($name, $targetCode)
    {
        /** @var TargetGroup $currentTargetGroup */
        $currentTargetGroup = TargetGroup::query()->where([
            'description' => $name,
            'custom' => true,
        ])->first();

        if (is_null($currentTargetGroup)) {
            $this->output->warning("Current custom target group with name " . $name . " not found. \n Skipping..");
            return;
        }

        try {
            /** @var TargetGroup $targetTargetGroup */
            $targetTargetGroup = TargetGroup::query()->where([
                'code' => $targetCode,
                'custom' => false,
            ])->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new \Exception('Target target group with code ' . $targetCode . ' not found');
        }

        $this->output->writeln('migrating from ' . $currentTargetGroup->id . ' to ' . $targetTargetGroup->id);

        // associate connected instruments to target
        $instruments = $this->instrumentRepository
            ->builderWithTrashed()
            ->whereHas('targetGroups', function (Builder $q) use ($currentTargetGroup) {
                $q->where([
                    'target_groups.id' => $currentTargetGroup->id,
                ]);
            })
            ->get();

        $this->output->writeln($instruments->count() . ' connected instruments');

        $this->targetGroupRepository->attachInstruments($targetTargetGroup, $instruments->pluck('id')->toArray());
        $this->targetGroupRepository->detachInstruments($currentTargetGroup, $instruments->pluck('id')->toArray());

        if ($this->option('delete')) {
            $currentTargetGroup->delete();
        }
    }
}
