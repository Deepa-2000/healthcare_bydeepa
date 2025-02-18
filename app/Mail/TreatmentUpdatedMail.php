<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TreatmentUpdatedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $treatment;

    public function __construct($treatment)
    {
        $this->treatment = $treatment;
    }

    public function build()
    {
        $treatment = $this->treatment;
        return $this->subject('Your Treatment Has Been Updated')
            ->view('email.treatment_updated')
            ->with([
                'treatment_name' => $treatment->treatment_name,
                'notes' => $treatment->notes,
                // 'updated_at' => $treatment->updated_at,
            ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome email',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'treatment_update',
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
