<?php

namespace Vng\EvaCore\Commands\Format;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Vng\EvaCore\Providers\MorphMapServiceProvider;

class ApplyMorphMap extends Command
{
    protected $signature = 'format:apply-morph-map';
    protected $description = 'Updates morph classes with the correct morph value';

    public function handle(): int
    {
        $this->getOutput()->writeln('starting applying morph map...');

        $this->applyMorphMap($this->getMorphMap());
        $this->applyMorphMap($this->getMorphMapAppVariant());

        $this->getOutput()->writeln('applying morph map finished!');
        return 0;
    }

    public function applyMorphMap($map)
    {
        foreach ($map as $label => $mappedClass) {
            $this->updateAddressables($label, $mappedClass);
            $this->updateAssociateables($label, $mappedClass);
            $this->updateContactables($label, $mappedClass);
            $this->updateEnvironments($label, $mappedClass);
            $this->updateInstruments($label, $mappedClass);
            $this->updateProviders($label, $mappedClass);
            $this->updateSyncAttempts($label, $mappedClass);
            $this->updateManagerRoles($label, $mappedClass);
        }
    }

    public function updateAddressables($label, $mappedClass)
    {
        $this->updateField('addressables', 'addressable_type', $label, $mappedClass);
    }

    public function updateAssociateables($label, $mappedClass)
    {
        $this->updateField('associateables', 'associateable_type', $label, $mappedClass);
    }

    public function updateContactables($label, $mappedClass)
    {
        $this->updateField('contactables', 'contactable_type', $label, $mappedClass);
    }

    public function updateEnvironments($label, $mappedClass)
    {
        $this->updateField('environments', 'featured_association_type', $label, $mappedClass);
    }

    public function updateInstruments($label, $mappedClass)
    {
        $this->updateField('instruments', 'owner_type', $label, $mappedClass);
    }

    public function updateProviders($label, $mappedClass)
    {
        $this->updateField('providers', 'owner_type', $label, $mappedClass);
    }

    public function updateSyncAttempts($label, $mappedClass)
    {
        $fields = [
            'resource_type',
            'origin_type'
        ];
        foreach ($fields as $field) {
            $this->updateField('sync_attempts', $field, $label, $mappedClass);
        }
    }

    public function updateManagerRoles($label, $mappedClass)
    {
        $fields = [
            'model_type',
        ];
        foreach ($fields as $field) {
            $this->updateField('core_manager_has_roles', $field, $label, $mappedClass);
        }
    }

    public function updateField(
        $table,
        $field,
        $label,
        $mappedClass
    ) {
        DB::table($table)
            ->where($field, $mappedClass)
            ->update([
                $field => $label
            ]);
    }

    private function getMorphMap()
    {
        return MorphMapServiceProvider::MORPH_MAP;
    }

    private function getMorphMapAppVariant()
    {
        $map = MorphMapServiceProvider::MORPH_MAP;
        return array_map(function ($entry){
            return str_replace('Vng\\EvaCore', 'App', $entry);
        }, $map);
    }
}
