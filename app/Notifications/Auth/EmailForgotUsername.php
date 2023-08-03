<?php

namespace App\Notifications\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Support\HtmlString;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EmailForgotUsername extends Notification
{
    use Queueable;

    public $type; // 'email', 'phone'

    /**
     * Create a new notification instance.
     */
    public function __construct($type)
    {
        if($type == 'phone'){
           $this->type = 'mobile number';
        }else{
            $this->type = $type;
        }
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
                    ->subject('MySI Portal Username')
                    ->greeting('Hi ' . $notifiable->first_name . ', ')
                    ->line('Thank you for using the MySI Portal and for your interest in St. Ignatius College Preparatory.')
                    ->line("The username associated with your {$this->type} is:  **{$notifiable->email}**")
                    ->line('To reset your password, you will need to go to [Forgot Password]('.url('forgot-password').') and enter the username/email above. The instructions will go to the email address, so you will need to have access to the email account to reset the password.')
                    ->action('Log In', url('/login'))
                    ->line('If you do not have access to that email or have other technical issues, please contact **admissions@siprep.org**.')
                    ->salutation(new HtmlString("**Regards**, <br>MySI Portal Admin" ));
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
