<?php

namespace App\Mail;

use App\Models\FranchiseApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FranchiseApplicationApproved extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public FranchiseApplication $application,
        public ?string $welcomeMessage = null,
        public ?string $nextSteps = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎊 Félicitations ! Votre franchise DrivnCook est approuvée',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.applications.approved',
            with: [
                'application' => $this->application,
                'welcomeMessage' => $this->welcomeMessage,
                'nextSteps' => $this->nextSteps,
            ],
        );
    }
}
