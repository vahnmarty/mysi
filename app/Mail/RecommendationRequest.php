<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\SupplementalRecommendationRequest;

class RecommendationRequest extends Mailable
{
    use Queueable, SerializesModels;

    public SupplementalRecommendationRequest $recommendation;

    /**
     * Create a new message instance.
     */
    public function __construct(SupplementalRecommendationRequest $recommendation)
    {
        $this->recommendation = $recommendation->load('application', 'child');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Recommendation Request for {$this->recommendation->child->first_name} {$this->recommendation->child->last_name} ",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.recommendation-request',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
