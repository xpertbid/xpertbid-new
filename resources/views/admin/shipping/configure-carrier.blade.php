@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="/admin">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.shipping.index') }}">Shipping</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.shipping.carriers') }}">Carriers</a></li>
                        <li class="breadcrumb-item active">Configure {{ ucfirst($carrierData['name']) }}</li>
                    </ol>
                </div>
                <h4 class="page-title">Configure {{ ucfirst($carrierData['name']) }} Carrier</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ ucfirst($carrierData['name']) }} Configuration</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.shipping.store-carrier-config', $carrier) }}" method="POST">
                        @csrf
                        
                        @if($carrier === 'dhl')
                            <div class="mb-3">
                                <label for="dhl_api_key" class="form-label">DHL API Key <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('dhl_api_key') is-invalid @enderror" 
                                       id="dhl_api_key" name="dhl_api_key" 
                                       value="{{ old('dhl_api_key', $carrierData['api_settings']['api_key'] ?? '') }}" required>
                                @error('dhl_api_key')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="dhl_api_secret" class="form-label">DHL API Secret <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('dhl_api_secret') is-invalid @enderror" 
                                       id="dhl_api_secret" name="dhl_api_secret" 
                                       value="{{ old('dhl_api_secret', $carrierData['api_settings']['api_secret'] ?? '') }}" required>
                                @error('dhl_api_secret')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="dhl_account_number" class="form-label">Account Number</label>
                                <input type="text" class="form-control @error('dhl_account_number') is-invalid @enderror" 
                                       id="dhl_account_number" name="dhl_account_number" 
                                       value="{{ old('dhl_account_number', $carrierData['api_settings']['account_number'] ?? '') }}">
                                @error('dhl_account_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                        @elseif($carrier === 'fedex')
                            <div class="mb-3">
                                <label for="fedex_api_key" class="form-label">FedEx API Key <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('fedex_api_key') is-invalid @enderror" 
                                       id="fedex_api_key" name="fedex_api_key" 
                                       value="{{ old('fedex_api_key', $carrierData['api_settings']['api_key'] ?? '') }}" required>
                                @error('fedex_api_key')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="fedex_api_secret" class="form-label">FedEx API Secret <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('fedex_api_secret') is-invalid @enderror" 
                                       id="fedex_api_secret" name="fedex_api_secret" 
                                       value="{{ old('fedex_api_secret', $carrierData['api_settings']['api_secret'] ?? '') }}" required>
                                @error('fedex_api_secret')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="fedex_account_number" class="form-label">Account Number</label>
                                <input type="text" class="form-control @error('fedex_account_number') is-invalid @enderror" 
                                       id="fedex_account_number" name="fedex_account_number" 
                                       value="{{ old('fedex_account_number', $carrierData['api_settings']['account_number'] ?? '') }}">
                                @error('fedex_account_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="fedex_meter_number" class="form-label">Meter Number</label>
                                <input type="text" class="form-control @error('fedex_meter_number') is-invalid @enderror" 
                                       id="fedex_meter_number" name="fedex_meter_number" 
                                       value="{{ old('fedex_meter_number', $carrierData['api_settings']['meter_number'] ?? '') }}">
                                @error('fedex_meter_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                        @elseif($carrier === 'ups')
                            <div class="mb-3">
                                <label for="ups_access_key" class="form-label">UPS Access Key <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('ups_access_key') is-invalid @enderror" 
                                       id="ups_access_key" name="ups_access_key" 
                                       value="{{ old('ups_access_key', $carrierData['api_settings']['access_key'] ?? '') }}" required>
                                @error('ups_access_key')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="ups_username" class="form-label">UPS Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('ups_username') is-invalid @enderror" 
                                       id="ups_username" name="ups_username" 
                                       value="{{ old('ups_username', $carrierData['api_settings']['username'] ?? '') }}" required>
                                @error('ups_username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="ups_password" class="form-label">UPS Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('ups_password') is-invalid @enderror" 
                                       id="ups_password" name="ups_password" 
                                       value="{{ old('ups_password', $carrierData['api_settings']['password'] ?? '') }}" required>
                                @error('ups_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="ups_account_number" class="form-label">Account Number</label>
                                <input type="text" class="form-control @error('ups_account_number') is-invalid @enderror" 
                                       id="ups_account_number" name="ups_account_number" 
                                       value="{{ old('ups_account_number', $carrierData['api_settings']['account_number'] ?? '') }}">
                                @error('ups_account_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                        @elseif($carrier === 'usps')
                            <div class="mb-3">
                                <label for="usps_user_id" class="form-label">USPS User ID <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('usps_user_id') is-invalid @enderror" 
                                       id="usps_user_id" name="usps_user_id" 
                                       value="{{ old('usps_user_id', $carrierData['api_settings']['user_id'] ?? '') }}" required>
                                @error('usps_user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="usps_password" class="form-label">USPS Password</label>
                                <input type="password" class="form-control @error('usps_password') is-invalid @enderror" 
                                       id="usps_password" name="usps_password" 
                                       value="{{ old('usps_password', $carrierData['api_settings']['password'] ?? '') }}">
                                @error('usps_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                        @else
                            <div class="mb-3">
                                <label for="api_key" class="form-label">API Key <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('api_key') is-invalid @enderror" 
                                       id="api_key" name="api_key" 
                                       value="{{ old('api_key', $carrierData['api_settings']['api_key'] ?? '') }}" required>
                                @error('api_key')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="api_secret" class="form-label">API Secret <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('api_secret') is-invalid @enderror" 
                                       id="api_secret" name="api_secret" 
                                       value="{{ old('api_secret', $carrierData['api_settings']['api_secret'] ?? '') }}" required>
                                @error('api_secret')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="account_number" class="form-label">Account Number</label>
                                <input type="text" class="form-control @error('account_number') is-invalid @enderror" 
                                       id="account_number" name="account_number" 
                                       value="{{ old('account_number', $carrierData['api_settings']['account_number'] ?? '') }}">
                                @error('account_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                        
                        <div class="mb-3">
                            <label for="test_mode" class="form-label">Environment</label>
                            <select class="form-select @error('test_mode') is-invalid @enderror" 
                                    id="test_mode" name="test_mode">
                                <option value="1" {{ old('test_mode', $carrierData['api_settings']['test_mode'] ?? true) ? 'selected' : '' }}>Test/Sandbox</option>
                                <option value="0" {{ old('test_mode', $carrierData['api_settings']['test_mode'] ?? true) == '0' ? 'selected' : '' }}>Production/Live</option>
                            </select>
                            @error('test_mode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                       {{ old('is_active', $carrierData['status'] === 'active') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Enable {{ ucfirst($carrierData['name']) }} Carrier
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.shipping.carriers') }}" class="btn btn-secondary me-2">Cancel</a>
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
                    <h5 class="card-title mb-0">Carrier Information</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-1"></i> {{ ucfirst($carrierData['name']) }} Setup</h6>
                        <p class="mb-2">Configure your {{ ucfirst($carrierData['name']) }} carrier settings:</p>
                        <ul class="mb-0">
                            @if($carrier === 'dhl')
                                <li>Get your API credentials from DHL Developer Portal</li>
                                <li>Use test credentials for development</li>
                                <li>Switch to live credentials for production</li>
                            @elseif($carrier === 'fedex')
                                <li>Create app in FedEx Developer Center</li>
                                <li>Use sandbox for testing</li>
                                <li>Switch to live for production</li>
                            @elseif($carrier === 'ups')
                                <li>Get credentials from UPS Developer Portal</li>
                                <li>Use test mode for development</li>
                                <li>Enable live mode for production</li>
                            @elseif($carrier === 'usps')
                                <li>Register for USPS Web Tools API</li>
                                <li>Use test environment for development</li>
                                <li>Switch to production for live shipping</li>
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
                            <li>Never share your API credentials</li>
                            <li>Use environment variables</li>
                            <li>Test thoroughly before going live</li>
                            <li>Monitor shipping rates regularly</li>
                        </ul>
                    </div>
                    
                    <div class="alert alert-success">
                        <h6><i class="fas fa-check-circle me-1"></i> Features</h6>
                        <ul class="mb-0">
                            <li>Real-time shipping rates</li>
                            <li>Package tracking</li>
                            <li>Label generation</li>
                            <li>Delivery confirmation</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
