<?php

namespace Tests\Feature\CmsAuth;

use Anhskohbo\NoCaptcha\NoCaptcha;
use App\Models\CmsAdmin;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Str;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Cms Auth - login url.
     *
     * @var string
     */
    protected $loginUrl = '/cms-api/auth/login';

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
        $this->postJson($this->loginUrl, [
            'email'                => 'a@a.a',
            'password'             => Str::random(8),
            'g-recaptcha-response' => 'bypassed',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('email')
            ->assertJsonFragment(['message' => 'The given data was invalid.'])
            ->assertJsonFragment(['email' => ['The email must be at least 11 characters.']]);
    }

    /** @test */
    public function password_validation_works_as_expected()
    {
        $this->postJson($this->loginUrl, [
            'email'                => 'admin@admin.net',
            'password'             => 'abcd',
            'g-recaptcha-response' => 'bypassed',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('password')
            ->assertJsonFragment(['message' => 'The given data was invalid.'])
            ->assertJsonFragment(['password' => ['The password must be at least 6 characters.']]);
    }

    /** @test */
    public function login_failed_using_invalid_credential()
    {
        $this->byPassRecaptcha();

        $this->postJson($this->loginUrl, [
            'email'                => 'admin@admin.net',
            'password'             => 'password',
            'g-recaptcha-response' => 'bypassed',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('email')
            ->assertJsonFragment(['message' => 'The given data was invalid.'])
            ->assertJsonFragment(['email' => ['These credentials do not match our records.']]);
    }

    /** @test */
    public function login_failed_using_a_valid_credential_but_has_no_permission_to_access_cms()
    {
        $this->byPassRecaptcha();

        $admin = CmsAdmin::factory()->create();

        $this->postJson($this->loginUrl, [
            'email'                => $admin->email,
            'password'             => 'password',
            'g-recaptcha-response' => 'bypassed',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('email')
            ->assertJsonFragment(['message' => 'The given data was invalid.'])
            ->assertJsonFragment(['email' => ['These credentials do not match our records.']]);

        $this->assertNull(\Auth::guard(config('api.cms_guard'))->user());
    }

    /** @test */
    public function login_success_using_a_valid_credential()
    {
        $this->byPassRecaptcha();

        $admin = CmsAdmin::factory()->create()->assignRole('super-administrator');

        $this->postJson($this->loginUrl, [
            'email'                => $admin->email,
            'password'             => 'password',
            'g-recaptcha-response' => 'bypassed',
        ])
            ->assertStatus(204);

        $actualAdmin = \Auth::guard(config('api.cms_guard'))->user();
        $this->assertInstanceOf(CmsAdmin::class, $actualAdmin);
        $this->assertEquals($admin->email, $actualAdmin->email);
    }
}
