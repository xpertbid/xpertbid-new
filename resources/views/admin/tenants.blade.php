@extends('admin.layouts.app')

@section('title', 'Tenants Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Tenants Management</h1>
            <p class="text-muted mb-0">Manage all tenants in your system</p>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-primary" onclick="addTenant()">
                    <i class="fas fa-plus me-1"></i>
                    Add Tenant
                </button>
            </div>
        </div>
    </div>

    <!-- Tenants Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-building me-2"></i>All Tenants
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Domain</th>
                            <th>Subdomain</th>
                            <th>Status</th>
                            <th>Plan</th>
                            <th>Vendor Limit</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tenants as $tenant)
                        <tr>
                            <td>{{ $tenant->id }}</td>
                            <td>
                                <strong>{{ $tenant->name }}</strong>
                            </td>
                            <td>
                                <code>{{ $tenant->domain }}</code>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $tenant->subdomain }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $tenant->status == 'active' ? 'success' : ($tenant->status == 'suspended' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($tenant->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ ucfirst($tenant->subscription_plan) }}</span>
                            </td>
                            <td>{{ $tenant->vendor_limit }}</td>
                            <td>{{ \Carbon\Carbon::parse($tenant->created_at)->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" title="View" onclick="viewTenant({{ $tenant->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-warning" title="Edit" onclick="editTenant({{ $tenant->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-danger" title="Delete" onclick="deleteTenant({{ $tenant->id }}, '{{ $tenant->name }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
// View Tenant Details
function viewTenant(id) {
    fetch(`/api/admin/tenants/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const tenant = data.data;
                Swal.fire({
                    title: tenant.name,
                    html: `
                        <div class="text-start">
                            <p><strong>Domain:</strong> ${tenant.domain}</p>
                            <p><strong>Subdomain:</strong> ${tenant.subdomain}</p>
                            <p><strong>Status:</strong> <span class="badge bg-${tenant.status === 'active' ? 'success' : 'warning'}">${tenant.status}</span></p>
                            <p><strong>Plan:</strong> <span class="badge bg-primary">${tenant.subscription_plan}</span></p>
                            <p><strong>Vendor Limit:</strong> ${tenant.vendor_limit}</p>
                            <p><strong>Product Limit:</strong> ${tenant.product_limit}</p>
                            <p><strong>Created:</strong> ${new Date(tenant.created_at).toLocaleDateString()}</p>
                        </div>
                    `,
                    showCloseButton: true,
                    showConfirmButton: false,
                    width: 600
                });
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        })
        .catch(error => {
            Swal.fire('Error', 'Failed to fetch tenant details', 'error');
        });
}

// Edit Tenant
function editTenant(id) {
    fetch(`/api/admin/tenants/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const tenant = data.data;
                Swal.fire({
                    title: 'Edit Tenant',
                    html: `
                        <form id="editForm">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" value="${tenant.name}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Domain</label>
                                <input type="text" class="form-control" id="domain" value="${tenant.domain}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Subdomain</label>
                                <input type="text" class="form-control" id="subdomain" value="${tenant.subdomain}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-control" id="status">
                                    <option value="active" ${tenant.status === 'active' ? 'selected' : ''}>Active</option>
                                    <option value="suspended" ${tenant.status === 'suspended' ? 'selected' : ''}>Suspended</option>
                                    <option value="pending" ${tenant.status === 'pending' ? 'selected' : ''}>Pending</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Subscription Plan</label>
                                <select class="form-control" id="subscription_plan">
                                    <option value="basic" ${tenant.subscription_plan === 'basic' ? 'selected' : ''}>Basic</option>
                                    <option value="premium" ${tenant.subscription_plan === 'premium' ? 'selected' : ''}>Premium</option>
                                    <option value="enterprise" ${tenant.subscription_plan === 'enterprise' ? 'selected' : ''}>Enterprise</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Vendor Limit</label>
                                <input type="number" class="form-control" id="vendor_limit" value="${tenant.vendor_limit}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Product Limit</label>
                                <input type="number" class="form-control" id="product_limit" value="${tenant.product_limit}" required>
                            </div>
                        </form>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Update',
                    cancelButtonText: 'Cancel',
                    preConfirm: () => {
                        const formData = {
                            name: document.getElementById('name').value,
                            domain: document.getElementById('domain').value,
                            subdomain: document.getElementById('subdomain').value,
                            status: document.getElementById('status').value,
                            subscription_plan: document.getElementById('subscription_plan').value,
                            vendor_limit: document.getElementById('vendor_limit').value,
                            product_limit: document.getElementById('product_limit').value
                        };

                        return fetch(`/api/admin/tenants/${id}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(formData)
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (!data.success) {
                                throw new Error(data.message);
                            }
                            return data;
                        });
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire('Success!', 'Tenant updated successfully', 'success').then(() => {
                            location.reload();
                        });
                    }
                }).catch(error => {
                    Swal.fire('Error', error.message, 'error');
                });
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        })
        .catch(error => {
            Swal.fire('Error', 'Failed to fetch tenant details', 'error');
        });
}

// Delete Tenant
function deleteTenant(id, name) {
    Swal.fire({
        title: 'Are you sure?',
        text: `You are about to delete tenant "${name}". This action cannot be undone!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/api/admin/tenants/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Deleted!', 'Tenant has been deleted successfully', 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error', 'Failed to delete tenant', 'error');
            });
        }
    });
}

// Add Tenant
function addTenant() {
    Swal.fire({
        title: 'Add New Tenant',
        html: `
            <form id="addForm">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Domain</label>
                    <input type="text" class="form-control" id="domain" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Subdomain</label>
                    <input type="text" class="form-control" id="subdomain" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Subscription Plan</label>
                    <select class="form-control" id="subscription_plan">
                        <option value="basic">Basic</option>
                        <option value="premium">Premium</option>
                        <option value="enterprise">Enterprise</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Vendor Limit</label>
                    <input type="number" class="form-control" id="vendor_limit" value="10" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Product Limit</label>
                    <input type="number" class="form-control" id="product_limit" value="1000" required>
                </div>
            </form>
        `,
        showCancelButton: true,
        confirmButtonText: 'Create',
        cancelButtonText: 'Cancel',
        preConfirm: () => {
            const formData = {
                name: document.getElementById('name').value,
                domain: document.getElementById('domain').value,
                subdomain: document.getElementById('subdomain').value,
                subscription_plan: document.getElementById('subscription_plan').value,
                vendor_limit: document.getElementById('vendor_limit').value,
                product_limit: document.getElementById('product_limit').value
            };

            return fetch('/api/admin/tenants', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    throw new Error(data.message);
                }
                return data;
            });
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Success!', 'Tenant created successfully', 'success').then(() => {
                location.reload();
            });
        }
    }).catch(error => {
        Swal.fire('Error', error.message, 'error');
    });
}
</script>
@endsection
@endsection