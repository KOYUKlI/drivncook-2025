<?php

namespace App\Mail;

use App\Models\FranchiseApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FranchiseApplicationSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public FranchiseApplication $application
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('emails.application_submitted'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.applications.submitted',
            with: [
                'application' => $this->application,
            ],
        );
    }
}
