@extends('admin.layouts.app')

@section('title', 'KYC Document Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">KYC Document Details</h1>
            <p class="text-muted mb-0">View and manage KYC document information</p>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('admin.kyc.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>
                Back to KYC List
            </a>
        </div>
    </div>

    <div class="row">
        <!-- KYC Information -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">KYC Information</h6>
                    <span class="badge bg-{{ $kycDocument->status_badge }} fs-6">
                        {{ $kycDocument->status_text }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Basic Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>User:</strong></td>
                                    <td>{{ $kycDocument->user->name }} ({{ $kycDocument->user->email }})</td>
                                </tr>
                                <tr>
                                    <td><strong>KYC Type:</strong></td>
                                    <td><span class="badge bg-info">{{ $kycDocument->kyc_type_text }}</span></td>
                                </tr>
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
                            <h6 class="text-primary">Address Information</h6>
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
                                <tr>
                                    <td><strong>Country:</strong></td>
                                    <td>{{ $kycDocument->country ?: 'Not provided' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Postal Code:</strong></td>
                                    <td>{{ $kycDocument->postal_code ?: 'Not provided' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($kycDocument->kyc_type === 'vendor')
                    <hr>
                    <h6 class="text-primary">Vendor Information</h6>
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

                    @if($kycDocument->rejection_reason || $kycDocument->admin_notes)
                    <hr>
                    <h6 class="text-primary">Review Information</h6>
                    @if($kycDocument->rejection_reason)
                    <div class="alert alert-danger">
                        <strong>Rejection Reason:</strong><br>
                        {{ $kycDocument->rejection_reason }}
                    </div>
                    @endif
                    
                    @if($kycDocument->admin_notes)
                    <div class="alert alert-info">
                        <strong>Admin Notes:</strong><br>
                        {{ $kycDocument->admin_notes }}
                    </div>
                    @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions & Documents -->
        <div class="col-lg-4">
            <!-- Actions Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.kyc.edit', $kycDocument) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i>
                            Edit KYC
                        </a>
                        
                        @if($kycDocument->status === 'pending')
                            <button class="btn btn-success" onclick="approveKyc({{ $kycDocument->id }})">
                                <i class="fas fa-check me-1"></i>
                                Approve
                            </button>
                            <button class="btn btn-danger" onclick="rejectKyc({{ $kycDocument->id }})">
                                <i class="fas fa-times me-1"></i>
                                Reject
                            </button>
                            <button class="btn btn-info" onclick="setUnderReview({{ $kycDocument->id }})">
                                <i class="fas fa-eye me-1"></i>
                                Set Under Review
                            </button>
                        @endif
                        
                        <button class="btn btn-outline-danger" onclick="deleteKyc({{ $kycDocument->id }}, '{{ $kycDocument->user->name }}')">
                            <i class="fas fa-trash me-1"></i>
                            Delete
                        </button>
                    </div>
                </div>
            </div>

            <!-- Documents Card -->
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Documents</h6>
                </div>
                <div class="card-body">
                    @if($kycDocument->documents && count($kycDocument->documents) > 0)
                        @foreach($kycDocument->documents as $index => $document)
                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                            <div>
                                <i class="fas fa-file me-2"></i>
                                <small>{{ $document['original_name'] }}</small>
                            </div>
                            <div>
                                <a href="{{ Storage::url($document['path']) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-danger" onclick="removeDocument({{ $kycDocument->id }}, {{ $index }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center">No documents uploaded</p>
                    @endif
                    
                    <hr>
                    <form id="documentUploadForm" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="document" class="form-label">Upload Document</label>
                            <input type="file" class="form-control" id="document" name="document" 
                                   accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="fas fa-upload me-1"></i>
                            Upload
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
// Approve KYC
function approveKyc(id) {
    Swal.fire({
        title: 'Approve KYC Document',
        text: 'Are you sure you want to approve this KYC document?',
        icon: 'question',
        input: 'textarea',
        inputLabel: 'Admin Notes (Optional)',
        inputPlaceholder: 'Add any notes for the user...',
        showCancelButton: true,
        confirmButtonText: 'Yes, Approve',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/kyc/${id}/approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    admin_notes: result.value || null
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Approved!', data.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error', 'Failed to approve KYC document', 'error');
            });
        }
    });
}

// Reject KYC
function rejectKyc(id) {
    Swal.fire({
        title: 'Reject KYC Document',
        text: 'Are you sure you want to reject this KYC document?',
        icon: 'warning',
        input: 'textarea',
        inputLabel: 'Rejection Reason *',
        inputPlaceholder: 'Please provide a reason for rejection...',
        inputValidator: (value) => {
            if (!value) {
                return 'You need to provide a rejection reason!'
            }
        },
        showCancelButton: true,
        confirmButtonText: 'Yes, Reject',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/kyc/${id}/reject`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    rejection_reason: result.value
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Rejected!', data.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error', 'Failed to reject KYC document', 'error');
            });
        }
    });
}

// Set Under Review
function setUnderReview(id) {
    Swal.fire({
        title: 'Set Under Review',
        text: 'Set this KYC document under review?',
        icon: 'question',
        input: 'textarea',
        inputLabel: 'Admin Notes (Optional)',
        inputPlaceholder: 'Add any notes...',
        showCancelButton: true,
        confirmButtonText: 'Yes, Set Under Review',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/kyc/${id}/under-review`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    admin_notes: result.value || null
                })
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
                Swal.fire('Error', 'Failed to set under review', 'error');
            });
        }
    });
}

// Delete KYC
function deleteKyc(id, name) {
    Swal.fire({
        title: 'Are you sure?',
        text: `You are about to delete KYC document for "${name}". This action cannot be undone!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/kyc/${id}`;
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            form.appendChild(methodField);
            form.appendChild(csrfToken);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Document Upload
document.getElementById('documentUploadForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData();
    const fileInput = document.getElementById('document');
    
    if (!fileInput.files[0]) {
        Swal.fire('Error', 'Please select a file to upload', 'error');
        return;
    }
    
    formData.append('document', fileInput.files[0]);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    fetch(`/admin/kyc/{{ $kycDocument->id }}/upload-document`, {
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

// Remove Document
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
            fetch(`/admin/kyc/${kycId}/remove-document`, {
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
@endsection
@endsection
