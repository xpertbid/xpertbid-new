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
                        <li class="breadcrumb-item active">Create New Carrier</li>
                    </ol>
                </div>
                <h4 class="page-title">Create New Shipping Carrier</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Carrier Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.shipping.store-carrier') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Carrier Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="code" class="form-label">Carrier Code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                           id="code" name="code" value="{{ old('code') }}" required>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Unique identifier for the carrier (e.g., fedex, dhl, ups)</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            @include('admin.components.shopify-editor', [
                                'name' => 'description',
                                'value' => old('description'),
                                'height' => 200,
                                'placeholder' => 'Describe this shipping carrier...'
                            ])
                        </div>

                        <div class="mb-3">
                            <label for="logo_url" class="form-label">Logo URL</label>
                            <input type="url" class="form-control @error('logo_url') is-invalid @enderror" 
                                   id="logo_url" name="logo_url" value="{{ old('logo_url') }}">
                            @error('logo_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="base_rate" class="form-label">Base Rate <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" step="0.01" min="0" class="form-control @error('base_rate') is-invalid @enderror" 
                                               id="base_rate" name="base_rate" value="{{ old('base_rate', '10.00') }}" required>
                                        @error('base_rate')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text">Base shipping rate in USD</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" min="0" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', '10') }}">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Lower numbers appear first</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Supported Countries</label>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="supported_countries[]" value="US" 
                                               {{ in_array('US', old('supported_countries', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label">United States</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="supported_countries[]" value="CA" 
                                               {{ in_array('CA', old('supported_countries', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label">Canada</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="supported_countries[]" value="GB" 
                                               {{ in_array('GB', old('supported_countries', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label">United Kingdom</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="supported_countries[]" value="DE" 
                                               {{ in_array('DE', old('supported_countries', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label">Germany</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="supported_countries[]" value="FR" 
                                               {{ in_array('FR', old('supported_countries', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label">France</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="supported_countries[]" value="IT" 
                                               {{ in_array('IT', old('supported_countries', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label">Italy</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="supported_countries[]" value="ES" 
                                               {{ in_array('ES', old('supported_countries', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label">Spain</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="supported_countries[]" value="AU" 
                                               {{ in_array('AU', old('supported_countries', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label">Australia</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Supported Services</label>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="supported_services[]" value="standard" 
                                               {{ in_array('standard', old('supported_services', ['standard'])) ? 'checked' : '' }}>
                                        <label class="form-check-label">Standard</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="supported_services[]" value="express" 
                                               {{ in_array('express', old('supported_services', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label">Express</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="supported_services[]" value="overnight" 
                                               {{ in_array('overnight', old('supported_services', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label">Overnight</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="supported_services[]" value="ground" 
                                               {{ in_array('ground', old('supported_services', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label">Ground</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active Carrier
                                        </label>
                                    </div>
                                    <div class="form-text">Enable this carrier for shipping calculations</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_integrated" name="is_integrated" 
                                               {{ old('is_integrated', false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_integrated">
                                            API Integrated
                                        </label>
                                    </div>
                                    <div class="form-text">Carrier has API integration configured</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.shipping.carriers') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Create Carrier
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Carrier Setup Guide</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-1"></i> Basic Information</h6>
                        <ul class="mb-0">
                            <li>Choose a unique carrier code</li>
                            <li>Set appropriate base shipping rate</li>
                            <li>Select supported countries</li>
                            <li>Choose available services</li>
                        </ul>
                    </div>
                    
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle me-1"></i> Important Notes</h6>
                        <ul class="mb-0">
                            <li>Carrier code must be unique</li>
                            <li>Base rate is the minimum charge</li>
                            <li>Configure API settings separately</li>
                            <li>Test rates before going live</li>
                        </ul>
                    </div>
                    
                    <div class="alert alert-success">
                        <h6><i class="fas fa-check-circle me-1"></i> Next Steps</h6>
                        <ol class="mb-0">
                            <li>Save the carrier</li>
                            <li>Configure API settings</li>
                            <li>Test shipping rates</li>
                            <li>Enable for production</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
