<?php

namespace App\Notifications\Auth;

use App\Models\EmailRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Support\HtmlString;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ConfirmEmailRequest extends Notification
{
    use Queueable;

    public EmailRequest $email;

    /**
     * Create a new notification instance.
     */
    public function __construct(EmailRequest $email)
    {
        $this->email = $email;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Request for New Email/Username')
                    ->greeting('Hello ' . $notifiable->first_name . ',')
                    ->line('We received a request to change your email/username to MySI Portal from *'. $notifiable->email .'* to **'. $this->email->email .'**.')
                    ->line('Confirm the email by clicking the button below.')
                    ->action('Confirm Email', route('email-request.verify', ['email' => $this->email->email , 'token' => $this->email->token]))
                    ->salutation(new HtmlString("**Regards**, <br>" . 'MySI Portal Admin'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
