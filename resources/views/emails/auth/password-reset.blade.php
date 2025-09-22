@extends('emails.layouts.app')

@section('title', 'Reset Your Password')

@section('content')
<h2>Password Reset Request</h2>

<p>Hello {{ $userName }},</p>

<p>We received a request to reset your password for your XpertBid account. If you made this request, click the button below to reset your password.</p>

<div class="alert alert-warning">
    <strong>Important:</strong> This password reset link will expire in {{ $expiryMinutes }} minutes for security reasons.
</div>

<a href="{{ $resetUrl }}" class="button">Reset My Password</a>

<p>If the button doesn't work, you can copy and paste this link into your browser:</p>
<p style="word-break: break-all; color: #3498db;">{{ $resetUrl }}</p>

<div class="alert alert-info">
    <strong>Didn't request this?</strong><br>
    If you didn't request a password reset, you can safely ignore this email. Your password will remain unchanged.
</div>

<h3>Security Tips:</h3>
<ul>
    <li>Use a strong, unique password</li>
    <li>Never share your password with anyone</li>
    <li>Log out from shared devices</li>
    <li>Enable two-factor authentication if available</li>
</ul>

<p>If you're having trouble resetting your password, please contact our support team.</p>

<p>Best regards,<br><strong>The XpertBid Security Team</strong></p>
@endsection
