<?php

namespace App\Mail;

use App\Models\Franchisee;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FranchiseeOnboardingWelcome extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Franchisee $franchisee,
        public string $tempPassword
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('emails.onboarding_welcome'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.franchisees.onboarding-welcome',
            with: [
                'franchisee' => $this->franchisee,
                'tempPassword' => $this->tempPassword,
                'loginUrl' => route('login'),
            ],
        );
    }
}
