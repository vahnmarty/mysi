<?php

namespace App\Notifications\Admission;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Support\HtmlString;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ApplicationSubmitted extends Notification
{
    use Queueable;

    public Application $app;

    /**
     * Create a new notification instance.
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
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
                    ->bcc('admissions@siprep.org')
                    ->markdown('emails.admission.application-submitted', [
                        'user' => $notifiable,
                        'app' => $this->app
                    ]);

        if($this->app->file_learning_documentation){
            $mail->cc('ggalletta@siprep.org')
                 ->cc('pcollins@siprep.org');
        }
        
        return $mail;

                    // ->greeting('Hi ' . $notifiable->first_name . ', ')
                    // ->line('**Applicant Name:** ' . $this->app->student->first_name . ' ' . $this->app->student->last_name )
                    // ->line('Thank you for submitting your application to St. Ignatius College Preparatory.')
                    // ->line('If you have any questions regarding the Admission process, please visit our website at https://www.siprep.org/admissions or email us at admissions@siprep.org')
                    // ->salutation(new HtmlString("**Regards**, <br>" . 'SI Admissions'));
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
