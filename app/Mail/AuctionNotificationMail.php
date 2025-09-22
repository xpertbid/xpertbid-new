<?php

namespace App\Mail;

use App\Models\Auction;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AuctionNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Auction $auction,
        public string $type, // 'new_bid', 'outbid', 'auction_ending', 'auction_won', 'auction_lost'
        public array $data = []
    ) {}

    public function envelope(): Envelope
    {
        $subject = match($this->type) {
            'new_bid' => 'New Bid Placed on Your Auction',
            'outbid' => 'You\'ve Been Outbid!',
            'auction_ending' => 'Auction Ending Soon - Place Your Bid!',
            'auction_won' => 'Congratulations! You Won the Auction',
            'auction_lost' => 'Auction Ended - Better Luck Next Time',
            default => 'Auction Update'
        };

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.auction.notification',
            with: [
                'user' => $this->user,
                'auction' => $this->auction,
                'type' => $this->type,
                'data' => $this->data,
                'auctionUrl' => config('app.frontend_url') . '/auctions/' . $this->auction->id,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
