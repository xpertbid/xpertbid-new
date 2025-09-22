@extends('admin.layouts.app')

@section('title', 'Properties Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Properties Management</h1>
        <div>
            <a href="/admin/properties/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Property
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Properties Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Properties List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="propertiesTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Price</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Featured</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($properties as $property)
                        <tr>
                            <td>{{ $property->id }}</td>
                            <td>
                                @if($property->images)
                                    @php
                                        $images = json_decode($property->images, true);
                                        $firstImage = is_array($images) && count($images) > 0 ? $images[0] : null;
                                    @endphp
                                    @if($firstImage)
                                        <img src="{{ $firstImage }}" width="50" height="50" class="rounded">
                                    @else
                                        <img src="/images/no-image.png" width="50" height="50" class="rounded">
                                    @endif
                                @else
                                    <img src="/images/no-image.png" width="50" height="50" class="rounded">
                                @endif
                            </td>
                            <td>{{ $property->title }}</td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($property->property_type) }}</span>
                            </td>
                            <td>${{ number_format($property->price, 2) }}</td>
                            <td>{{ $property->city }}, {{ $property->state }}</td>
                            <td>
                                <span class="badge bg-{{ $property->property_status === 'available' ? 'success' : 'warning' }}">
                                    {{ ucfirst($property->property_status) }}
                                </span>
                            </td>
                            <td>
                                @if($property->is_featured)
                                    <span class="badge bg-warning">
                                        <i class="fas fa-star"></i> Featured
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Regular</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" title="View" onclick="viewProperty({{ $property->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="/admin/properties/{{ $property->id }}/edit" class="btn btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-outline-danger" title="Delete" onclick="deleteProperty({{ $property->id }})">
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

<!-- View Property Modal -->
<div class="modal fade" id="viewPropertyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Property Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="propertyDetails">
                <!-- Property details will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function viewProperty(id) {
    // For now, just show a placeholder
    $('#propertyDetails').html(`
        <div class="row">
            <div class="col-md-6">
                <h5>Property Information</h5>
                <p><strong>ID:</strong> ${id}</p>
                <p><strong>Status:</strong> Available</p>
                <p><strong>Featured:</strong> No</p>
            </div>
            <div class="col-md-6">
                <h5>Details</h5>
                <p><strong>Type:</strong> House</p>
                <p><strong>Price:</strong> $500,000</p>
                <p><strong>Location:</strong> New York, NY</p>
            </div>
        </div>
    `);
    $('#viewPropertyModal').modal('show');
}

function deleteProperty(id) {
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
            Swal.fire('Deleted!', 'Property has been deleted.', 'success');
        }
    });
}
</script>
@endsection