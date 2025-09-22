<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>XpertBid - Vendors Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 4px 0;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }
        .main-content {
            background: #f8f9fa;
            min-height: 100vh;
        }
        .table-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h4 class="text-white"><i class="fas fa-gavel"></i> XpertBid</h4>
                        <small class="text-white-50">Admin Dashboard</small>
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="/admin">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/tenants">
                                <i class="fas fa-building me-2"></i>
                                Tenants
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/users">
                                <i class="fas fa-users me-2"></i>
                                Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="/admin/vendors">
                                <i class="fas fa-store me-2"></i>
                                Vendors
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/products">
                                <i class="fas fa-box me-2"></i>
                                Products
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/properties">
                                <i class="fas fa-home me-2"></i>
                                Properties
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/vehicles">
                                <i class="fas fa-car me-2"></i>
                                Vehicles
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/auctions">
                                <i class="fas fa-gavel me-2"></i>
                                Auctions
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><i class="fas fa-store me-2"></i>Vendors Management</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-primary" onclick="addVendor()">
                                <i class="fas fa-plus"></i> Add Vendor
                            </button>
                        </div>
                    </div>
                </div>

                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Business Name</th>
                                    <th>Store Name</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Tier</th>
                                    <th>Commission</th>
                                    <th>Verified</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vendors as $vendor)
                                <tr>
                                    <td>{{ $vendor->id }}</td>
                                    <td>
                                        <strong>{{ $vendor->business_name }}</strong>
                                    </td>
                                    <td>
                                        <code>{{ $vendor->store_name }}</code>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($vendor->business_type) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $vendor->status == 'approved' ? 'success' : ($vendor->status == 'rejected' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($vendor->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $vendor->tier == 'platinum' ? 'primary' : ($vendor->tier == 'gold' ? 'warning' : ($vendor->tier == 'silver' ? 'secondary' : 'dark')) }}">
                                            {{ ucfirst($vendor->tier) }}
                                        </span>
                                    </td>
                                    <td>{{ $vendor->commission_rate }}%</td>
                                    <td>
                                        @if($vendor->verified)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle"></i> Verified
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock"></i> Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($vendor->created_at)->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" title="View" onclick="viewVendor({{ $vendor->id }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-warning" title="Edit" onclick="editVendor({{ $vendor->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" title="Delete" onclick="deleteVendor({{ $vendor->id }}, '{{ $vendor->business_name }}')">
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
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // View Vendor Details
        function viewVendor(id) {
            fetch(`/api/admin/vendors/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const vendor = data.data;
                        Swal.fire({
                            title: vendor.business_name,
                            html: `
                                <div class="text-start">
                                    <p><strong>Store Name:</strong> ${vendor.store_name}</p>
                                    <p><strong>Business Type:</strong> ${vendor.business_type}</p>
                                    <p><strong>Status:</strong> <span class="badge bg-${vendor.status === 'approved' ? 'success' : 'warning'}">${vendor.status}</span></p>
                                    <p><strong>Tier:</strong> <span class="badge bg-primary">${vendor.tier}</span></p>
                                    <p><strong>Commission Rate:</strong> ${vendor.commission_rate}%</p>
                                    <p><strong>Contact Email:</strong> ${vendor.contact_email}</p>
                                    <p><strong>Contact Phone:</strong> ${vendor.contact_phone || 'N/A'}</p>
                                    <p><strong>Verified:</strong> <span class="badge bg-${vendor.verified ? 'success' : 'secondary'}">${vendor.verified ? 'Yes' : 'No'}</span></p>
                                    <p><strong>Created:</strong> ${new Date(vendor.created_at).toLocaleDateString()}</p>
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
                    Swal.fire('Error', 'Failed to fetch vendor details', 'error');
                });
        }

        // Edit Vendor
        function editVendor(id) {
            fetch(`/api/admin/vendors/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const vendor = data.data;
                        Swal.fire({
                            title: 'Edit Vendor',
                            html: `
                                <form id="editForm">
                                    <div class="mb-3">
                                        <label class="form-label">Business Name</label>
                                        <input type="text" class="form-control" id="business_name" value="${vendor.business_name}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Store Name</label>
                                        <input type="text" class="form-control" id="store_name" value="${vendor.store_name}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Business Type</label>
                                        <select class="form-control" id="business_type">
                                            <option value="individual" ${vendor.business_type === 'individual' ? 'selected' : ''}>Individual</option>
                                            <option value="company" ${vendor.business_type === 'company' ? 'selected' : ''}>Company</option>
                                            <option value="corporation" ${vendor.business_type === 'corporation' ? 'selected' : ''}>Corporation</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select class="form-control" id="status">
                                            <option value="pending" ${vendor.status === 'pending' ? 'selected' : ''}>Pending</option>
                                            <option value="approved" ${vendor.status === 'approved' ? 'selected' : ''}>Approved</option>
                                            <option value="rejected" ${vendor.status === 'rejected' ? 'selected' : ''}>Rejected</option>
                                            <option value="suspended" ${vendor.status === 'suspended' ? 'selected' : ''}>Suspended</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Tier</label>
                                        <select class="form-control" id="tier">
                                            <option value="bronze" ${vendor.tier === 'bronze' ? 'selected' : ''}>Bronze</option>
                                            <option value="silver" ${vendor.tier === 'silver' ? 'selected' : ''}>Silver</option>
                                            <option value="gold" ${vendor.tier === 'gold' ? 'selected' : ''}>Gold</option>
                                            <option value="platinum" ${vendor.tier === 'platinum' ? 'selected' : ''}>Platinum</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Commission Rate (%)</label>
                                        <input type="number" class="form-control" id="commission_rate" value="${vendor.commission_rate}" min="0" max="100" step="0.01">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Contact Email</label>
                                        <input type="email" class="form-control" id="contact_email" value="${vendor.contact_email}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Contact Phone</label>
                                        <input type="text" class="form-control" id="contact_phone" value="${vendor.contact_phone || ''}">
                                    </div>
                                </form>
                            `,
                            showCancelButton: true,
                            confirmButtonText: 'Update',
                            cancelButtonText: 'Cancel',
                            preConfirm: () => {
                                const formData = {
                                    business_name: document.getElementById('business_name').value,
                                    store_name: document.getElementById('store_name').value,
                                    business_type: document.getElementById('business_type').value,
                                    status: document.getElementById('status').value,
                                    tier: document.getElementById('tier').value,
                                    commission_rate: document.getElementById('commission_rate').value,
                                    contact_email: document.getElementById('contact_email').value,
                                    contact_phone: document.getElementById('contact_phone').value
                                };

                                return fetch(`/api/admin/vendors/${id}`, {
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
                                Swal.fire('Success!', 'Vendor updated successfully', 'success').then(() => {
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
                    Swal.fire('Error', 'Failed to fetch vendor details', 'error');
                });
        }

        // Delete Vendor
        function deleteVendor(id, name) {
            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete vendor "${name}". This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/api/admin/vendors/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Deleted!', 'Vendor has been deleted successfully', 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error', 'Failed to delete vendor', 'error');
                    });
                }
            });
        }

        // Add Vendor
        function addVendor() {
            Swal.fire({
                title: 'Add New Vendor',
                html: `
                    <form id="addForm">
                        <div class="mb-3">
                            <label class="form-label">Business Name</label>
                            <input type="text" class="form-control" id="business_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Store Name</label>
                            <input type="text" class="form-control" id="store_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Store Slug</label>
                            <input type="text" class="form-control" id="store_slug" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Business Type</label>
                            <select class="form-control" id="business_type">
                                <option value="individual">Individual</option>
                                <option value="company">Company</option>
                                <option value="corporation">Corporation</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contact Email</label>
                            <input type="email" class="form-control" id="contact_email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contact Phone</label>
                            <input type="text" class="form-control" id="contact_phone">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Commission Rate (%)</label>
                            <input type="number" class="form-control" id="commission_rate" value="5.00" min="0" max="100" step="0.01">
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Create',
                cancelButtonText: 'Cancel',
                preConfirm: () => {
                    const formData = {
                        tenant_id: 1, // Default to first tenant
                        user_id: 1, // Default to first user
                        business_name: document.getElementById('business_name').value,
                        store_name: document.getElementById('store_name').value,
                        store_slug: document.getElementById('store_slug').value,
                        business_type: document.getElementById('business_type').value,
                        contact_email: document.getElementById('contact_email').value,
                        contact_phone: document.getElementById('contact_phone').value,
                        commission_rate: document.getElementById('commission_rate').value
                    };

                    return fetch('/api/admin/vendors', {
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
                    Swal.fire('Success!', 'Vendor created successfully', 'success').then(() => {
                        location.reload();
                    });
                }
            }).catch(error => {
                Swal.fire('Error', error.message, 'error');
            });
        }
    </script>
</body>
</html>
