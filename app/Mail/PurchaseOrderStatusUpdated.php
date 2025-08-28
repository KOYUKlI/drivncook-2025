<?php

namespace App\Mail;

use App\Models\PurchaseOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PurchaseOrderStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public PurchaseOrder $purchaseOrder,
        public string $oldStatus,
        public string $newStatus,
        public ?string $note = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('emails.po_status_updated', ['number' => $this->purchaseOrder->number]),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.purchase-orders.status-updated',
            with: [
                'purchaseOrder' => $this->purchaseOrder,
                'oldStatus' => $this->oldStatus,
                'newStatus' => $this->newStatus,
                'note' => $this->note,
                'viewUrl' => route('fo.purchase-orders.show', $this->purchaseOrder),
            ],
        );
    }
}
