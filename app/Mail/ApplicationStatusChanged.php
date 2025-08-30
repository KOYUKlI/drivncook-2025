<?php

namespace App\Mail;

use App\Models\FranchiseApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public FranchiseApplication $application,
        public string $oldStatus,
        public string $newStatus,
        public ?string $adminMessage = ''
    ) {}

    public function envelope(): Envelope
    {
        $subject = match ($this->newStatus) {
            'prequalified' => __('emails.application_status_prequalified'),
            'interview' => __('emails.application_status_interview'),
            'approved' => __('emails.application_status_approved'),
            'rejected' => __('emails.application_status_rejected'),
            default => __('emails.application_status_rejected'),
        };

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.applications.status-changed',
            with: [
                'application' => $this->application,
                'oldStatus' => $this->oldStatus,
                'newStatus' => $this->newStatus,
                'adminMessage' => $this->adminMessage,
            ],
        );
    }
}
