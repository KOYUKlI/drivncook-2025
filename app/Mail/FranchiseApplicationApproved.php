<?php

namespace App\Mail;

use App\Models\FranchiseApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FranchiseApplicationApproved extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public FranchiseApplication $application)
    {
    }

    public function build(): self
    {
        return $this->subject('Votre candidature a été acceptée')
            ->view('emails.franchise-application-approved')
            ->text('emails.franchise-application-approved-text');
    }
}
