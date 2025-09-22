@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="/admin">Dashboard</a></li>
                        <li class="breadcrumb-item active">Payment Gateways</li>
                    </ol>
                </div>
                <h4 class="page-title">Payment Gateway Management</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title mb-0">Available Payment Gateways</h5>
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

                    <div class="row">
                        @foreach($paymentGateways as $gateway)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100 border {{ $gateway['is_active'] ? 'border-success' : 'border-secondary' }}">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            @if($gateway['logo'])
                                                <img src="{{ $gateway['logo'] }}" alt="{{ $gateway['name'] }}" class="me-3" style="height: 40px;">
                                            @else
                                                <div class="bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-credit-card"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $gateway['name'] }}</h6>
                                                <small class="text-muted">{{ $gateway['slug'] }}</small>
                                            </div>
                                        </div>
                                        
                                        <p class="text-muted small mb-3">{{ $gateway['description'] }}</p>
                                        
                                        <div class="mb-3">
                                            <strong>Supported Currencies:</strong>
                                            <div class="mt-1">
                                                @foreach($gateway['supported_currencies'] as $currency)
                                                    <span class="badge bg-light text-dark me-1">{{ $currency }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <strong>Fees:</strong>
                                            <span class="text-muted">{{ $gateway['fees'] }}</span>
                                        </div>
                                        
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                @if($gateway['is_active'])
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                                
                                                @if($gateway['is_test_mode'])
                                                    <span class="badge bg-warning ms-1">Test Mode</span>
                                                @endif
                                            </div>
                                            
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.payments.configure', $gateway['slug']) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-cog"></i> Configure
                                                </a>
                                                <form action="{{ route('admin.payments.toggle', $gateway['slug']) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm {{ $gateway['is_active'] ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                                        <i class="fas fa-power-off"></i>
                                                        {{ $gateway['is_active'] ? 'Disable' : 'Enable' }}
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
