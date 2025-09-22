@extends('emails.layouts.app')

@section('title', 'Welcome to XpertBid')

@section('content')
<h2>Welcome to XpertBid, {{ $user->name }}!</h2>

<p>We're excited to have you join our premier multi-vendor marketplace and auction platform. Your account has been successfully created and you're ready to start exploring all that XpertBid has to offer.</p>

<div class="alert alert-success">
    <strong>Your Account Details:</strong><br>
    <strong>Email:</strong> {{ $user->email }}<br>
    @if($password)
    <strong>Temporary Password:</strong> {{ $password }}<br>
    <em>Please change your password after your first login for security.</em>
    @endif
</div>

<h3>What's Next?</h3>
<ul>
    <li><strong>Complete Your Profile:</strong> Add your personal information and preferences</li>
    <li><strong>Browse Products:</strong> Discover amazing products from our trusted vendors</li>
    <li><strong>Join Auctions:</strong> Participate in exciting auctions and place bids</li>
    <li><strong>Become a Vendor:</strong> Apply to sell your products on our platform</li>
</ul>

<a href="{{ $loginUrl }}" class="button">Get Started Now</a>

<h3>Platform Features</h3>
<div style="display: flex; flex-wrap: wrap; gap: 20px; margin: 20px 0;">
    <div style="flex: 1; min-width: 150px;">
        <h4>🛍️ Multi-Vendor Marketplace</h4>
        <p>Shop from thousands of verified vendors with diverse product catalogs.</p>
    </div>
    <div style="flex: 1; min-width: 150px;">
        <h4>🔨 Advanced Auctions</h4>
        <p>Participate in real-time auctions with automated bidding systems.</p>
    </div>
    <div style="flex: 1; min-width: 150px;">
        <h4>🏠 Property & Vehicle Sales</h4>
        <p>Browse real estate and vehicle listings with detailed specifications.</p>
    </div>
</div>

<h3>Need Help?</h3>
<p>Our support team is here to help you get the most out of XpertBid. If you have any questions, feel free to:</p>
<ul>
    <li>Visit our <a href="{{ config('app.frontend_url') }}/help">Help Center</a></li>
    <li>Contact our <a href="{{ config('app.frontend_url') }}/support">Support Team</a></li>
    <li>Join our <a href="{{ config('app.frontend_url') }}/community">Community Forum</a></li>
</ul>

<p>Happy shopping and bidding!</p>
<p><strong>The XpertBid Team</strong></p>
@endsection
