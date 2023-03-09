<?php

namespace Tests\Feature\Api\CmsEndpoints;

use App\Models\CmsAdmin;
use Faker\Generator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CmsAdminsTest extends TestCase
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
    protected $endpoint = '/cms-api/cms_admins/';

    /**
     * Faker generator instance.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * The model which being tested.
     *
     * @var CmsAdmin
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

        CmsAdmin::factory()->count(10)->create();
        $this->model = CmsAdmin::findOrFail(5);

        Role::findOrCreate('role-1', config('api.cms_guard'));
        Role::findOrCreate('role-2', config('api.cms_guard'));
        Role::findOrCreate('role-3', config('api.cms_guard'));
        Role::findOrCreate('role-4', config('api.cms_guard'));
        Role::findOrCreate('role-5', config('api.cms_guard'));
    }

    /** @test */
    public function api_access_rejected_without_json_support()
    {
        $this->get($this->endpoint)
            ->assertStatus(405)
            ->assertJsonFragment(['message' => 'Backend accept only json communication.']);
    }

    /** @test */
    public function index_endpoint_works_as_expected()
    {
        $this->getJson($this->endpoint)
            ->assertStatus(200)
            ->assertJsonFragment([
                'name'  => $this->model->getAttribute('name'),
                'email' => $this->model->getAttribute('email'),
            ]);
    }

    /** @test */
    public function show_endpoint_works_as_expected()
    {
        $this->getJson($this->endpoint.$this->model->getKey())
            ->assertStatus(200)
            ->assertJsonFragment([
                'name'  => $this->model->getAttribute('name'),
                'email' => $this->model->getAttribute('email'),
            ]);
    }

    /** @test */
    public function create_endpoint_works_as_expected_with_uploaded_image()
    {
        \Storage::fake('public');

        $fakeImage = UploadedFile::fake()
            ->image('user.jpg', 1280, 768)
            ->size(360);

        // Submitted data
        $roles = Role::orderBy('id', 'desc')->take(3)->pluck('name')->toArray();
        $data = CmsAdmin::factory()->raw([
            'password'              => 'V3ry.Str0ng.P@ssw0rd!!',
            'password_confirmation' => 'V3ry.Str0ng.P@ssw0rd!!',
            'profile_picture'       => $fakeImage,
            'role_names'            => implode(',', $roles),
        ]);
        unset($data['remember_token']);

        // The data which should be shown
        $seenData = $data;
        unset(
            $seenData['password'],
            $seenData['password_confirmation'],
            $seenData['profile_picture'],
            $seenData['role_names']
        );

        $this->postJson($this->endpoint, $data)
            ->assertStatus(201)
            ->assertJsonFragment($seenData);

        $admin = CmsAdmin::orderBy('id', 'desc')->first();
        $media = $admin->getFirstMedia('profile-picture');

        $this->assertInstanceOf(Media::class, $media);

        $path = str_replace(dirname(\Storage::disk('public')->path('test')), '', $media->getPath());
        \Storage::disk('public')->assertExists($path);

        $this->assertTrue($admin->hasAllRoles($roles));
    }

    /** @test */
    public function create_endpoint_works_as_expected_without_uploaded_image()
    {
        // Submitted data
        $roles = Role::orderBy('id', 'desc')->take(3)->pluck('name')->toArray();
        $data = CmsAdmin::factory()->raw([
            'password'              => 'V3ry.Str0ng.P@ssw0rd!!',
            'password_confirmation' => 'V3ry.Str0ng.P@ssw0rd!!',
            'role_names'            => implode(',', $roles),
        ]);
        unset($data['remember_token']);

        // The data which should be shown
        $seenData = $data;
        unset(
            $seenData['password'],
            $seenData['password_confirmation'],
            $seenData['role_names']
        );

        $this->postJson($this->endpoint, $data)
            ->assertStatus(201)
            ->assertJsonFragment($seenData);

        $admin = CmsAdmin::orderBy('id', 'desc')->first();
        $this->assertTrue($admin->hasAllRoles($roles));
    }

    /** @test */
    public function update_endpoint_works_as_expected_with_uploaded_image()
    {
        \Storage::fake('public');

        $fakeImage = UploadedFile::fake()
            ->image('user.jpg', 1280, 768)
            ->size(360);

        // Submitted data
        $roles = Role::orderBy('id', 'asc')->take(3)->pluck('name')->toArray();
        $data = CmsAdmin::factory()->raw([
            'password'              => 'V3ry.Str0ng.P@ssw0rd!!',
            'password_confirmation' => 'V3ry.Str0ng.P@ssw0rd!!',
            'role_names'            => implode(',', $roles),
        ]);
        $data['profile_picture'] = $fakeImage;
        unset($data['remember_token']);

        // The data which should be shown
        $seenData = $data;
        unset(
            $seenData['password'],
            $seenData['password_confirmation'],
            $seenData['profile_picture'],
            $seenData['role_names']
        );

        $this->patchJson($this->endpoint.$this->model->getKey(), $data)
            ->assertStatus(200)
            ->assertJsonFragment($seenData);

        $this->model->refresh();
        $media = $this->model->getFirstMedia('profile-picture');

        $this->assertInstanceOf(Media::class, $media);

        $path = str_replace(dirname(\Storage::disk('public')->path('test')), '', $media->getPath());
        \Storage::disk('public')->assertExists($path);

        $this->assertTrue($this->model->hasAllRoles($roles));
    }

    /** @test */
    public function update_endpoint_works_as_expected_without_uploaded_image()
    {
        // Submitted data
        $roles = Role::orderBy('id', 'asc')->take(3)->pluck('name')->toArray();
        $data = CmsAdmin::factory()->raw([
            'password'              => 'V3ry.Str0ng.P@ssw0rd!!',
            'password_confirmation' => 'V3ry.Str0ng.P@ssw0rd!!',
            'role_names'            => implode(',', $roles),
        ]);
        unset($data['remember_token']);

        // The data which should be shown
        $seenData = $data;
        unset(
            $seenData['password'],
            $seenData['password_confirmation'],
            $seenData['role_names']
        );

        $this->patchJson($this->endpoint.$this->model->getKey(), $data)
            ->assertStatus(200)
            ->assertJsonFragment($seenData);

        $this->model->refresh();
        $this->assertTrue($this->model->hasAllRoles($roles));
    }

    /** @test */
    public function delete_endpoint_works_as_expected()
    {
        $this->deleteJson($this->endpoint.$this->model->getKey())
            ->assertStatus(200)
            ->assertJsonFragment([
                'info' => 'The cms admin has been deleted.',
            ]);

        $this->assertDatabaseMissing('cms_admins', [
            'name'       => $this->model->getAttribute('name'),
            'email'      => $this->model->getAttribute('email'),
            'deleted_at' => null,
        ]);
    }
}
