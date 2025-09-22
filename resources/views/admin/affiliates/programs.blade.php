@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="/admin">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.affiliates.index') }}">Affiliates</a></li>
                        <li class="breadcrumb-item active">Affiliate Programs</li>
                    </ol>
                </div>
                <h4 class="page-title">Affiliate Programs Management</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title mb-0">Affiliate Programs</h5>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProgramModal">
                                <i class="fas fa-plus me-1"></i> Create New Program
                            </button>
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
                        @foreach($programs as $program)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100 border {{ $program['is_active'] ? 'border-success' : 'border-secondary' }}">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-handshake"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $program['name'] }}</h6>
                                                <small class="text-muted">Program #{{ $program['id'] }}</small>
                                            </div>
                                        </div>
                                        
                                        <p class="text-muted small mb-3">{{ $program['description'] }}</p>
                                        
                                        <div class="mb-3">
                                            <strong>Commission Rate:</strong>
                                            <span class="badge bg-light text-dark">{{ $program['commission_rate'] }}%</span>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <strong>Min Payout:</strong>
                                            <span class="text-muted">${{ number_format($program['min_payout'], 2) }}</span>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <strong>Payout Frequency:</strong>
                                            <span class="text-muted">{{ ucfirst($program['payout_frequency']) }}</span>
                                        </div>
                                        
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                @if($program['is_active'])
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </div>
                                            
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm {{ $program['is_active'] ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                                    <i class="fas fa-power-off"></i>
                                                </button>
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

<!-- Create Program Modal -->
<div class="modal fade" id="createProgramModal" tabindex="-1" aria-labelledby="createProgramModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createProgramModalLabel">Create New Affiliate Program</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="program_name" class="form-label">Program Name</label>
                        <input type="text" class="form-control" id="program_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="program_description" class="form-label">Description</label>
                        <textarea class="form-control" id="program_description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="commission_rate" class="form-label">Commission Rate (%)</label>
                        <input type="number" class="form-control" id="commission_rate" min="0" max="100" step="0.1" required>
                    </div>
                    <div class="mb-3">
                        <label for="min_payout" class="form-label">Minimum Payout ($)</label>
                        <input type="number" class="form-control" id="min_payout" min="0" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="payout_frequency" class="form-label">Payout Frequency</label>
                        <select class="form-select" id="payout_frequency" required>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="quarterly">Quarterly</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Create Program</button>
            </div>
        </div>
    </div>
</div>
@endsection
