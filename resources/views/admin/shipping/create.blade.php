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
                        <li class="breadcrumb-item active">Create Shipping Zone</li>
                    </ol>
                </div>
                <h4 class="page-title">Create Shipping Zone</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Shipping Zone Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.shipping.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Zone Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="countries" class="form-label">Countries <span class="text-danger">*</span></label>
                                    <select class="form-select @error('countries') is-invalid @enderror" 
                                            id="countries" name="countries[]" multiple required>
                                        <option value="US" {{ in_array('US', old('countries', [])) ? 'selected' : '' }}>United States</option>
                                        <option value="CA" {{ in_array('CA', old('countries', [])) ? 'selected' : '' }}>Canada</option>
                                        <option value="GB" {{ in_array('GB', old('countries', [])) ? 'selected' : '' }}>United Kingdom</option>
                                        <option value="DE" {{ in_array('DE', old('countries', [])) ? 'selected' : '' }}>Germany</option>
                                        <option value="FR" {{ in_array('FR', old('countries', [])) ? 'selected' : '' }}>France</option>
                                        <option value="AU" {{ in_array('AU', old('countries', [])) ? 'selected' : '' }}>Australia</option>
                                    </select>
                                    @error('countries')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="shipping_method" class="form-label">Shipping Method <span class="text-danger">*</span></label>
                                    <select class="form-select @error('shipping_method') is-invalid @enderror" 
                                            id="shipping_method" name="shipping_method" required>
                                        <option value="">Select Method</option>
                                        <option value="flat_rate" {{ old('shipping_method') == 'flat_rate' ? 'selected' : '' }}>Flat Rate</option>
                                        <option value="free_shipping" {{ old('shipping_method') == 'free_shipping' ? 'selected' : '' }}>Free Shipping</option>
                                        <option value="local_pickup" {{ old('shipping_method') == 'local_pickup' ? 'selected' : '' }}>Local Pickup</option>
                                        <option value="weight_based" {{ old('shipping_method') == 'weight_based' ? 'selected' : '' }}>Weight Based</option>
                                    </select>
                                    @error('shipping_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cost" class="form-label">Cost ($)</label>
                                    <input type="number" class="form-control @error('cost') is-invalid @enderror" 
                                           id="cost" name="cost" value="{{ old('cost', 0) }}" 
                                           min="0" step="0.01">
                                    @error('cost')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Enable this shipping zone
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.shipping.index') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Create Shipping Zone
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Help & Tips</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-1"></i> Shipping Zone Guidelines</h6>
                        <ul class="mb-0">
                            <li>Create zones based on geographic regions</li>
                            <li>Use flat rate for simple pricing</li>
                            <li>Free shipping can boost conversions</li>
                            <li>Weight-based shipping for variable costs</li>
                        </ul>
                    </div>
                    
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle me-1"></i> Important Notes</h6>
                        <ul class="mb-0">
                            <li>Customers can only be in one zone</li>
                            <li>Test shipping calculations thoroughly</li>
                            <li>Consider international shipping costs</li>
                            <li>Update rates regularly</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
