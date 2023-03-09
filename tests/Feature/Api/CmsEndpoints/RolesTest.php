<?php

namespace Tests\Feature\Api\CmsEndpoints;

use App\Models\CmsAdmin;
use Faker\Generator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RolesTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Currently logged in Cms Admin.
     *
     * @var CmsAdmin
     */
    protected $cmsAdmin;

    /**
     * Current endpoint url which being tested.
     *
     * @var string
     */
    protected $endpoint = '/cms-api/roles/';

    /**
     * Faker generator instance.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * The model which being tested.
     *
     * @var Role
     */
    protected $model;

    /**
     * Setup the test environment.
     *
     * return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(['PermissionSeeder', 'RoleSeeder']);

        $this->faker = new Generator();
        $this->cmsAdmin = CmsAdmin::factory()->create()->assignRole('super-administrator');

        $this->actingAs($this->cmsAdmin, config('api.cms_guard'));

        Role::findOrCreate('role-1', config('api.cms_guard'));
        Role::findOrCreate('role-2', config('api.cms_guard'));
        Role::findOrCreate('role-3', config('api.cms_guard'));
        Role::findOrCreate('role-4', config('api.cms_guard'));
        Role::findOrCreate('role-5', config('api.cms_guard'));

        $this->model = Role::findOrFail(3);
    }

    /** @test */
    public function index_endpoint_works_as_expected()
    {
        $this->getJson($this->endpoint)
            ->assertStatus(200)
            ->assertJsonFragment([
                'name'       => $this->model->getAttribute('name'),
                'guard_name' => $this->model->getAttribute('guard_name'),
            ]);
    }

    /** @test */
    public function show_endpoint_works_as_expected()
    {
        $this->getJson($this->endpoint.$this->model->getKey())
            ->assertStatus(200)
            ->assertJsonFragment([
                'name'       => $this->model->getAttribute('name'),
                'guard_name' => $this->model->getAttribute('guard_name'),
            ]);
    }

    /** @test */
    public function create_endpoint_works_with_passing_some_permissions()
    {
        // Submitted data
        $permissions = Permission::orderBy('id', 'desc')->take(5)->get();
        $data = [
            'name'       => 'dummy-role',
            'guard_name' => config('api.cms_guard'),
        ];
        $data['permission_list'] = implode(',', $permissions->pluck('name')->toArray());

        // The data which should be shown
        $seenData = $data;
        unset($seenData['permission_list']);

        $this->postJson($this->endpoint, $data)
            ->assertStatus(201)
            ->assertJsonFragment($seenData);

        $role = Role::orderBy('id', 'desc')->first();

        foreach ($permissions as $permission) {
            $this->assertTrue($role->hasPermissionTo($permission->name));
        }
    }

    /** @test */
    public function update_endpoint_works_as_expected()
    {
        // Submitted data
        $permissions = Permission::orderBy('id', 'asc')->take(5)->get();
        $data = [
            'name'       => 'dummy-role',
            'guard_name' => config('api.cms_guard'),
        ];
        $data['permission_list'] = implode(',', $permissions->pluck('name')->toArray());

        // The data which should be shown
        $seenData = $data;
        unset($seenData['permission_list']);

        $this->patchJson($this->endpoint.$this->model->getKey(), $data)
            ->assertStatus(200)
            ->assertJsonFragment($seenData);

        foreach ($permissions as $permission) {
            $this->assertTrue($this->model->hasPermissionTo($permission->name));
        }
    }

    /** @test */
    public function delete_endpoint_works_as_expected()
    {
        $this->deleteJson($this->endpoint.$this->model->getKey())
            ->assertStatus(200)
            ->assertJsonFragment([
                'info' => 'The role has been deleted.',
            ]);

        $this->assertDatabaseMissing('cms_admins', [
            'name'       => $this->model->getAttribute('name'),
            'guard_name' => $this->model->getAttribute('guard_name'),
            'deleted_at' => null,
        ]);
    }
}
