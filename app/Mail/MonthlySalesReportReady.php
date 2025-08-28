<?php

namespace App\Mail;

use App\Models\Franchisee;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MonthlySalesReportReady extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Franchisee $franchisee,
        public string $period,
        public string $downloadToken
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('emails.monthly_sales_ready', ['month' => $this->period]),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.reports.monthly-sales-ready',
            with: [
                'franchisee' => $this->franchisee,
                'period' => $this->period,
                'downloadUrl' => route('fo.reports.download', ['token' => $this->downloadToken]),
            ],
        );
    }
}
