<?php

namespace Tests\Feature\CmsAuth;

use Anhskohbo\NoCaptcha\NoCaptcha;
use App\Models\CmsAdmin;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * The Cms Admin which being tested.
     *
     * @var CmsAdmin
     */
    protected $cmsAdmin;

    /**
     * Current endpoint url which being tested.
     *
     * @var string
     */
    protected $endpoint = 'cms-api/auth/password/email';

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

    /**
     * By pass google recaptcha validation.
     */
    protected function byPassRecaptcha(): void
    {
        $captcha = \Mockery::mock(NoCaptcha::class);
        app()->bind('captcha', function () use ($captcha) {
            return $captcha;
        });

        $captcha->shouldReceive('verifyResponse')
            ->once()
            ->andReturn(true);
    }

    /** @test */
    public function email_validation_works_as_expected()
    {
        $this->byPassRecaptcha();

        $this->postJson($this->endpoint, [
            'email'                => 'not.a.valid.email.address',
            'g-recaptcha-response' => 'bypassed',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    /** @test */
    public function reset_password_failed_using_unregistered_email_address()
    {
        $this->byPassRecaptcha();

        $this->postJson($this->endpoint, [
            'email'                => 'no_such_admin@admin.net',
            'g-recaptcha-response' => 'bypassed',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    /** @test */
    public function admin_can_request_reset_password_using_forgot_password_endpoint()
    {
        $this->byPassRecaptcha();

        $this->postJson($this->endpoint, [
            'email'                => $this->cmsAdmin->email,
            'g-recaptcha-response' => 'bypassed',
        ])
            ->assertStatus(200)
            ->assertJsonFragment(['message' => 'The password reset link has been sent to your email address.']);
    }
}
