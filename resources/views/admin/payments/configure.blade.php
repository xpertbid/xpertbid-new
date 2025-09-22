@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="/admin">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.payments.index') }}">Payment Gateways</a></li>
                        <li class="breadcrumb-item active">Configure {{ ucfirst($gateway) }}</li>
                    </ol>
                </div>
                <h4 class="page-title">Configure {{ ucfirst($gateway) }} Payment Gateway</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ ucfirst($gateway) }} Configuration</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.payments.store-config', $gateway) }}" method="POST">
                        @csrf
                        
                        @if($gateway === 'stripe')
                            <div class="mb-3">
                                <label for="stripe_public_key" class="form-label">Stripe Public Key <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('stripe_public_key') is-invalid @enderror" 
                                       id="stripe_public_key" name="stripe_public_key" 
                                       value="{{ old('stripe_public_key', 'pk_test_...') }}" required>
                                @error('stripe_public_key')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="stripe_secret_key" class="form-label">Stripe Secret Key <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('stripe_secret_key') is-invalid @enderror" 
                                       id="stripe_secret_key" name="stripe_secret_key" 
                                       value="{{ old('stripe_secret_key', 'sk_test_...') }}" required>
                                @error('stripe_secret_key')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="stripe_webhook_secret" class="form-label">Webhook Secret</label>
                                <input type="password" class="form-control @error('stripe_webhook_secret') is-invalid @enderror" 
                                       id="stripe_webhook_secret" name="stripe_webhook_secret" 
                                       value="{{ old('stripe_webhook_secret', 'whsec_...') }}">
                                @error('stripe_webhook_secret')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                        @elseif($gateway === 'paypal')
                            <div class="mb-3">
                                <label for="paypal_client_id" class="form-label">PayPal Client ID <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('paypal_client_id') is-invalid @enderror" 
                                       id="paypal_client_id" name="paypal_client_id" 
                                       value="{{ old('paypal_client_id', 'AeA1QIZXiflr1_...') }}" required>
                                @error('paypal_client_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="paypal_client_secret" class="form-label">PayPal Client Secret <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('paypal_client_secret') is-invalid @enderror" 
                                       id="paypal_client_secret" name="paypal_client_secret" 
                                       value="{{ old('paypal_client_secret', 'EC0...') }}" required>
                                @error('paypal_client_secret')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="paypal_mode" class="form-label">PayPal Mode <span class="text-danger">*</span></label>
                                <select class="form-select @error('paypal_mode') is-invalid @enderror" 
                                        id="paypal_mode" name="paypal_mode" required>
                                    <option value="sandbox" {{ old('paypal_mode', 'sandbox') == 'sandbox' ? 'selected' : '' }}>Sandbox</option>
                                    <option value="live" {{ old('paypal_mode') == 'live' ? 'selected' : '' }}>Live</option>
                                </select>
                                @error('paypal_mode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                        @elseif($gateway === 'razorpay')
                            <div class="mb-3">
                                <label for="razorpay_key_id" class="form-label">Razorpay Key ID <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('razorpay_key_id') is-invalid @enderror" 
                                       id="razorpay_key_id" name="razorpay_key_id" 
                                       value="{{ old('razorpay_key_id', 'rzp_test_...') }}" required>
                                @error('razorpay_key_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="razorpay_key_secret" class="form-label">Razorpay Key Secret <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('razorpay_key_secret') is-invalid @enderror" 
                                       id="razorpay_key_secret" name="razorpay_key_secret" 
                                       value="{{ old('razorpay_key_secret', '...') }}" required>
                                @error('razorpay_key_secret')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                        @else
                            <div class="mb-3">
                                <label for="api_key" class="form-label">API Key <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('api_key') is-invalid @enderror" 
                                       id="api_key" name="api_key" 
                                       value="{{ old('api_key') }}" required>
                                @error('api_key')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="api_secret" class="form-label">API Secret <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('api_secret') is-invalid @enderror" 
                                       id="api_secret" name="api_secret" 
                                       value="{{ old('api_secret') }}" required>
                                @error('api_secret')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                        
                        <div class="mb-3">
                            <label for="is_active" class="form-label">Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Enable {{ ucfirst($gateway) }} Payment Gateway
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Save Configuration
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Gateway Information</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-1"></i> {{ ucfirst($gateway) }} Setup</h6>
                        <p class="mb-2">Configure your {{ ucfirst($gateway) }} payment gateway settings:</p>
                        <ul class="mb-0">
                            @if($gateway === 'stripe')
                                <li>Get your API keys from Stripe Dashboard</li>
                                <li>Use test keys for development</li>
                                <li>Switch to live keys for production</li>
                            @elseif($gateway === 'paypal')
                                <li>Create app in PayPal Developer Console</li>
                                <li>Use sandbox for testing</li>
                                <li>Switch to live for production</li>
                            @elseif($gateway === 'razorpay')
                                <li>Get credentials from Razorpay Dashboard</li>
                                <li>Use test mode for development</li>
                                <li>Enable live mode for production</li>
                            @else
                                <li>Enter your API credentials</li>
                                <li>Test the connection</li>
                                <li>Enable when ready</li>
                            @endif
                        </ul>
                    </div>
                    
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle me-1"></i> Security Notes</h6>
                        <ul class="mb-0">
                            <li>Never share your secret keys</li>
                            <li>Use environment variables</li>
                            <li>Test thoroughly before going live</li>
                            <li>Monitor transactions regularly</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
