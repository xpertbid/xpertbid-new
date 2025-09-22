@extends('emails.layouts.app')

@section('title', 'Auction Update')

@section('content')
@if($type === 'new_bid')
    <h2>New Bid on Your Auction!</h2>
    <p>Great news! Someone has placed a new bid on your auction.</p>
@elseif($type === 'outbid')
    <h2>You've Been Outbid!</h2>
    <p>Someone has placed a higher bid on an auction you're participating in.</p>
@elseif($type === 'auction_ending')
    <h2>Auction Ending Soon!</h2>
    <p>An auction you're interested in is ending soon. Don't miss out!</p>
@elseif($type === 'auction_won')
    <h2>🎉 Congratulations! You Won!</h2>
    <p>Excellent! You won the auction. Time to complete your purchase.</p>
@elseif($type === 'auction_lost')
    <h2>Auction Ended</h2>
    <p>The auction has ended. Better luck next time!</p>
@else
    <h2>Auction Update</h2>
    <p>Here's an update about an auction you're following.</p>
@endif

<div class="auction-card">
    <h3>{{ $auction->title }}</h3>
    <p><strong>Current Bid:</strong> ${{ number_format($auction->current_bid, 2) }}</p>
    <p><strong>Bid Count:</strong> {{ $auction->bid_count }} bids</p>
    
    @if($type === 'auction_ending' || $type === 'auction_won' || $type === 'auction_lost')
        <div class="auction-timer">
            @if($type === 'auction_ending')
                ⏰ Ending Soon!
            @elseif($type === 'auction_won')
                ✅ You Won!
            @else
                ❌ Auction Ended
            @endif
        </div>
    @endif
    
    @if($auction->end_time)
        <p><strong>End Time:</strong> {{ $auction->end_time->format('M j, Y \a\t g:i A') }}</p>
    @endif
</div>

@if($type === 'new_bid' && isset($data['bid_amount']))
    <div class="alert alert-info">
        <strong>New Bid Amount:</strong> ${{ number_format($data['bid_amount'], 2) }}<br>
        <strong>Bidder:</strong> {{ $data['bidder_name'] ?? 'Anonymous' }}
    </div>
@endif

@if($type === 'outbid' && isset($data['new_bid_amount']))
    <div class="alert alert-warning">
        <strong>You were outbid by:</strong> ${{ number_format($data['new_bid_amount'], 2) }}<br>
        <strong>Your bid:</strong> ${{ number_format($data['your_bid'], 2) }}
    </div>
@endif

@if($type === 'auction_won')
    <div class="alert alert-success">
        <strong>Congratulations!</strong> You won this auction with a bid of ${{ number_format($auction->current_bid, 2) }}.
        Please complete your purchase within 48 hours.
    </div>
    
    <a href="{{ $auctionUrl }}/checkout" class="button">Complete Purchase</a>
@else
    <a href="{{ $auctionUrl }}" class="button">View Auction</a>
@endif

@if($type === 'auction_ending')
    <h3>Don't Miss Out!</h3>
    <p>This auction is ending soon. If you're interested, make sure to place your bid before time runs out!</p>
    
    <ul>
        <li>Set up automatic bidding to stay competitive</li>
        <li>Monitor the auction closely as it approaches the end time</li>
        <li>Be prepared to bid in the final moments if needed</li>
    </ul>
@endif

<h3>Need Help?</h3>
<p>If you have any questions about this auction or need assistance, please contact our support team.</p>

<p>Happy bidding!<br><strong>The XpertBid Team</strong></p>
@endsection
