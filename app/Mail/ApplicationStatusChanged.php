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
        public FranchiseApplication|array $application,
        public string $oldStatus,
        public string $newStatus,
        public ?string $adminMessage = ''
    ) {}

    public function envelope(): Envelope
    {
        $subject = match ($this->newStatus) {
            'prequalified' => 'Votre candidature est présélectionnée',
            'interview' => 'Entretien programmé',
            'approved' => 'Candidature approuvée',
            'rejected' => 'Candidature rejetée',
            default => 'Mise à jour de votre candidature',
        };

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.application_status_changed',
            with: [
                'newStatus' => $this->newStatus,
                'oldStatus' => $this->oldStatus,
                'statusMessage' => $this->adminMessage ?? '',
                'application' => $this->application,
            ],
        );
    }
}
