<?php

namespace Tests\Feature\CmsAuth;

use Anhskohbo\NoCaptcha\NoCaptcha;
use App\Models\CmsAdmin;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
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
    protected $endpoint = 'cms-api/auth/password/reset';

    /**
     * The password reset token.
     *
     * @var string
     */
    protected $token;

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
        $this->token = Password::broker('cms_admins')->getRepository()->create($this->cmsAdmin);

        \DB::table('cms_password_resets')->insert([
            'email'      => $this->cmsAdmin->email,
            'token'      => $this->token,
            'created_at' => Carbon::now()->subMinutes(10),
        ]);
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
    public function email_validation_works_well()
    {
        $password = 'V3ry.Strong-P@ssw0rd!!';

        $this->postJson($this->endpoint, [
            'token'                 => $this->token,
            'email'                 => 'aa@bb.cc',
            'password'              => $password,
            'password_confirmation' => $password,
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    /** @test */
    public function password_validation_works_well()
    {
        $password = 'password';

        $this->postJson($this->endpoint, [
            'token'                 => $this->token,
            'email'                 => $this->cmsAdmin->email,
            'password'              => $password,
            'password_confirmation' => $password,
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('password');
    }

    /** @test */
    public function reset_password_failed_with_invalid_token()
    {
        $this->byPassRecaptcha();

        $password = 'V3ry.Strong-P@ssw0rd!!';

        $this->postJson($this->endpoint, [
            'token'                 => Str::random(14),
            'email'                 => $this->cmsAdmin->email,
            'password'              => $password,
            'password_confirmation' => $password,
            'g-recaptcha-response'  => 'bypassed',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('token');
    }

    /** @test */
    public function reset_password_failed_with_invalid_email()
    {
        $this->byPassRecaptcha();

        $password = 'V3ry.Strong-P@ssw0rd!!';

        $this->postJson($this->endpoint, [
            'token'                 => $this->token,
            'email'                 => 'admin@admin.net',
            'password'              => $password,
            'password_confirmation' => $password,
            'g-recaptcha-response'  => 'bypassed',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('token');
    }

    /** @test */
    public function reset_password_succeed()
    {
        $this->byPassRecaptcha();

        $password = 'V3ry.Strong-P@ssw0rd!!';

        $this->postJson($this->endpoint, [
            'token'                 => $this->token,
            'email'                 => $this->cmsAdmin->email,
            'password'              => $password,
            'password_confirmation' => $password,
            'g-recaptcha-response'  => 'bypassed',
        ])
            ->assertStatus(200)
            ->assertJsonFragment(['message' => 'Your account password has been reset. Please re-login using the new password.']);
    }
}
