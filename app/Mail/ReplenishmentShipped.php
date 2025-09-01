<?php

namespace App\Mail;

use App\Models\PurchaseOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReplenishmentShipped extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public PurchaseOrder $order, public ?string $deliveryNotePath = null) {}

    public function envelope(): Envelope
    {
        $number = $this->order->reference ?? $this->order->id;
        return new Envelope(
            subject: __('emails.replenishment_shipped_subject', ['number' => $number]),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.replenishments.shipped',
            with: [
                'order' => $this->order,
                'downloadUrl' => $this->deliveryNotePath ? asset('storage/'.$this->deliveryNotePath) : null,
            ],
        );
    }
}
