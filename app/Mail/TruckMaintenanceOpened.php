<?php

namespace App\Mail;

use App\Models\MaintenanceLog;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TruckMaintenanceOpened extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public MaintenanceLog $maintenanceLog
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('emails.maintenance_opened', ['truck' => $this->maintenanceLog->truck->plate]),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.trucks.maintenance-opened',
            with: [
                'maintenanceLog' => $this->maintenanceLog,
                'truck' => $this->maintenanceLog->truck,
                'viewUrl' => route('bo.trucks.show', $this->maintenanceLog->truck),
            ],
        );
    }
}
