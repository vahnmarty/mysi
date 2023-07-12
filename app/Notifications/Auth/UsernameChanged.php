<?php

namespace App\Notifications\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Support\HtmlString;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UsernameChanged extends Notification
{
    use Queueable;

    public $oldEmail;
    /**
     * Create a new notification instance.
     */
    public function __construct($oldEmail = null)
    {
        $this->oldEmail = $oldEmail;
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
        $mail = (new MailMessage)
                    ->subject("Username Changed")
                    ->greeting('Hello ' . $notifiable->first_name . ',')
                    ->line('Your MySI username/email has been changed.  If you did not make this change, please contact **admissions@siprep.org** for assistance.')
                    ->line('If you made the change, please ignore this email.')
                    ->salutation(new HtmlString("**Regards,** <br>" . 'MySI Portal Admin'));

        if($this->oldEmail){
            $mail->cc($this->oldEmail);
        }
        

        return $mail;
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
