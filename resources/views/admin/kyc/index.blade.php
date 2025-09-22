@extends('admin.layouts.app')

@section('title', 'KYC Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">KYC Management</h1>
            <p class="text-muted mb-0">Manage Know Your Customer documents and verifications</p>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('admin.kyc.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>
                    Add KYC Document
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total KYC</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Approved</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['approved'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Rejected</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['rejected'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.kyc.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="type" class="form-label">KYC Type</label>
                    <select class="form-select" id="type" name="type">
                        <option value="">All Types</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="search" class="form-label">Search User</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Search by name or email...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- KYC Documents Table -->
    <div class="card">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">KYC Documents</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Documents</th>
                            <th>Submitted</th>
                            <th>Reviewed</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kycDocuments as $kyc)
                        <tr>
                            <td>{{ $kyc->id }}</td>
                            <td>
                                <div>
                                    <strong>{{ $kyc->user->name }}</strong><br>
                                    <small class="text-muted">{{ $kyc->user->email }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $kyc->kyc_type_text }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $kyc->status_badge }}">
                                    {{ $kyc->status_text }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $kyc->getDocumentCount() }} files</span>
                            </td>
                            <td>{{ $kyc->created_at->format('M d, Y') }}</td>
                            <td>
                                @if($kyc->reviewed_at)
                                    {{ $kyc->reviewed_at->format('M d, Y') }}<br>
                                    <small class="text-muted">by {{ $kyc->reviewer->name ?? 'Admin' }}</small>
                                @else
                                    <span class="text-muted">Not reviewed</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.kyc.show', $kyc) }}" class="btn btn-outline-primary" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.kyc.edit', $kyc) }}" class="btn btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($kyc->status == 'pending')
                                        <button class="btn btn-outline-success" title="Approve" onclick="approveKyc({{ $kyc->id }})">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn btn-outline-danger" title="Reject" onclick="rejectKyc({{ $kyc->id }})">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                    <button class="btn btn-outline-danger" title="Delete" onclick="deleteKyc({{ $kyc->id }}, '{{ $kyc->user->name }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-file-alt fa-3x mb-3"></i><br>
                                No KYC documents found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($kycDocuments->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $kycDocuments->links() }}
                </div>
            @endif
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
</script>
@endsection
@endsection
