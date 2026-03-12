<?php

namespace App\Mail;

use App\Models\OrderInfo;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public OrderInfo $order,
        public string $oldStatus,
        public string $newStatus
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Order #{$this->order->orderinfo_id} Status Updated"
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order_status_updated',
            with: [
                'order' => $this->order,
                'oldStatus' => $this->oldStatus,
                'newStatus' => $this->newStatus,
            ]
        );
    }
}

