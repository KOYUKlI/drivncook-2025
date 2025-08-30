<?php

namespace App\Mail;

use App\Models\FranchiseApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FranchiseApplicationRejected extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public FranchiseApplication $application,
        public ?string $reason = null,
        public ?string $feedback = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Candidature franchise DrivnCook - '.$this->application->full_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.applications.rejected',
            with: [
                'application' => $this->application,
                'reason' => $this->reason,
                'feedback' => $this->feedback,
            ],
        );
    }
}
