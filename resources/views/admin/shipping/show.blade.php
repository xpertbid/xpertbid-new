@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="/admin">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.shipping.index') }}">Shipping</a></li>
                        <li class="breadcrumb-item active">{{ $shippingZone['name'] }}</li>
                    </ol>
                </div>
                <h4 class="page-title">Shipping Zone Details</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Zone Overview -->
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title mb-0">Zone Overview</h5>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.shipping.edit', $shippingZone['id']) }}" class="btn btn-primary">
                                <i class="fas fa-edit me-1"></i> Edit Zone
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Zone Name:</strong><br>
                                <span class="text-muted">{{ $shippingZone['name'] }}</span>
                            </div>
                            <div class="mb-3">
                                <strong>Countries:</strong><br>
                                <span class="text-muted">{{ $shippingZone['countries'] }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Status:</strong><br>
                                @if($shippingZone['status'] === 'active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </div>
                            <div class="mb-3">
                                <strong>Shipping Methods:</strong><br>
                                <span class="badge bg-info">{{ $shippingZone['methods_count'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Methods -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Shipping Methods</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Method Name</th>
                                    <th>Type</th>
                                    <th>Cost</th>
                                    <th>Delivery Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Standard Shipping</td>
                                    <td>Flat Rate</td>
                                    <td>$9.99</td>
                                    <td>5-7 days</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                </tr>
                                <tr>
                                    <td>Express Shipping</td>
                                    <td>Flat Rate</td>
                                    <td>$19.99</td>
                                    <td>2-3 days</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                </tr>
                                <tr>
                                    <td>FedEx Ground</td>
                                    <td>Carrier Based</td>
                                    <td>Calculated</td>
                                    <td>3-5 days</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Stats</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Created:</strong><br>
                        <span class="text-muted">{{ date('M d, Y', strtotime($shippingZone['created_at'])) }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Total Methods:</strong><br>
                        <span class="badge bg-info fs-6">{{ $shippingZone['methods_count'] }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Average Cost:</strong><br>
                        <span class="text-success fw-bold">$12.50</span>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.shipping.edit', $shippingZone['id']) }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit me-1"></i> Edit Zone
                        </a>
                        <a href="{{ route('admin.shipping.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back to Shipping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
