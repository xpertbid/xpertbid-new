@extends('admin.layouts.app')

@section('title', 'Users Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Users Management</h1>
            <p class="text-muted mb-0">Manage all users in your system</p>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-primary" onclick="addUser()">
                    <i class="fas fa-plus me-1"></i>
                    Add User
                </button>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-users me-2"></i>All Users
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>
                                <strong>{{ $user->name }}</strong>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : 'primary' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $user->status == 'active' ? 'success' : 'warning' }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" title="View" onclick="viewUser({{ $user->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-warning" title="Edit" onclick="editUser({{ $user->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-danger" title="Delete" onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')">
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
// View User Details
function viewUser(id) {
    fetch(`/api/admin/users/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const user = data.data;
                Swal.fire({
                    title: user.name,
                    html: `
                        <div class="text-start">
                            <p><strong>Email:</strong> ${user.email}</p>
                            <p><strong>Role:</strong> <span class="badge bg-${user.role === 'admin' ? 'danger' : 'primary'}">${user.role}</span></p>
                            <p><strong>Status:</strong> <span class="badge bg-${user.status === 'active' ? 'success' : 'warning'}">${user.status}</span></p>
                            <p><strong>Created:</strong> ${new Date(user.created_at).toLocaleDateString()}</p>
                            <p><strong>Last Login:</strong> ${user.last_login_at ? new Date(user.last_login_at).toLocaleDateString() : 'Never'}</p>
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
            Swal.fire('Error', 'Failed to fetch user details', 'error');
        });
}

// Edit User
function editUser(id) {
    fetch(`/api/admin/users/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const user = data.data;
                Swal.fire({
                    title: 'Edit User',
                    html: `
                        <form id="editForm">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" value="${user.name}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" value="${user.email}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Role</label>
                                <select class="form-control" id="role">
                                    <option value="user" ${user.role === 'user' ? 'selected' : ''}>User</option>
                                    <option value="admin" ${user.role === 'admin' ? 'selected' : ''}>Admin</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-control" id="status">
                                    <option value="active" ${user.status === 'active' ? 'selected' : ''}>Active</option>
                                    <option value="inactive" ${user.status === 'inactive' ? 'selected' : ''}>Inactive</option>
                                    <option value="suspended" ${user.status === 'suspended' ? 'selected' : ''}>Suspended</option>
                                </select>
                            </div>
                        </form>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Update',
                    cancelButtonText: 'Cancel',
                    preConfirm: () => {
                        const formData = {
                            name: document.getElementById('name').value,
                            email: document.getElementById('email').value,
                            role: document.getElementById('role').value,
                            status: document.getElementById('status').value
                        };

                        return fetch(`/api/admin/users/${id}`, {
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
                        Swal.fire('Success!', 'User updated successfully', 'success').then(() => {
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
            Swal.fire('Error', 'Failed to fetch user details', 'error');
        });
}

// Delete User
function deleteUser(id, name) {
    Swal.fire({
        title: 'Are you sure?',
        text: `You are about to delete user "${name}". This action cannot be undone!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/api/admin/users/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Deleted!', 'User has been deleted successfully', 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error', 'Failed to delete user', 'error');
            });
        }
    });
}

// Add User
function addUser() {
    Swal.fire({
        title: 'Add New User',
        html: `
            <form id="addForm">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select class="form-control" id="role">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select class="form-control" id="status">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </form>
        `,
        showCancelButton: true,
        confirmButtonText: 'Create',
        cancelButtonText: 'Cancel',
        preConfirm: () => {
            const formData = {
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,
                role: document.getElementById('role').value,
                status: document.getElementById('status').value
            };

            return fetch('/api/admin/users', {
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
            Swal.fire('Success!', 'User created successfully', 'success').then(() => {
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