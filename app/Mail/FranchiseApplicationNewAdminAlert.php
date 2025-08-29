<?php

namespace App\Mail;

use App\Models\FranchiseApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FranchiseApplicationNewAdminAlert extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public FranchiseApplication $application
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouvelle candidature franchise - '.$this->application->full_name,
            to: config('mail.admin_notifications', ['admin@drivncook.local']),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.applications.new-admin-alert',
            with: [
                'application' => $this->application,
                'boUrl' => route('bo.applications.show', $this->application),
                'documentsCount' => $this->application->documents()->count(),
            ],
        );
    }
}
