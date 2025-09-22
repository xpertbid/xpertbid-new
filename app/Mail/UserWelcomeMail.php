<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserWelcomeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $password = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to XpertBid - Your Account is Ready!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.user.welcome',
            with: [
                'user' => $this->user,
                'password' => $this->password,
                'loginUrl' => config('app.frontend_url') . '/login',
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
