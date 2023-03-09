<?php

namespace Tests\Feature\Api\CmsEndpoints;

use App\Models\CmsAdmin;
use App\Models\Setting;
use Faker\Generator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SettingsTest extends TestCase
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
    protected $endpoint = '/cms-api/settings/';

    /**
     * Faker generator instance.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * The model which being tested.
     *
     * @var Setting
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

        Setting::factory()->count(10)->create();
        $this->model = Setting::findOrFail(5);
    }

    /** @test */
    public function index_endpoint_works_as_expected()
    {
        $this->getJson($this->endpoint)
            ->assertStatus(200)
            ->assertJsonFragment([
                'type'  => $this->model->getAttribute('type'),
                'key'   => $this->model->getAttribute('key'),
                'value' => $this->model->getAttribute('value'),
            ]);
    }

    /** @test */
    public function show_endpoint_works_as_expected()
    {
        $this->getJson($this->endpoint.$this->model->getKey())
            ->assertStatus(200)
            ->assertJsonFragment([
                'type'  => $this->model->getAttribute('type'),
                'key'   => $this->model->getAttribute('key'),
                'value' => $this->model->getAttribute('value'),
            ]);
    }

    /** @test */
    public function create_endpoint_works_as_expected()
    {
        // Submitted data
        $data = Setting::factory()->raw();

        // The data which should be shown
        $seenData = $data;

        $this->postJson($this->endpoint, $data)
            ->assertStatus(201)
            ->assertJsonFragment($seenData);
    }

    /** @test */
    public function update_endpoint_works_as_expected()
    {
        // Submitted data
        $data = Setting::factory()->raw();

        // The data which should be shown
        $seenData = $data;

        $this->patchJson($this->endpoint.$this->model->getKey(), $data)
            ->assertStatus(200)
            ->assertJsonFragment($seenData);
    }

    /** @test */
    public function delete_endpoint_works_as_expected()
    {
        $this->deleteJson($this->endpoint.$this->model->getKey())
            ->assertStatus(200)
            ->assertJsonFragment([
                'info' => 'The setting has been deleted.',
            ]);

        $this->assertDatabaseMissing('cms_admins', [
            'type'       => $this->model->getAttribute('type'),
            'key'        => $this->model->getAttribute('key'),
            'value'      => $this->model->getAttribute('value'),
            'deleted_at' => null,
        ]);
    }
}
