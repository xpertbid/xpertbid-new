@extends('admin.layouts.app')

@section('title', 'Create KYC Document')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Create KYC Document</h1>
            <p class="text-muted mb-0">Add a new KYC document for user verification</p>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('admin.kyc.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>
                Back to KYC List
            </a>
        </div>
    </div>

    <!-- KYC Form -->
    <div class="card">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">KYC Information</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.kyc.store') }}">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="user_id" class="form-label">User *</label>
                            <select class="form-select" id="user_id" name="user_id" required>
                                <option value="">Select User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="kyc_type" class="form-label">KYC Type *</label>
                            <select class="form-select" id="kyc_type" name="kyc_type" required>
                                <option value="">Select Type</option>
                                @foreach($types as $type)
                                    <option value="{{ $type }}" {{ old('kyc_type') == $type ? 'selected' : '' }}>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kyc_type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Full Name *</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" 
                                   value="{{ old('full_name') }}" required>
                            @error('full_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ old('email') }}" required>
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Phone Number *</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" 
                                   value="{{ old('phone_number') }}" required>
                            @error('phone_number')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city" name="city" 
                                   value="{{ old('city') }}">
                            @error('city')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="state" class="form-label">State</label>
                            <input type="text" class="form-control" id="state" name="state" 
                                   value="{{ old('state') }}">
                            @error('state')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" class="form-control" id="country" name="country" 
                                   value="{{ old('country') }}">
                            @error('country')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="postal_code" class="form-label">Postal Code</label>
                            <input type="text" class="form-control" id="postal_code" name="postal_code" 
                                   value="{{ old('postal_code') }}">
                            @error('postal_code')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Vendor Specific Fields -->
                <div id="vendor-fields" style="display: none;">
                    <hr>
                    <h6 class="text-primary mb-3">Vendor Information</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="business_name" class="form-label">Business Name</label>
                                <input type="text" class="form-control" id="business_name" name="business_name" 
                                       value="{{ old('business_name') }}">
                                @error('business_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="ntn_number" class="form-label">NTN Number</label>
                                <input type="text" class="form-control" id="ntn_number" name="ntn_number" 
                                       value="{{ old('ntn_number') }}">
                                @error('ntn_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="business_address" class="form-label">Business Address</label>
                        <textarea class="form-control" id="business_address" name="business_address" rows="3">{{ old('business_address') }}</textarea>
                        @error('business_address')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.kyc.index') }}" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>
                        Create KYC Document
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
document.getElementById('kyc_type').addEventListener('change', function() {
    const vendorFields = document.getElementById('vendor-fields');
    if (this.value === 'vendor') {
        vendorFields.style.display = 'block';
    } else {
        vendorFields.style.display = 'none';
    }
});

// Show vendor fields if type is already selected
if (document.getElementById('kyc_type').value === 'vendor') {
    document.getElementById('vendor-fields').style.display = 'block';
}
</script>
@endsection
@endsection
