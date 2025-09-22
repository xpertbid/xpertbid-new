@extends('admin.layouts.app')

@section('title', 'Vehicles Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Vehicles Management</h1>
        <div>
            <a href="/admin/vehicles/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Vehicle
            </a>
        </div>
    </div>

    <!-- Vehicles Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Vehicles List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="vehiclesTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Make/Model</th>
                            <th>Year</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Featured</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vehicles as $vehicle)
                        <tr>
                            <td>{{ $vehicle->id }}</td>
                            <td>
                                @if($vehicle->images)
                                    @php
                                        $images = json_decode($vehicle->images, true);
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
                            <td>{{ $vehicle->title }}</td>
                            <td>{{ $vehicle->make }} {{ $vehicle->model }}</td>
                            <td>{{ $vehicle->year }}</td>
                            <td>${{ number_format($vehicle->price, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $vehicle->vehicle_status === 'available' ? 'success' : 'warning' }}">
                                    {{ ucfirst($vehicle->vehicle_status) }}
                                </span>
                            </td>
                            <td>
                                @if($vehicle->is_featured)
                                    <span class="badge bg-warning">
                                        <i class="fas fa-star"></i> Featured
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Regular</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" title="View" onclick="viewVehicle({{ $vehicle->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="/admin/vehicles/{{ $vehicle->id }}/edit" class="btn btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-outline-danger" title="Delete" onclick="deleteVehicle({{ $vehicle->id }})">
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

<!-- View Vehicle Modal -->
<div class="modal fade" id="viewVehicleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Vehicle Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="vehicleDetails">
                <!-- Vehicle details will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function viewVehicle(id) {
    // For now, just show a placeholder
    $('#vehicleDetails').html(`
        <div class="row">
            <div class="col-md-6">
                <h5>Vehicle Information</h5>
                <p><strong>ID:</strong> ${id}</p>
                <p><strong>Status:</strong> Available</p>
                <p><strong>Featured:</strong> No</p>
            </div>
            <div class="col-md-6">
                <h5>Details</h5>
                <p><strong>Make:</strong> Toyota</p>
                <p><strong>Model:</strong> Camry</p>
                <p><strong>Year:</strong> 2023</p>
            </div>
        </div>
    `);
    $('#viewVehicleModal').modal('show');
}

function deleteVehicle(id) {
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
            Swal.fire('Deleted!', 'Vehicle has been deleted.', 'success');
        }
    });
}
</script>
@endsection