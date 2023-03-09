<?php

namespace Tests\Feature\Api\OtherEndpoints;

use App\Models\CmsAdmin;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Tests\TestCase;

class UpdateAdminProfileTest extends TestCase
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
    protected $endpoint = '/cms-api/current-admin/profile';

    /**
     * Setup the test environment.
     *
     * return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(['PermissionSeeder', 'RoleSeeder']);

        $this->cmsAdmin = CmsAdmin::factory()->create()->assignRole('super-administrator');

        $this->actingAs($this->cmsAdmin, config('api.cms_guard'));
    }

    /** @test */
    public function name_validation_works_as_expected()
    {
        $this->putJson($this->endpoint, [
            'name'                  => '',
            'email'                 => $this->cmsAdmin->email,
            'current_password'      => '',
            'password'              => '',
            'password_confirmation' => '',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('name');

        $this->putJson($this->endpoint, [
            'name'                  => 'I',
            'email'                 => $this->cmsAdmin->email,
            'current_password'      => '',
            'password'              => '',
            'password_confirmation' => '',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('name');
    }

    /** @test */
    public function all_of_the_three_password_fields_need_to_be_filled()
    {
        $this->putJson($this->endpoint, [
            'name'                  => 'Admin',
            'email'                 => $this->cmsAdmin->email,
            'current_password'      => 'password',
            'password'              => '',
            'password_confirmation' => '',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('password')
            ->assertJsonValidationErrors('password_confirmation');

        $this->putJson($this->endpoint, [
            'name'                  => 'Admin',
            'email'                 => $this->cmsAdmin->email,
            'current_password'      => '',
            'password'              => 'password',
            'password_confirmation' => '',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('current_password')
            ->assertJsonValidationErrors('password_confirmation');

        $this->putJson($this->endpoint, [
            'name'                  => 'Admin',
            'email'                 => $this->cmsAdmin->email,
            'current_password'      => '',
            'password'              => '',
            'password_confirmation' => 'password',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('current_password')
            ->assertJsonValidationErrors('password');
    }

    /** @test */
    public function current_password_validation_works_as_expected()
    {
        $this->putJson($this->endpoint, [
            'name'                  => 'Admin',
            'email'                 => $this->cmsAdmin->email,
            'current_password'      => 'secretgarden',
            'password'              => 'password',
            'password_confirmation' => 'password',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('current_password');
    }

    /** @test */
    public function password_confirmation_validation_works_as_expected()
    {
        $this->putJson($this->endpoint, [
            'name'                  => 'Admin',
            'email'                 => $this->cmsAdmin->email,
            'current_password'      => 'password',
            'password'              => 'password2',
            'password_confirmation' => 'password',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('password');
    }

    /** @test */
    public function new_password_should_be_at_least_6_characters()
    {
        $this->putJson($this->endpoint, [
            'name'                  => 'Admin',
            'email'                 => $this->cmsAdmin->email,
            'current_password'      => 'password',
            'password'              => 'test',
            'password_confirmation' => 'test',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('password');
    }

    /** @test */
    public function admin_can_update_name_without_updating_password()
    {
        $this->putJson($this->endpoint, [
            'name'                  => 'Admin',
            'email'                 => $this->cmsAdmin->email,
            'current_password'      => '',
            'password'              => '',
            'password_confirmation' => '',
        ])
            ->assertStatus(200)
            ->assertJsonFragment(['info' => 'Your account information has been updated.']);
    }

    /** @test */
    public function admin_can_update_name_and_password()
    {
        $this->putJson($this->endpoint, [
            'name'                  => 'Admin',
            'email'                 => $this->cmsAdmin->email,
            'current_password'      => 'password',
            'password'              => 'secretpassword',
            'password_confirmation' => 'secretpassword',
        ])
            ->assertStatus(200)
            ->assertJsonFragment(['info' => 'Your account information has been updated.']);
    }

    /** @test */
    public function admin_can_update_name_password_and_profile_picture()
    {
        \Storage::fake('public');

        $fakeImage = UploadedFile::fake()
            ->image('user.jpg', 1280, 768)
            ->size(360);

        $this->putJson($this->endpoint, [
            'name'                  => 'Admin',
            'email'                 => $this->cmsAdmin->email,
            'current_password'      => 'password',
            'password'              => 'secretpassword',
            'password_confirmation' => 'secretpassword',
            'profile_picture'       => $fakeImage,
        ])
            ->assertStatus(200)
            ->assertJsonFragment(['info' => 'Your account information has been updated.']);

        $this->cmsAdmin->refresh();
        $media = $this->cmsAdmin->getFirstMedia('profile-picture');

        $this->assertInstanceOf(Media::class, $media);

        $path = str_replace(dirname(\Storage::disk('public')->path('test')), '', $media->getPath());
        \Storage::disk('public')->assertExists($path);
    }
}
