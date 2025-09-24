@extends('admin.layouts.app')

@section('title', 'Order Details - ' . $order->order_number)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Order Header -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-receipt mr-2"></i>
                        Order #{{ $order->order_number }}
                    </h3>
                    <div>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Back to Orders
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Order Information</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Order Number:</strong></td>
                                    <td>{{ $order->order_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'confirmed' => 'info',
                                                'processing' => 'primary',
                                                'shipped' => 'secondary',
                                                'delivered' => 'success',
                                                'cancelled' => 'danger',
                                                'refunded' => 'dark'
                                            ];
                                        @endphp
                                        <span class="badge badge-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Status:</strong></td>
                                    <td>
                                        @php
                                            $paymentColors = [
                                                'pending' => 'warning',
                                                'paid' => 'success',
                                                'failed' => 'danger',
                                                'refunded' => 'dark',
                                                'partially_refunded' => 'info'
                                            ];
                                        @endphp
                                        <span class="badge badge-{{ $paymentColors[$order->payment_status] ?? 'secondary' }}">
                                            {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Method:</strong></td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Transaction ID:</strong></td>
                                    <td>{{ $order->transaction_id ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tracking Number:</strong></td>
                                    <td>{{ $order->tracking_number ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Customer Information</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Customer:</strong></td>
                                    <td>{{ $order->customer_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $order->customer_email ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Vendor:</strong></td>
                                    <td>{{ $order->vendor_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Order Date:</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y h:i A') }}</td>
                                </tr>
                                @if($order->confirmed_at)
                                <tr>
                                    <td><strong>Confirmed:</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($order->confirmed_at)->format('M d, Y h:i A') }}</td>
                                </tr>
                                @endif
                                @if($order->shipped_at)
                                <tr>
                                    <td><strong>Shipped:</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($order->shipped_at)->format('M d, Y h:i A') }}</td>
                                </tr>
                                @endif
                                @if($order->delivered_at)
                                <tr>
                                    <td><strong>Delivered:</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($order->delivered_at)->format('M d, Y h:i A') }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <!-- Order Items -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="fas fa-box mr-2"></i>
                                Order Items ({{ $orderItems->count() }})
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Product</th>
                                            <th>SKU</th>
                                            <th>Vendor</th>
                                            <th>Qty</th>
                                            <th>Unit Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orderItems as $item)
                                        <tr>
                                            <td>
                                                <strong>{{ $item->product_name }}</strong>
                                                @if($item->product_attributes)
                                                    @php
                                                        $attributes = json_decode($item->product_attributes, true);
                                                    @endphp
                                                    @if($attributes)
                                                        <br>
                                                        <small class="text-muted">
                                                            @foreach($attributes as $key => $value)
                                                                <span class="badge badge-light mr-1">{{ ucfirst($key) }}: {{ $value }}</span>
                                                            @endforeach
                                                        </small>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>{{ $item->product_sku }}</td>
                                            <td>{{ $item->vendor_name ?? 'N/A' }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>${{ number_format($item->unit_price, 2) }}</td>
                                            <td><strong>${{ number_format($item->total_price, 2) }}</strong></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="fas fa-calculator mr-2"></i>
                                Order Summary
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <tr>
                                    <td>Subtotal:</td>
                                    <td class="text-right">${{ number_format($order->subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Shipping:</td>
                                    <td class="text-right">${{ number_format($order->shipping_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Tax:</td>
                                    <td class="text-right">${{ number_format($order->tax_amount, 2) }}</td>
                                </tr>
                                @if($order->discount_amount > 0)
                                <tr>
                                    <td>Discount:</td>
                                    <td class="text-right text-success">-${{ number_format($order->discount_amount, 2) }}</td>
                                </tr>
                                @endif
                                <tr class="table-active">
                                    <td><strong>Total:</strong></td>
                                    <td class="text-right"><strong>${{ number_format($order->total_amount, 2) }}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    @if($payments->count() > 0)
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="fas fa-credit-card mr-2"></i>
                                Payment Information
                            </h5>
                        </div>
                        <div class="card-body">
                            @foreach($payments as $payment)
                            <div class="border-bottom pb-2 mb-2">
                                <div class="d-flex justify-content-between">
                                    <span><strong>Payment ID:</strong></span>
                                    <span>{{ $payment->payment_id }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span><strong>Gateway:</strong></span>
                                    <span>{{ ucfirst($payment->gateway) }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span><strong>Amount:</strong></span>
                                    <span>${{ number_format($payment->amount, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span><strong>Status:</strong></span>
                                    <span class="badge badge-{{ $payment->status === 'paid' ? 'success' : ($payment->status === 'failed' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </div>
                                @if($payment->processed_at)
                                <div class="d-flex justify-content-between">
                                    <span><strong>Processed:</strong></span>
                                    <span>{{ \Carbon\Carbon::parse($payment->processed_at)->format('M d, Y h:i A') }}</span>
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Addresses -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                Addresses
                            </h5>
                        </div>
                        <div class="card-body">
                            @php
                                $billingAddress = json_decode($order->billing_address, true);
                                $shippingAddress = json_decode($order->shipping_address, true);
                            @endphp
                            
                            <h6>Billing Address</h6>
                            @if($billingAddress)
                                <address>
                                    {{ $billingAddress['first_name'] ?? '' }} {{ $billingAddress['last_name'] ?? '' }}<br>
                                    {{ $billingAddress['address_1'] ?? '' }}<br>
                                    @if($billingAddress['address_2'])
                                        {{ $billingAddress['address_2'] }}<br>
                                    @endif
                                    {{ $billingAddress['city'] ?? '' }}, {{ $billingAddress['state'] ?? '' }} {{ $billingAddress['postal_code'] ?? '' }}<br>
                                    {{ $billingAddress['country'] ?? '' }}<br>
                                    @if($billingAddress['phone'])
                                        <i class="fas fa-phone"></i> {{ $billingAddress['phone'] }}<br>
                                    @endif
                                    @if($billingAddress['email'])
                                        <i class="fas fa-envelope"></i> {{ $billingAddress['email'] }}
                                    @endif
                                </address>
                            @else
                                <p class="text-muted">No billing address available</p>
                            @endif

                            <hr>

                            <h6>Shipping Address</h6>
                            @if($shippingAddress)
                                <address>
                                    {{ $shippingAddress['first_name'] ?? '' }} {{ $shippingAddress['last_name'] ?? '' }}<br>
                                    {{ $shippingAddress['address_1'] ?? '' }}<br>
                                    @if($shippingAddress['address_2'])
                                        {{ $shippingAddress['address_2'] }}<br>
                                    @endif
                                    {{ $shippingAddress['city'] ?? '' }}, {{ $shippingAddress['state'] ?? '' }} {{ $shippingAddress['postal_code'] ?? '' }}<br>
                                    {{ $shippingAddress['country'] ?? '' }}<br>
                                    @if($shippingAddress['phone'])
                                        <i class="fas fa-phone"></i> {{ $shippingAddress['phone'] }}<br>
                                    @endif
                                    @if($shippingAddress['email'])
                                        <i class="fas fa-envelope"></i> {{ $shippingAddress['email'] }}
                                    @endif
                                </address>
                            @else
                                <p class="text-muted">No shipping address available</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Notes -->
            @if($order->notes)
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-sticky-note mr-2"></i>
                        Order Notes
                    </h5>
                </div>
                <div class="card-body">
                    <p>{{ $order->notes }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
