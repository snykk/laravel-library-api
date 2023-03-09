<?php

namespace Tests\Unit\Rules;

use App\Models\CmsAdmin;
use App\Rules\CurrentPasswordMatched;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CurrentPasswordMatchTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * The Cms Admin which being tested.
     *
     * @var CmsAdmin
     */
    protected $cmsAdmin;

    /**
     * The validation rule which being tested.
     *
     * @var CurrentPasswordMatched
     */
    protected $rule;

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

        $this->rule = new CurrentPasswordMatched();
    }

    /** @test */
    public function it_wont_pass_if_the_current_user_has_not_logged_in_as_cms_admin()
    {
        $actual = $this->rule->passes('current_password', 'password');

        $this->assertFalse($actual);
    }

    /** @test */
    public function it_wont_pass_if_the_given_password_does_not_match()
    {
        $this->actingAs($this->cmsAdmin, config('api.cms_guard'));
        $actual = $this->rule->passes('current_password', 'wrong-password');

        $this->assertFalse($actual);
    }

    /** @test */
    public function it_will_pass_if_you_give_the_correct_password()
    {
        $this->actingAs($this->cmsAdmin, config('api.cms_guard'));
        $actual = $this->rule->passes('current_password', 'password');

        $this->assertTrue($actual);
    }

    /** @test */
    public function it_returns_error_message_template_as_expected()
    {
        $expected = 'The given :attribute does not match with your current password.';
        $actual = $this->rule->message();

        $this->assertEquals($expected, $actual);
    }
}
