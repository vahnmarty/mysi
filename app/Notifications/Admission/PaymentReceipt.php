<?php

namespace App\Notifications\Admission;

use App\Models\Payment;
use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Support\HtmlString;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PaymentReceipt extends Notification
{
    use Queueable;

    public Application $app;
    public Payment $payment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->payment = $app->applicationFee;
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
                    ->subject('SI Admissions Application Receipt')
                    ->greeting('Hi ' . $notifiable->first_name . ', ')
                    ->line('Thank you for your payment of **$'. number_format($this->payment->final_amount,2). '**. Your transaction confirmation code is: **'. $this->payment->transaction_id.'**. Please keep this information for your records.')
                    ->salutation(new HtmlString("**Regards**, <br>" . 'SI Admissions'));
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
