<?php

namespace Tests\Feature\Api\CmsEndpoints;

use App\Models\CmsAdmin;
use App\Models\User;
use Faker\Generator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UsersTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Currently logged in CMS Admin.
     *
     * @var CmsAdmin
     */
    protected $cmsAdmin;

    /**
     * Current endpoint url which being tested.
     *
     * @var string
     */
    protected $endpoint = '/cms-api/users/';

    /**
     * Faker generator instance.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * The model which being tested.
     *
     * @var User
     */
    protected $model;

    /**
     * Setup the test environment.
     *
     * return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->seed(['PermissionSeeder', 'RoleSeeder']);

        $this->faker = new Generator();
        $this->cmsAdmin = CmsAdmin::factory()->create()->assignRole('super-administrator');

        $this->actingAs($this->cmsAdmin, config('api.cms_guard'));

        $this->model = User::factory()->create();
    }

    /** @test */
    public function index_endpoint_works_as_expected()
    {
        $this->getJson($this->endpoint)
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => $this->model->getAttribute('name'),
                'email' => $this->model->getAttribute('email'),
                'email_verified_at' => $this->model->getAttribute('email_verified_at'),
                'password' => $this->model->getAttribute('password'),
                'reviews' => $this->model->getAttribute('reviews'),
                'remember_token' => $this->model->getAttribute('remember_token'),
            ]);
    }

    /** @test */
    public function show_endpoint_works_as_expected()
    {
        $this->getJson($this->endpoint.$this->model->getKey())
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => $this->model->getAttribute('name'),
                'email' => $this->model->getAttribute('email'),
                'email_verified_at' => $this->model->getAttribute('email_verified_at'),
                'password' => $this->model->getAttribute('password'),
                'reviews' => $this->model->getAttribute('reviews'),
                'remember_token' => $this->model->getAttribute('remember_token'),
            ]);
    }

    /** @test */
    public function create_endpoint_works_as_expected()
    {
        // Submitted data
        $data = User::factory()->raw();

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
        $data = User::factory()->raw();

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
                'info' => 'The user has been deleted.',
            ]);

        $this->assertDatabaseHas('users', [
            'name' => $this->model->getAttribute('name'),
            'email' => $this->model->getAttribute('email'),
            'email_verified_at' => $this->model->getAttribute('email_verified_at'),
            'password' => $this->model->getAttribute('password'),
            'reviews' => $this->model->getAttribute('reviews'),
            'remember_token' => $this->model->getAttribute('remember_token'),
        ]);

        $this->assertDatabaseMissing('users', [
            'name' => $this->model->getAttribute('name'),
            'email' => $this->model->getAttribute('email'),
            'email_verified_at' => $this->model->getAttribute('email_verified_at'),
            'password' => $this->model->getAttribute('password'),
            'reviews' => $this->model->getAttribute('reviews'),
            'remember_token' => $this->model->getAttribute('remember_token'),
            'deleted_at' => null,
        ]);
    }
}
