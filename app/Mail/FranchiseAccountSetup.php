<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FranchiseAccountSetup extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public string $resetUrl)
    {
    }

    public function build(): self
    {
        return $this->subject("Activez votre accès — définissez votre mot de passe")
            ->view('emails.franchise-account-setup')
            ->text('emails.franchise-account-setup-text');
    }
}
