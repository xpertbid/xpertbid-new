<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>KYC Details - {{ ucfirst($kycDocument->kyc_type) }} - XpertBid</title>
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
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .kyc-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 2rem;
            margin-bottom: 2rem;
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

        .status-badge {
            font-size: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-weight: 600;
        }

        .status-pending {
            background: rgba(255, 193, 7, 0.2);
            color: #856404;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }

        .status-approved {
            background: rgba(40, 167, 69, 0.2);
            color: #155724;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }

        .status-rejected {
            background: rgba(220, 53, 69, 0.2);
            color: #721c24;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        .status-under-review {
            background: rgba(23, 162, 184, 0.2);
            color: #0c5460;
            border: 1px solid rgba(23, 162, 184, 0.3);
        }

        .section-header {
            background: linear-gradient(135deg, var(--primary-color), #5ba3d4);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: var(--border-radius);
            margin: 2rem 0 1.5rem 0;
            font-weight: 600;
        }

        .info-card {
            background: rgba(23, 162, 184, 0.1);
            border: 1px solid rgba(23, 162, 184, 0.2);
            border-radius: var(--border-radius);
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .warning-card {
            background: rgba(255, 193, 7, 0.1);
            border: 1px solid rgba(255, 193, 7, 0.2);
            border-radius: var(--border-radius);
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .success-card {
            background: rgba(40, 167, 69, 0.1);
            border: 1px solid rgba(40, 167, 69, 0.2);
            border-radius: var(--border-radius);
            padding: 1rem;
            margin-bottom: 1rem;
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

        .btn-outline-primary {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            border-radius: var(--border-radius);
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            transform: translateY(-2px);
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
                <h1><i class="fas fa-id-card me-2"></i>KYC Document Details</h1>
                <div class="d-flex justify-content-center align-items-center mt-3">
                    <span class="status-badge status-{{ $kycDocument->status }}">
                        {{ ucfirst(str_replace('_', ' ', $kycDocument->status)) }}
                    </span>
                    <span class="ms-3 badge bg-info">{{ $kycDocument->kyc_type_text }}</span>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($kycDocument->status === 'rejected')
                <div class="warning-card">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>KYC Rejected</h6>
                    <p class="mb-2">{{ $kycDocument->rejection_reason }}</p>
                    <p class="mb-0">Please review the feedback and resubmit your KYC document.</p>
                </div>
            @elseif($kycDocument->status === 'approved')
                <div class="success-card">
                    <h6><i class="fas fa-check-circle me-2"></i>KYC Approved</h6>
                    <p class="mb-0">Congratulations! Your KYC verification has been approved. You now have full access to all platform features.</p>
                </div>
            @elseif($kycDocument->status === 'pending')
                <div class="info-card">
                    <h6><i class="fas fa-clock me-2"></i>Under Review</h6>
                    <p class="mb-0">Your KYC document is currently being reviewed by our team. You will be notified once the review is complete.</p>
                </div>
            @elseif($kycDocument->status === 'under_review')
                <div class="info-card">
                    <h6><i class="fas fa-eye me-2"></i>Under Detailed Review</h6>
                    <p class="mb-0">Your KYC document is under detailed review. This may take additional time.</p>
                </div>
            @endif

            <div class="row">
                <div class="col-md-8">
                    <!-- Basic Information -->
                    <div class="section-header">
                        <i class="fas fa-user me-2"></i>Basic Information
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Full Name:</strong></td>
                                    <td>{{ $kycDocument->full_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $kycDocument->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Phone:</strong></td>
                                    <td>{{ $kycDocument->phone_number }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Address:</strong></td>
                                    <td>{{ $kycDocument->address ?: 'Not provided' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>City:</strong></td>
                                    <td>{{ $kycDocument->city ?: 'Not provided' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>State:</strong></td>
                                    <td>{{ $kycDocument->state ?: 'Not provided' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($kycDocument->kyc_type === 'vendor')
                        <!-- Vendor Information -->
                        <div class="section-header">
                            <i class="fas fa-store me-2"></i>Business Information
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Business Name:</strong></td>
                                        <td>{{ $kycDocument->business_name ?: 'Not provided' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>NTN Number:</strong></td>
                                        <td>{{ $kycDocument->ntn_number ?: 'Not provided' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Business Type:</strong></td>
                                        <td>{{ $kycDocument->business_type ?: 'Not provided' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Business Address:</strong></td>
                                        <td>{{ $kycDocument->business_address ?: 'Not provided' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tax Number:</strong></td>
                                        <td>{{ $kycDocument->tax_number ?: 'Not provided' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    @endif

                    @if($kycDocument->kyc_type === 'property')
                        <!-- Property Information -->
                        <div class="section-header">
                            <i class="fas fa-home me-2"></i>Property Information
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Property Type:</strong></td>
                                        <td>{{ $kycDocument->property_type ?: 'Not provided' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Property Location:</strong></td>
                                        <td>{{ $kycDocument->property_location ?: 'Not provided' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Property Size:</strong></td>
                                        <td>{{ $kycDocument->property_size ?: 'Not provided' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Ownership Type:</strong></td>
                                        <td>{{ $kycDocument->property_ownership ?: 'Not provided' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Description:</strong></td>
                                        <td>{{ $kycDocument->property_description ?: 'Not provided' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    @endif

                    @if($kycDocument->kyc_type === 'vehicle')
                        <!-- Vehicle Information -->
                        <div class="section-header">
                            <i class="fas fa-car me-2"></i>Vehicle Information
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Vehicle Type:</strong></td>
                                        <td>{{ $kycDocument->vehicle_type ?: 'Not provided' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Make:</strong></td>
                                        <td>{{ $kycDocument->vehicle_make ?: 'Not provided' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Model:</strong></td>
                                        <td>{{ $kycDocument->vehicle_model ?: 'Not provided' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Year:</strong></td>
                                        <td>{{ $kycDocument->vehicle_year ?: 'Not provided' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>VIN Number:</strong></td>
                                        <td>{{ $kycDocument->vehicle_vin ?: 'Not provided' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Registration Number:</strong></td>
                                        <td>{{ $kycDocument->vehicle_registration_number ?: 'Not provided' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    @endif

                    @if($kycDocument->kyc_type === 'auction')
                        <!-- Auction Information -->
                        <div class="section-header">
                            <i class="fas fa-gavel me-2"></i>Auction Information
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Auction Type:</strong></td>
                                        <td>{{ $kycDocument->auction_type ?: 'Not provided' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Item Category:</strong></td>
                                        <td>{{ $kycDocument->item_category ?: 'Not provided' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Item Condition:</strong></td>
                                        <td>{{ $kycDocument->item_condition ?: 'Not provided' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Estimated Value:</strong></td>
                                        <td>{{ $kycDocument->estimated_value ?: 'Not provided' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Description:</strong></td>
                                        <td>{{ $kycDocument->item_description ?: 'Not provided' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    @endif

                    <!-- Review Information -->
                    @if($kycDocument->reviewed_at)
                        <div class="section-header">
                            <i class="fas fa-clipboard-check me-2"></i>Review Information
                        </div>

                        <div class="info-card">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Reviewed On:</strong> {{ $kycDocument->reviewed_at->format('M d, Y H:i') }}</p>
                                    <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $kycDocument->status)) }}</p>
                                </div>
                                <div class="col-md-6">
                                    @if($kycDocument->admin_notes)
                                        <p><strong>Admin Notes:</strong></p>
                                        <p class="mb-0">{{ $kycDocument->admin_notes }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-md-4">
                    <!-- Actions -->
                    <div class="kyc-card">
                        <h5><i class="fas fa-cogs me-2"></i>Actions</h5>
                        <div class="d-grid gap-2">
                            @if(in_array($kycDocument->status, ['pending', 'rejected']))
                                <a href="{{ route('kyc.edit', $kycDocument) }}" class="btn btn-primary">
                                    <i class="fas fa-edit me-2"></i>Edit KYC
                                </a>
                            @endif
                            
                            <a href="{{ route('kyc.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-2"></i>Back to KYC
                            </a>
                        </div>
                    </div>

                    <!-- Document Management -->
                    <div class="kyc-card">
                        <h5><i class="fas fa-file-alt me-2"></i>Documents ({{ $kycDocument->getDocumentCount() }})</h5>
                        
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
                                        @if(in_array($kycDocument->status, ['pending', 'rejected']))
                                            <button class="btn btn-outline-danger" onclick="removeDocument({{ $kycDocument->id }}, {{ $index }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <p class="text-muted text-center">No documents uploaded</p>
                        @endif

                        @if(in_array($kycDocument->status, ['pending', 'rejected']))
                            <hr>
                            <div class="document-upload" onclick="document.getElementById('newDocument').click()">
                                <i class="fas fa-plus fa-2x text-muted mb-2"></i>
                                <p class="mb-0">Add Document</p>
                                <input type="file" id="newDocument" style="display: none;" 
                                       accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Document upload
        document.getElementById('newDocument').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('document', file);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch(`/kyc/{{ $kycDocument->id }}/upload-document`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Success!', data.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error', 'Failed to upload document', 'error');
            });
        });

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
    </script>
</body>
</html>
