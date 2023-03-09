<?php

namespace Tests\Feature\Console;

use App\Models\CmsAdmin;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class CreateNewAdminTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Setup the test environment.
     *
     * return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(['PermissionSeeder', 'RoleSeeder']);
    }

    /** @test */
    public function it_can_create_new_admin()
    {
        $newAdmin = [
            'name'     => 'Richan Fongdasen',
            'email'    => 'richan.fongdasen@gmail.com',
            'password' => 'verystrongpassword',
        ];

        \Artisan::call('cms:create-admin', $newAdmin);
        unset($newAdmin['password']);

        $this->assertDatabaseHas('cms_admins', $newAdmin);

        $admin = CmsAdmin::where('email', $newAdmin['email'])->firstOrFail();

        $this->assertTrue(\Hash::check('verystrongpassword', $admin->password));
        $this->assertTrue($admin->hasRole('super-administrator'));

        $permissions = Permission::where('guard_name', config('cms.guard'))->get();
        foreach ($permissions as $permission) {
            $this->assertTrue($admin->can($permission->name));
        }
    }
}
