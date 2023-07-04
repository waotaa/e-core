<?php

namespace Vng\EvaCore\Commands\Format;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Vng\EvaCore\Models\Implementation;
use Illuminate\Console\Command;
use Vng\EvaCore\Repositories\ImplementationRepositoryInterface;
use Vng\EvaCore\Repositories\InstrumentRepositoryInterface;

class MigrateImplementations extends Command
{
    protected $signature = 'format:implementation-migrate';
    protected $description = 'Migrate implementation options before removing custom options';

    public function __construct(
        public InstrumentRepositoryInterface $instrumentRepository,
        public ImplementationRepositoryInterface $implementationRepository
    )
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->getOutput()->writeln('starting migrating implementations...');

        $this->call('migrate', ['--force' => true]);
        /**
         * Makes all current items custom
         * Creates new items for the new data set (non custom)
         * Once this migration has ran we can delete all custom items
         */
        $this->call('setup:seed-characteristics');

        $this->migrateImplementations();

        $this->getOutput()->writeln('migrating implementations finished!');
        return 0;
    }

    private function migrateImplementations()
    {
        $this->migrateByName('Scholing', '(Bij)Scholing'); // 19
        $this->migrateByName('Cursus', '(Bij)Scholing'); // 35
        $this->migrateByName('E-Learning', '(Bij)Scholing'); // 40
        $this->migrateByName('Workshop', '(Bij)Scholing'); // 42
        $this->migrateByName('Training', '(Bij)Scholing'); // 43

        $this->migrateByName('Opleidingstrajecten (perceel 5 Open House)', 'Opleiding');  // 22
//        $this->migrateByName('Opleiding', 'Opleiding');  // 36 Seeder fixes this one

        $this->migrateByName('Werk leer traject', 'Leerwerktraject'); // 5
        $this->migrateByName('Werkleertraject', 'Leerwerktraject'); // 10
//        $this->migrateByName('leerwerktraject', 'Leerwerktraject'); // 13 Seeder fixes this one
        $this->migrateByName('Leerlijn', 'Leerwerktraject'); // 14
        $this->migrateByName('Leerwerkplek', 'Leerwerktraject'); // 31

        $this->migrateByName('Dagbesteding', '(Vrijwilligers)werk'); // 3
        $this->migrateByName('Leren/Werken', '(Vrijwilligers)werk'); // 7
        $this->migrateByName('Participatietraject', '(Vrijwilligers)werk'); // 15
        $this->migrateByName('Werkervaringsplaats anderstaligen (perceel 6 Open House)', '(Vrijwilligers)werk'); // 21
        $this->migrateByName('Werkervaringsplaats (perceel 1 Open House)', '(Vrijwilligers)werk'); // 23
        $this->migrateByName('Vrijwilligerswerk', '(Vrijwilligers)werk'); // 27
        $this->migrateByName('Werk/Stage', '(Vrijwilligers)werk'); // 37

//        $this->migrateByName('Vragenlijst', 'Vragenlijst'); // 41 Seeder fixes this one

        $this->migrateByName('Oriëntatie, onderzoek en diagnose', 'Onderzoek'); // 12
//        $this->migrateByName('Onderzoek', 'Onderzoek'); // 38 Seeder fixes this one

        $this->migrateByName('begeleiding naar en tijdens werk', 'Coaching'); // 11
        $this->migrateByName('Persoonlijke ontwikkeling/coaching met bewegen (perceel 4 Open House)', 'Coaching'); // 24
        $this->migrateByName('Persoonlijke ontwikkeling/coaching (perceel 3 Open House)', 'Coaching'); // 25
        $this->migrateByName('Coaching alleenstaande ouders (perceel 2 Open House)', 'Coaching'); // 26
//        $this->migrateByName('Coaching', 'Coaching'); // 39 Seeder fixes this one

        $this->migrateByName('Opleidingstrajecten (perceel 5 Open House)', 'Subsidie/Voucher'); // 22
        $this->migrateByName('Vouchers', 'Subsidie/Voucher'); // 28

        $this->migrateByName('Tolken / vertalen', 'Tolken/vertalen'); // 17

        $this->migrateByName('Sociale kaart', 'Verwijzing'); // 2

//        $this->migrateByName('', 'Materiele ondersteuning');
    }

    private function migrateByName($name, $targetName)
    {
        // find current
        try {
            $currentImplementation = Implementation::query()->where([
                'name' => $name,
                'custom' => true,
            ])->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new \Exception('Current custom implementation with name ' . $name . ' not found');
        }

        // find target
        try {
            $targetImplementation = Implementation::query()->where([
                'name' => $targetName,
                'custom' => false,
            ])->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new \Exception('Target non-custom implementation with name ' . $targetName . ' not found');
        }

        $this->output->writeln('migrating from ' . $currentImplementation->id . ' to ' . $targetImplementation->id);

        // associate connected instruments to target
        $this->instrumentRepository->builderWithTrashed()->where([
            'implementation_id' => $currentImplementation->id
        ])->update([
            'implementation_id' => $targetImplementation->id
        ]);
    }
}
