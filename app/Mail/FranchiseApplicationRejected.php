<?php

namespace App\Mail;

use App\Models\FranchiseApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FranchiseApplicationRejected extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public FranchiseApplication $application)
    {
    }

    public function build(): self
    {
        return $this->subject('Votre candidature n’a pas été retenue')
            ->view('emails.franchise-application-rejected')
            ->text('emails.franchise-application-rejected-text');
    }
}
