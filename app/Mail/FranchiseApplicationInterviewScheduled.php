<?php

namespace App\Mail;

use App\Models\FranchiseApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FranchiseApplicationInterviewScheduled extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public FranchiseApplication $application,
        public string $interviewDate,
        public ?string $interviewDetails = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📅 Entretien franchise DrivnCook - '.$this->application->full_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.applications.interview-scheduled',
            with: [
                'application' => $this->application,
                'interviewDate' => $this->interviewDate,
                'interviewDetails' => $this->interviewDetails,
            ],
        );
    }
}
