<?php

namespace App\Mail;

use App\Models\FranchiseApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FranchiseApplicationReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public FranchiseApplication $application, public bool $forAdmin = false)
    {
    }

    public function build(): self
    {
        $subject = $this->forAdmin
            ? "Nouvelle candidature franchise — {$this->application->full_name}"
            : "Nous avons bien reçu votre candidature";
        return $this->subject($subject)
            ->view('emails.franchise-application-received')
            ->text('emails.franchise-application-received-text');
    }
}
