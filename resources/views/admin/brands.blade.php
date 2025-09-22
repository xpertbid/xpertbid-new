@extends('admin.layouts.app')

@section('title', 'Brands Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Brands Management</h1>
        <div>
            <a href="/admin/brands/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Brand
            </a>
        </div>
    </div>

    <!-- Brands Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Brands List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="brandsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Logo</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Website</th>
                            <th>Status</th>
                            <th>Type</th>
                            <th>Products</th>
                            <th>Views</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($brands as $brand)
                        <tr>
                            <td>{{ $brand->id }}</td>
                            <td>
                                @if($brand->logo)
                                    <img src="{{ $brand->logo }}" width="50" height="50" class="rounded">
                                @else
                                    <img src="/images/no-image.png" width="50" height="50" class="rounded">
                                @endif
                            </td>
                            <td>{{ $brand->name }}</td>
                            <td>{{ $brand->slug }}</td>
                            <td>
                                @if($brand->website_url)
                                    <a href="{{ $brand->website_url }}" target="_blank" class="text-decoration-none">
                                        <i class="fas fa-external-link-alt"></i> Visit
                                    </a>
                                @else
                                    <span class="text-muted">Not provided</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $brand->status ? 'success' : 'danger' }}">
                                    {{ $brand->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-info">
                                    <i class="fas fa-tag"></i> Brand
                                </span>
                            </td>
                            <td>0</td>
                            <td>0</td>
                            <td>{{ \Carbon\Carbon::parse($brand->created_at)->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" title="View" onclick="viewBrand({{ $brand->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="/admin/brands/{{ $brand->id }}/edit" class="btn btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-outline-danger" title="Delete" onclick="deleteBrand({{ $brand->id }})">
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

<!-- View Brand Modal -->
<div class="modal fade" id="viewBrandModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Brand Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="brandDetails">
                <!-- Brand details will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function viewBrand(id) {
    // For now, just show a placeholder
    $('#brandDetails').html(`
        <div class="row">
            <div class="col-md-6">
                <h5>Brand Information</h5>
                <p><strong>ID:</strong> ${id}</p>
                <p><strong>Status:</strong> Active</p>
                <p><strong>Website:</strong> Not provided</p>
            </div>
            <div class="col-md-6">
                <h5>Details</h5>
                <p><strong>Products:</strong> 0</p>
                <p><strong>Views:</strong> 0</p>
                <p><strong>Created:</strong> ${new Date().toLocaleDateString()}</p>
            </div>
        </div>
    `);
    $('#viewBrandModal').modal('show');
}

function deleteBrand(id) {
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
            Swal.fire('Deleted!', 'Brand has been deleted.', 'success');
        }
    });
}
</script>
@endsection