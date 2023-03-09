<?php

namespace App\Http\Controllers\CmsAuth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * ForgotPasswordController constructor.
     */
    public function __construct()
    {
        ResetPassword::toMailUsing(static function ($notifiable, $token) {
            $name = data_get($notifiable, 'name');

            return (new MailMessage())
                ->subject('Reset Password Notification')
                ->line('Hi '.$name.',')
                ->line('You are receiving this email because we received a password reset request for your account.')
                ->action('Reset Password', url(config('api.cms_url').'/auth/password/reset/'.$token))
                ->line('This password reset link will expire in '.config('auth.passwords.cms_admins.expire').' minutes.')
                ->line('If you did not request a password reset, no further action is required.');
        });
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker(): PasswordBroker
    {
        return \Password::broker('cms_admins');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @throws ValidationException
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
            $this->credentials($request)
        );

        if ($response !== Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => 'There was no admin registered with the given email address.',
            ]);
        }

        return response()->json([
            'message' => 'The password reset link has been sent to your email address.',
        ]);
    }

    /**
     * Validate the email for the given request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    protected function validateEmail(Request $request)
    {
        $rules = [
            'email' => 'required|string|email|min:11',
        ];

        if (config('api.captcha_enabled')) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        $request->validate($rules);
    }
}
