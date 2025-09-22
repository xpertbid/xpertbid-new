@extends('emails.layouts.app')

@section('title', 'Order Confirmation')

@section('content')
<h2>Order Confirmation</h2>

<p>Hello {{ $user->name }},</p>

<p>Thank you for your order! We've received your order and it's being processed. Here are the details:</p>

<div class="order-details">
    <h3>Order Information</h3>
    <div class="order-item">
        <span><strong>Order Number:</strong></span>
        <span>{{ $order->order_number }}</span>
    </div>
    <div class="order-item">
        <span><strong>Order Date:</strong></span>
        <span>{{ $order->created_at->format('M j, Y \a\t g:i A') }}</span>
    </div>
    <div class="order-item">
        <span><strong>Status:</strong></span>
        <span style="color: #28a745; font-weight: bold;">{{ ucfirst($order->status) }}</span>
    </div>
    @if($order->tracking_number)
    <div class="order-item">
        <span><strong>Tracking Number:</strong></span>
        <span>{{ $order->tracking_number }}</span>
    </div>
    @endif
</div>

<div class="order-details">
    <h3>Shipping Address</h3>
    <p>
        {{ $order->shipping_address['name'] ?? $user->name }}<br>
        {{ $order->shipping_address['address_line_1'] }}<br>
        @if($order->shipping_address['address_line_2'])
            {{ $order->shipping_address['address_line_2'] }}<br>
        @endif
        {{ $order->shipping_address['city'] }}, {{ $order->shipping_address['state'] }} {{ $order->shipping_address['postal_code'] }}<br>
        {{ $order->shipping_address['country'] }}
    </p>
</div>

<div class="order-details">
    <h3>Order Items</h3>
    @foreach($order->items as $item)
    <div class="order-item">
        <div>
            <strong>{{ $item->product_name }}</strong><br>
            <small>SKU: {{ $item->sku }}</small><br>
            <small>Quantity: {{ $item->quantity }}</small>
        </div>
        <div>
            ${{ number_format($item->price * $item->quantity, 2) }}
        </div>
    </div>
    @endforeach
    
    <div class="order-item" style="border-top: 2px solid #dee2e6; font-weight: bold;">
        <span>Subtotal:</span>
        <span>${{ number_format($order->subtotal, 2) }}</span>
    </div>
    @if($order->tax_amount > 0)
    <div class="order-item">
        <span>Tax:</span>
        <span>${{ number_format($order->tax_amount, 2) }}</span>
    </div>
    @endif
    @if($order->shipping_amount > 0)
    <div class="order-item">
        <span>Shipping:</span>
        <span>${{ number_format($order->shipping_amount, 2) }}</span>
    </div>
    @endif
    <div class="order-item" style="border-top: 2px solid #dee2e6; font-weight: bold; font-size: 18px;">
        <span>Total:</span>
        <span>${{ number_format($order->total, 2) }}</span>
    </div>
</div>

@if($order->tracking_number)
    <a href="{{ $trackingUrl }}" class="button">Track Your Order</a>
@endif

<a href="{{ $orderUrl }}" class="button">View Order Details</a>

<div class="alert alert-info">
    <strong>What's Next?</strong><br>
    <ul>
        <li>Your order is being prepared for shipment</li>
        <li>You'll receive a shipping confirmation email with tracking information</li>
        <li>Expected delivery: 3-5 business days</li>
    </ul>
</div>

<h3>Need Help?</h3>
<p>If you have any questions about your order, please contact our customer support team or visit your order page.</p>

<p>Thank you for choosing XpertBid!<br><strong>The XpertBid Team</strong></p>
@endsection
