@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="/admin">Dashboard</a></li>
                        <li class="breadcrumb-item active">Shipping Zones</li>
                    </ol>
                </div>
                <h4 class="page-title">Shipping Zones Management</h4>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1">
                            <p class="text-truncate font-size-14 mb-2">Total Shipping Zones</p>
                            <h4 class="mb-2">{{ $stats['total_zones'] }}</h4>
                            <p class="text-muted mb-0"><span class="text-success me-2"><i class="mdi mdi-arrow-up-bold me-1"></i></span>From previous period</p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-light text-primary rounded-3">
                                <i class="fas fa-globe font-size-18"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1">
                            <p class="text-truncate font-size-14 mb-2">Active Zones</p>
                            <h4 class="mb-2">{{ $stats['active_zones'] }}</h4>
                            <p class="text-muted mb-0"><span class="text-success me-2"><i class="mdi mdi-arrow-up-bold me-1"></i></span>Currently active</p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-light text-success rounded-3">
                                <i class="fas fa-check-circle font-size-18"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1">
                            <p class="text-truncate font-size-14 mb-2">Total Methods</p>
                            <h4 class="mb-2">{{ $stats['total_methods'] }}</h4>
                            <p class="text-muted mb-0"><span class="text-success me-2"><i class="mdi mdi-arrow-up-bold me-1"></i></span>All zones</p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-light text-warning rounded-3">
                                <i class="fas fa-shipping-fast font-size-18"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1">
                            <p class="text-truncate font-size-14 mb-2">Average Cost</p>
                            <h4 class="mb-2">${{ number_format($stats['avg_cost'], 2) }}</h4>
                            <p class="text-muted mb-0"><span class="text-info me-2"><i class="mdi mdi-currency-usd me-1"></i></span>Per shipment</p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-light text-info rounded-3">
                                <i class="fas fa-dollar-sign font-size-18"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title mb-0">Shipping Zones</h5>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.shipping.create') }}" class="btn btn-primary me-2">
                                <i class="fas fa-plus me-1"></i> Add New Zone
                            </a>
                            <a href="{{ route('admin.shipping.carriers') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-truck me-1"></i> Manage Carriers
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Zone Name</th>
                                    <th>Countries</th>
                                    <th>Methods</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($shippingZones as $zone)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-3">
                                                    <span class="avatar-title bg-light text-primary rounded-circle">
                                                        <i class="fas fa-globe"></i>
                                                    </span>
                                                </div>
                                                <div>
                                                    <strong>{{ $zone['name'] }}</strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $zone['countries'] }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $zone['methods_count'] }}</span>
                                        </td>
                                        <td>
                                            @if($zone['status'] === 'active')
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ date('M d, Y', strtotime($zone['created_at'])) }}
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.shipping.show', $zone['id']) }}" class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.shipping.edit', $zone['id']) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.shipping.destroy', $zone['id']) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this shipping zone?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-shipping-fast fa-3x mb-3"></i>
                                                <p>No shipping zones found. <a href="{{ route('admin.shipping.create') }}">Add your first shipping zone</a></p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
