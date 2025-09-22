<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Order $order
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Confirmation #' . $this->order->order_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order.confirmation',
            with: [
                'user' => $this->user,
                'order' => $this->order,
                'orderUrl' => config('app.frontend_url') . '/orders/' . $this->order->id,
                'trackingUrl' => config('app.frontend_url') . '/track-order/' . $this->order->tracking_number,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
