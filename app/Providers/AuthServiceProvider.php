<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Support\HtmlString;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use Illuminate\Support\Facades\Lang;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->greeting('Hello ' . $notifiable->first_name . ', ')
                ->subject('Verify Email Address')
                ->line('Thank you for creating a MySI account.')
                ->line('Click the button below to verify your email address.')
                ->action('Verify Email Address', $url)
                ->salutation(new HtmlString("**Regards**, <br>" . 'MySI Portal Admin'));
        });

        ResetPassword::toMailUsing(function (object $notifiable, string $token) {

            $url = route('password.reset', [
                    'token' => $token,
                    'email' => $notifiable->getEmailForPasswordReset(),
                ]);

            return (new MailMessage)
                ->greeting('Hello ' . $notifiable->first_name . ',')
                ->subject('Reset MySI Portal Password')
                ->line(Lang::get('You are receiving this email because we received a password reset request for your account.'))
                ->action(Lang::get('Reset Password'), $url)
                ->line(Lang::get('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
                ->line(Lang::get('If you did not request a password reset, no further action is required.'))
                ->salutation(new HtmlString("**Regards**, <br>" . 'MySI Portal Admin'));
        });
    }
}
