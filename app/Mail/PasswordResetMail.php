<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $email,
        public string $token,
        public string $userName
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reset Your XpertBid Password',
        );
    }

    public function content(): Content
    {
        $resetUrl = config('app.frontend_url') . '/reset-password?token=' . $this->token . '&email=' . urlencode($this->email);

        return new Content(
            view: 'emails.auth.password-reset',
            with: [
                'userName' => $this->userName,
                'resetUrl' => $resetUrl,
                'token' => $this->token,
                'expiryMinutes' => 60, // Token expires in 60 minutes
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
