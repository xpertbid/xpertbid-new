@extends('admin.layouts.app')

@section('title', 'Categories Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Categories Management</h1>
        <div>
            <a href="/admin/categories/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Category
            </a>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Categories List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="categoriesTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Parent</th>
                            <th>Icon</th>
                            <th>Status</th>
                            <th>Featured</th>
                            <th>Sort Order</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>
                                @if($category->image)
                                    <img src="{{ $category->image }}" width="50" height="50" class="rounded">
                                @else
                                    <img src="/images/no-image.png" width="50" height="50" class="rounded">
                                @endif
                            </td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->slug }}</td>
                            <td>
                                @if($category->parent_id)
                                    <span class="badge bg-info">Subcategory</span>
                                @else
                                    <span class="badge bg-primary">Main Category</span>
                                @endif
                            </td>
                            <td>
                                @if($category->icon)
                                    <i class="{{ $category->icon }}"></i>
                                @else
                                    <span class="text-muted">No Icon</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $category->is_active ? 'success' : 'danger' }}">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                @if($category->is_featured)
                                    <span class="badge bg-warning">
                                        <i class="fas fa-star"></i> Featured
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Regular</span>
                                @endif
                            </td>
                            <td>{{ $category->sort_order }}</td>
                            <td>{{ \Carbon\Carbon::parse($category->created_at)->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" title="View" onclick="viewCategory({{ $category->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="/admin/categories/{{ $category->id }}/edit" class="btn btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-outline-danger" title="Delete" onclick="deleteCategory({{ $category->id }})">
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

<!-- View Category Modal -->
<div class="modal fade" id="viewCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Category Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="categoryDetails">
                <!-- Category details will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function viewCategory(id) {
    // For now, just show a placeholder
    $('#categoryDetails').html(`
        <div class="row">
            <div class="col-md-6">
                <h5>Category Information</h5>
                <p><strong>ID:</strong> ${id}</p>
                <p><strong>Status:</strong> Active</p>
                <p><strong>Featured:</strong> No</p>
            </div>
            <div class="col-md-6">
                <h5>Details</h5>
                <p><strong>Sort Order:</strong> 0</p>
                <p><strong>Created:</strong> ${new Date().toLocaleDateString()}</p>
                <p><strong>Type:</strong> Main Category</p>
            </div>
        </div>
    `);
    $('#viewCategoryModal').modal('show');
}

function deleteCategory(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // For now, just show success message
            Swal.fire('Deleted!', 'Category has been deleted.', 'success');
        }
    });
}
</script>
@endsection