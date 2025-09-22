<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit KYC - {{ ucfirst($kycDocument->kyc_type) }} - XpertBid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary-color: #43ACE9;
            --secondary-color: #606060;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --light-bg: #f8f9fa;
            --white: #ffffff;
            --border-radius: 12px;
            --shadow: 0 2px 10px rgba(0,0,0,0.08);
            --shadow-hover: 0 8px 25px rgba(0,0,0,0.15);
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
        }

        .kyc-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .kyc-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .kyc-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .kyc-header h1 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .kyc-header p {
            color: var(--secondary-color);
            margin-bottom: 0;
        }

        .form-label {
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border-radius: var(--border-radius);
            border: 2px solid #e9ecef;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(67, 172, 233, 0.25);
        }

        .required-field::after {
            content: " *";
            color: var(--danger-color);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), #5ba3d4);
            border: none;
            border-radius: var(--border-radius);
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
        }

        .btn-secondary {
            border-radius: var(--border-radius);
            padding: 0.75rem 2rem;
            font-weight: 600;
        }

        .section-header {
            background: linear-gradient(135deg, var(--primary-color), #5ba3d4);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: var(--border-radius);
            margin: 2rem 0 1.5rem 0;
            font-weight: 600;
        }

        .info-box {
            background: rgba(23, 162, 184, 0.1);
            border: 1px solid rgba(23, 162, 184, 0.2);
            border-radius: var(--border-radius);
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .warning-box {
            background: rgba(255, 193, 7, 0.1);
            border: 1px solid rgba(255, 193, 7, 0.2);
            border-radius: var(--border-radius);
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .document-item {
            border: 1px solid #dee2e6;
            border-radius: var(--border-radius);
            padding: 1rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .document-item:hover {
            box-shadow: var(--shadow);
        }

        .document-upload {
            border: 2px dashed #dee2e6;
            border-radius: var(--border-radius);
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .document-upload:hover {
            border-color: var(--primary-color);
            background: rgba(67, 172, 233, 0.05);
        }

        .document-upload.dragover {
            border-color: var(--primary-color);
            background: rgba(67, 172, 233, 0.1);
        }
    </style>
</head>
<body>
    <div class="kyc-container">
        <div class="kyc-card">
            <div class="kyc-header">
                <h1><i class="fas fa-edit me-2"></i>Edit KYC Document</h1>
                <p>Update your {{ ucfirst($kycDocument->kyc_type) }} verification information</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($kycDocument->status === 'rejected' && $kycDocument->rejection_reason)
                <div class="warning-box">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Previous Rejection</h6>
                    <p class="mb-0"><strong>Reason:</strong> {{ $kycDocument->rejection_reason }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('kyc.update', $kycDocument) }}">
                @csrf
                @method('PUT')

                <!-- Basic Information Section -->
                <div class="section-header">
                    <i class="fas fa-user me-2"></i>Basic Information
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="full_name" class="form-label required-field">Full Name</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" 
                                   value="{{ old('full_name', $kycDocument->full_name) }}" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label required-field">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ old('email', $kycDocument->email) }}" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="phone_number" class="form-label required-field">Phone Number</label>
                            <input type="tel" class="form-control" id="phone_number" name="phone_number" 
                                   value="{{ old('phone_number', $kycDocument->phone_number) }}" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address" 
                                   value="{{ old('address', $kycDocument->address) }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city" name="city" 
                                   value="{{ old('city', $kycDocument->city) }}">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="state" class="form-label">State</label>
                            <input type="text" class="form-control" id="state" name="state" 
                                   value="{{ old('state', $kycDocument->state) }}">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" class="form-control" id="country" name="country" 
                                   value="{{ old('country', $kycDocument->country) }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="postal_code" class="form-label">Postal Code</label>
                            <input type="text" class="form-control" id="postal_code" name="postal_code" 
                                   value="{{ old('postal_code', $kycDocument->postal_code) }}">
                        </div>
                    </div>
                </div>

                @if($kycDocument->kyc_type === 'vendor')
                    <!-- Vendor Specific Fields -->
                    <div class="section-header">
                        <i class="fas fa-store me-2"></i>Business Information
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="business_name" class="form-label required-field">Business Name</label>
                                <input type="text" class="form-control" id="business_name" name="business_name" 
                                       value="{{ old('business_name', $kycDocument->business_name) }}" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="ntn_number" class="form-label required-field">NTN Number</label>
                                <input type="text" class="form-control" id="ntn_number" name="ntn_number" 
                                       value="{{ old('ntn_number', $kycDocument->ntn_number) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="business_address" class="form-label required-field">Business Address</label>
                        <textarea class="form-control" id="business_address" name="business_address" 
                                  rows="3" required>{{ old('business_address', $kycDocument->business_address) }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="business_type" class="form-label">Business Type</label>
                                <select class="form-select" id="business_type" name="business_type">
                                    <option value="">Select Business Type</option>
                                    <option value="retail" {{ old('business_type', $kycDocument->business_type) == 'retail' ? 'selected' : '' }}>Retail</option>
                                    <option value="wholesale" {{ old('business_type', $kycDocument->business_type) == 'wholesale' ? 'selected' : '' }}>Wholesale</option>
                                    <option value="manufacturing" {{ old('business_type', $kycDocument->business_type) == 'manufacturing' ? 'selected' : '' }}>Manufacturing</option>
                                    <option value="services" {{ old('business_type', $kycDocument->business_type) == 'services' ? 'selected' : '' }}>Services</option>
                                    <option value="other" {{ old('business_type', $kycDocument->business_type) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tax_number" class="form-label">Tax Number</label>
                                <input type="text" class="form-control" id="tax_number" name="tax_number" 
                                       value="{{ old('tax_number', $kycDocument->tax_number) }}">
                            </div>
                        </div>
                    </div>
                @endif

                @if($kycDocument->kyc_type === 'property')
                    <!-- Property Specific Fields -->
                    <div class="section-header">
                        <i class="fas fa-home me-2"></i>Property Information
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="property_type" class="form-label">Property Type</label>
                                <select class="form-select" id="property_type" name="property_type">
                                    <option value="">Select Property Type</option>
                                    <option value="residential" {{ old('property_type', $kycDocument->property_type) == 'residential' ? 'selected' : '' }}>Residential</option>
                                    <option value="commercial" {{ old('property_type', $kycDocument->property_type) == 'commercial' ? 'selected' : '' }}>Commercial</option>
                                    <option value="industrial" {{ old('property_type', $kycDocument->property_type) == 'industrial' ? 'selected' : '' }}>Industrial</option>
                                    <option value="land" {{ old('property_type', $kycDocument->property_type) == 'land' ? 'selected' : '' }}>Land</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="property_location" class="form-label">Property Location</label>
                                <input type="text" class="form-control" id="property_location" name="property_location" 
                                       value="{{ old('property_location', $kycDocument->property_location) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="property_size" class="form-label">Property Size</label>
                                <input type="text" class="form-control" id="property_size" name="property_size" 
                                       value="{{ old('property_size', $kycDocument->property_size) }}" placeholder="e.g., 1000 sq ft">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="property_ownership" class="form-label">Ownership Type</label>
                                <select class="form-select" id="property_ownership" name="property_ownership">
                                    <option value="">Select Ownership</option>
                                    <option value="owned" {{ old('property_ownership', $kycDocument->property_ownership) == 'owned' ? 'selected' : '' }}>Owned</option>
                                    <option value="rented" {{ old('property_ownership', $kycDocument->property_ownership) == 'rented' ? 'selected' : '' }}>Rented</option>
                                    <option value="leased" {{ old('property_ownership', $kycDocument->property_ownership) == 'leased' ? 'selected' : '' }}>Leased</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="property_description" class="form-label">Property Description</label>
                        <textarea class="form-control" id="property_description" name="property_description" 
                                  rows="3">{{ old('property_description', $kycDocument->property_description) }}</textarea>
                    </div>
                @endif

                @if($kycDocument->kyc_type === 'vehicle')
                    <!-- Vehicle Specific Fields -->
                    <div class="section-header">
                        <i class="fas fa-car me-2"></i>Vehicle Information
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="vehicle_type" class="form-label">Vehicle Type</label>
                                <select class="form-select" id="vehicle_type" name="vehicle_type">
                                    <option value="">Select Vehicle Type</option>
                                    <option value="car" {{ old('vehicle_type', $kycDocument->vehicle_type) == 'car' ? 'selected' : '' }}>Car</option>
                                    <option value="motorcycle" {{ old('vehicle_type', $kycDocument->vehicle_type) == 'motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                                    <option value="truck" {{ old('vehicle_type', $kycDocument->vehicle_type) == 'truck' ? 'selected' : '' }}>Truck</option>
                                    <option value="bus" {{ old('vehicle_type', $kycDocument->vehicle_type) == 'bus' ? 'selected' : '' }}>Bus</option>
                                    <option value="boat" {{ old('vehicle_type', $kycDocument->vehicle_type) == 'boat' ? 'selected' : '' }}>Boat</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="vehicle_make" class="form-label">Vehicle Make</label>
                                <input type="text" class="form-control" id="vehicle_make" name="vehicle_make" 
                                       value="{{ old('vehicle_make', $kycDocument->vehicle_make) }}" placeholder="e.g., Toyota, Honda">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="vehicle_model" class="form-label">Vehicle Model</label>
                                <input type="text" class="form-control" id="vehicle_model" name="vehicle_model" 
                                       value="{{ old('vehicle_model', $kycDocument->vehicle_model) }}">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="vehicle_year" class="form-label">Vehicle Year</label>
                                <input type="number" class="form-control" id="vehicle_year" name="vehicle_year" 
                                       value="{{ old('vehicle_year', $kycDocument->vehicle_year) }}" min="1900" max="{{ date('Y') + 1 }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="vehicle_vin" class="form-label">VIN Number</label>
                                <input type="text" class="form-control" id="vehicle_vin" name="vehicle_vin" 
                                       value="{{ old('vehicle_vin', $kycDocument->vehicle_vin) }}">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="vehicle_registration_number" class="form-label">Registration Number</label>
                                <input type="text" class="form-control" id="vehicle_registration_number" name="vehicle_registration_number" 
                                       value="{{ old('vehicle_registration_number', $kycDocument->vehicle_registration_number) }}">
                            </div>
                        </div>
                    </div>
                @endif

                @if($kycDocument->kyc_type === 'auction')
                    <!-- Auction Specific Fields -->
                    <div class="section-header">
                        <i class="fas fa-gavel me-2"></i>Auction Information
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="auction_type" class="form-label">Auction Type</label>
                                <select class="form-select" id="auction_type" name="auction_type">
                                    <option value="">Select Auction Type</option>
                                    <option value="live" {{ old('auction_type', $kycDocument->auction_type) == 'live' ? 'selected' : '' }}>Live Auction</option>
                                    <option value="online" {{ old('auction_type', $kycDocument->auction_type) == 'online' ? 'selected' : '' }}>Online Auction</option>
                                    <option value="sealed_bid" {{ old('auction_type', $kycDocument->auction_type) == 'sealed_bid' ? 'selected' : '' }}>Sealed Bid</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="item_category" class="form-label">Item Category</label>
                                <select class="form-select" id="item_category" name="item_category">
                                    <option value="">Select Category</option>
                                    <option value="art" {{ old('item_category', $kycDocument->item_category) == 'art' ? 'selected' : '' }}>Art & Antiques</option>
                                    <option value="jewelry" {{ old('item_category', $kycDocument->item_category) == 'jewelry' ? 'selected' : '' }}>Jewelry</option>
                                    <option value="collectibles" {{ old('item_category', $kycDocument->item_category) == 'collectibles' ? 'selected' : '' }}>Collectibles</option>
                                    <option value="electronics" {{ old('item_category', $kycDocument->item_category) == 'electronics' ? 'selected' : '' }}>Electronics</option>
                                    <option value="furniture" {{ old('item_category', $kycDocument->item_category) == 'furniture' ? 'selected' : '' }}>Furniture</option>
                                    <option value="other" {{ old('item_category', $kycDocument->item_category) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="item_condition" class="form-label">Item Condition</label>
                                <select class="form-select" id="item_condition" name="item_condition">
                                    <option value="">Select Condition</option>
                                    <option value="new" {{ old('item_condition', $kycDocument->item_condition) == 'new' ? 'selected' : '' }}>New</option>
                                    <option value="excellent" {{ old('item_condition', $kycDocument->item_condition) == 'excellent' ? 'selected' : '' }}>Excellent</option>
                                    <option value="good" {{ old('item_condition', $kycDocument->item_condition) == 'good' ? 'selected' : '' }}>Good</option>
                                    <option value="fair" {{ old('item_condition', $kycDocument->item_condition) == 'fair' ? 'selected' : '' }}>Fair</option>
                                    <option value="poor" {{ old('item_condition', $kycDocument->item_condition) == 'poor' ? 'selected' : '' }}>Poor</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="estimated_value" class="form-label">Estimated Value</label>
                                <input type="text" class="form-control" id="estimated_value" name="estimated_value" 
                                       value="{{ old('estimated_value', $kycDocument->estimated_value) }}" placeholder="e.g., $1000 - $5000">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="item_description" class="form-label">Item Description</label>
                        <textarea class="form-control" id="item_description" name="item_description" 
                                  rows="3">{{ old('item_description', $kycDocument->item_description) }}</textarea>
                    </div>
                @endif

                <!-- Current Documents -->
                <div class="section-header">
                    <i class="fas fa-file-alt me-2"></i>Current Documents
                </div>

                @if($kycDocument->documents && count($kycDocument->documents) > 0)
                    @foreach($kycDocument->documents as $index => $document)
                    <div class="document-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-file me-2"></i>
                                <strong>{{ $document['original_name'] }}</strong>
                                <br>
                                <small class="text-muted">{{ $document['file_type'] }}</small>
                            </div>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ Storage::url($document['path']) }}" target="_blank" class="btn btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button class="btn btn-outline-danger" onclick="removeDocument({{ $kycDocument->id }}, {{ $index }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="info-box">
                        <p class="mb-0 text-center">No documents uploaded yet.</p>
                    </div>
                @endif

                <!-- Add New Documents -->
                <div class="section-header">
                    <i class="fas fa-plus me-2"></i>Add New Documents
                </div>

                <div class="info-box">
                    <h6><i class="fas fa-info-circle me-2"></i>Supported Documents</h6>
                    <p class="mb-0">You can upload images (JPG, PNG), PDF documents, or Word documents. Maximum file size is 10MB per file.</p>
                </div>

                <div class="document-upload" onclick="document.getElementById('newDocuments').click()">
                    <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                    <h5>Upload New Documents</h5>
                    <p class="text-muted">Click here to select files or drag and drop</p>
                    <input type="file" id="newDocuments" name="new_documents[]" multiple 
                           accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" style="display: none;">
                </div>

                <div id="file-list" class="mt-3"></div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('kyc.show', $kycDocument) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update KYC
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // File upload handling
        document.getElementById('newDocuments').addEventListener('change', function(e) {
            const files = e.target.files;
            const fileList = document.getElementById('file-list');
            
            fileList.innerHTML = '';
            
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const fileItem = document.createElement('div');
                fileItem.className = 'alert alert-info d-flex justify-content-between align-items-center';
                fileItem.innerHTML = `
                    <div>
                        <i class="fas fa-file me-2"></i>
                        <strong>${file.name}</strong>
                        <small class="text-muted ms-2">(${(file.size / 1024 / 1024).toFixed(2)} MB)</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFile(this)">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                fileList.appendChild(fileItem);
            }
        });

        function removeFile(button) {
            button.parentElement.remove();
        }

        // Remove document
        function removeDocument(kycId, documentIndex) {
            Swal.fire({
                title: 'Remove Document',
                text: 'Are you sure you want to remove this document?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, remove it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/kyc/${kycId}/remove-document`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            document_index: documentIndex
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Removed!', data.message, 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error', 'Failed to remove document', 'error');
                    });
                }
            });
        }

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const requiredFields = document.querySelectorAll('.required-field');
            let isValid = true;
            
            requiredFields.forEach(field => {
                const input = field.parentElement.querySelector('input, select, textarea');
                if (input && !input.value.trim()) {
                    input.classList.add('is-invalid');
                    isValid = false;
                } else {
                    input.classList.remove('is-invalid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                Swal.fire('Validation Error', 'Please fill in all required fields.', 'error');
            }
        });
    </script>
</body>
</html>
