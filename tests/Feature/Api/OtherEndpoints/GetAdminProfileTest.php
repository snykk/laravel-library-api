<?php

namespace Tests\Feature\Api\OtherEndpoints;

use App\Models\CmsAdmin;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class GetAdminProfileTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Currently logged in Cms Admin.
     *
     * @var CmsAdmin
     */
    protected $cmsAdmin;

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
    }

    /** @test */
    public function it_can_retrieve_current_admin_information()
    {
        $this->actingAs($this->cmsAdmin, config('api.cms_guard'));

        $this->getJson('/cms-api/current-admin/profile')
            ->assertStatus(200)
            ->assertJsonFragment([
                'name'                   => $this->cmsAdmin->name,
                'email'                  => $this->cmsAdmin->email,
                'small_profile_picture'  => '',
                'medium_profile_picture' => '',
            ]);
    }

    /** @test */
    public function unauthorized_user_can_not_retrieve_cms_admin_information()
    {
        $this->getJson('/cms-api/current-admin/profile')
            ->assertStatus(401);
    }
}
