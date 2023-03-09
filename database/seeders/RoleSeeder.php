<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->createSuperAdministratorRole();
    }

    /**
     * Create a new role for super administrator.
     *
     * @return void
     */
    public function createSuperAdministratorRole(): void
    {
        $role = Role::findOrCreate('super-administrator', config('api.cms_guard'));

        if ($role instanceof Role) {
            $role->givePermissionTo(Permission::where('guard_name', config('api.cms_guard'))->get());
        }
    }
}
