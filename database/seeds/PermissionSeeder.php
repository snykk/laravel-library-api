<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Permission::findOrCreate('access-cms', 'cms-api');

        $this->createResourcePermissionsFor('cms', 'cms_admins', 'cms-api');
        $this->createResourcePermissionsFor('cms', 'roles', 'cms-api');
        $this->createResourcePermissionsFor('cms', 'seo_metas', 'cms-api');
        $this->createResourcePermissionsFor('cms', 'settings', 'cms-api');
    }

    /**
     * Create a set of resource permissions for the given resource string.
     *
     * @param string $prefix
     * @param string $resource
     * @param string $guard
     *
     * @return void
     */
    protected function createResourcePermissionsFor(string $prefix, string $resource, string $guard): void
    {
        Permission::findOrCreate($prefix.'.'.$resource.'.viewAny', $guard);
        Permission::findOrCreate($prefix.'.'.$resource.'.view', $guard);
        Permission::findOrCreate($prefix.'.'.$resource.'.create', $guard);
        Permission::findOrCreate($prefix.'.'.$resource.'.update', $guard);
        Permission::findOrCreate($prefix.'.'.$resource.'.delete', $guard);
        Permission::findOrCreate($prefix.'.'.$resource.'.restore', $guard);
        Permission::findOrCreate($prefix.'.'.$resource.'.forceDelete', $guard);
    }
}
