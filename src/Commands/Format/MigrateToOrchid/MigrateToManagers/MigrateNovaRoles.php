<?php

namespace Vng\EvaCore\Commands\Format\MigrateToOrchid\MigrateToManagers;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\Manager;
use Vng\EvaCore\Repositories\ManagerRepositoryInterface;
use Vng\EvaCore\Repositories\RoleRepositoryInterface;
use Vng\EvaCore\Repositories\UserRepositoryInterface;

class MigrateNovaRoles extends Command
{
    protected $signature = 'format:migrate-nova-roles';
    protected $description = 'Migrate nova roles to core roles';

    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected RoleRepositoryInterface $roleRepository,
        protected ManagerRepositoryInterface $managerRepository,
    )
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->getOutput()->writeln('starting migrating nova roles...');

        $this->migrateToOrganisationRoles();
        $this->migrateRoles();

        $this->getOutput()->writeln('migrating nova roles finished!');
        return 0;
    }

    private function migrateRoles()
    {
        if (!Schema::hasTable('nova_model_has_roles')) {
            $this->output->writeln('nova_model_has_roles table does not exists - skippinig');
            return;
        }

        $records = DB::table('nova_model_has_roles')->get(['model_id', 'role_id']);
        $records->each(function ($record) {
            /** @var IsManagerInterface $user */
            $user = $this->userRepository->find($record->model_id);
            /** @var Manager $manager */
            $manager = $user->manager()->first();

            $role = DB::table('nova_roles')->find($record->role_id);
            $roleName = $role->name;

            // rename some roles
            if ($roleName === 'instrument-manager-association') {
                $roleName = 'instrument-manager-organisation';
            }
            if ($roleName === 'association-user-manager') {
                $roleName = 'user-manager-organisation';
            }

            $coreRole = $this->roleRepository->builder()->where('name', $roleName)->first();
            if (!is_null($coreRole)) {
                $manager->assignRole($coreRole);
            }
        });
    }

    private function migrateToOrganisationRoles()
    {
        if (!Schema::hasTable('nova_roles')) {
            $this->output->writeln('nova_roles table does not exists - skippinig');
            return;
        }

        $instrumentManagerRoles = DB::table('nova_roles')
            ->where('name', 'instrument-manager-association')
            ->orWhere('name', 'instrument-manager-national')
            ->get();
        
        $organisationInstrumentManagerRole = DB::table('nova_roles')->where('name', 'instrument-manager-association')->first();
        DB::table('nova_model_has_roles')
            ->whereIn('role_id', $instrumentManagerRoles->pluck('id'))
            ->update(['role_id' => $organisationInstrumentManagerRole->id]);


        $userManagerRoles = DB::table('nova_roles')
            ->where('name', 'association-user-manager')
            ->orWhere('name', 'national-user-manager')
            ->get();
        $organisationUserManagerRole = DB::table('nova_roles')->where('name', 'association-user-manager')->first();
        DB::table('nova_model_has_roles')
            ->whereIn('role_id', $userManagerRoles->pluck('id'))
            ->update(['role_id' => $organisationUserManagerRole->id]);
    }
}
