<?php

namespace Tests\Feature\CmsAuth;

use App\Models\CmsAdmin;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LogoutTest extends TestCase
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

        $this->actingAs($this->cmsAdmin, config('api.cms_guard'));
    }

    /** @test */
    public function it_can_log_out_the_cms_admin()
    {
        $this->postJson('/cms-api/auth/logout')
            ->assertStatus(204);

        $this->assertNull(\Auth::guard(config('api.cms_guard'))->user());
    }
}
