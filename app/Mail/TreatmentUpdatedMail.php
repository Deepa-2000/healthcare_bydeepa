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
    public $msg;
    public $sub;

    public function __construct($msg,$sub)
    {
        $this->msg = $msg;
        $this->sub = $sub;
    }

    // public function build()
    // {
    //     return $this->subject('Treatment Updated')
    //         ->view('treatment_update')
    //         ->with([
    //             'treatment_name' => $this->msg->msg_name,
    //             'notes' => $this->msg->notes,
    //         ]);
    // }
    // public function build()
    // {
    //     $treatment = $this->treatment;
    //     return $this->subject('Your Treatment Has Been Updated')
    //         ->view('email.treatment_updated')
    //         ->with([
    //             'treatment_name' => $treatment->treatment_name,
    //             'notes' => $treatment->notes,
    //             'updated_at' => $treatment->updated_at,
    //         ]);
    // }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->sub,
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
