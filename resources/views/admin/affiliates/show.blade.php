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
                        <li class="breadcrumb-item active">{{ $affiliate['name'] }}</li>
                    </ol>
                </div>
                <h4 class="page-title">Affiliate Details</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Affiliate Overview -->
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title mb-0">Affiliate Overview</h5>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.affiliates.edit', $affiliate['id']) }}" class="btn btn-primary">
                                <i class="fas fa-edit me-1"></i> Edit Affiliate
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Name:</strong><br>
                                <span class="text-muted">{{ $affiliate['name'] }}</span>
                            </div>
                            <div class="mb-3">
                                <strong>Email:</strong><br>
                                <span class="text-muted">{{ $affiliate['email'] }}</span>
                            </div>
                            <div class="mb-3">
                                <strong>Status:</strong><br>
                                @if($affiliate['status'] === 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($affiliate['status'] === 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Commission Rate:</strong><br>
                                <span class="badge bg-light text-dark">{{ $affiliate['commission_rate'] }}%</span>
                            </div>
                            <div class="mb-3">
                                <strong>Total Earnings:</strong><br>
                                <span class="text-success fw-bold">${{ number_format($affiliate['total_earnings'], 2) }}</span>
                            </div>
                            <div class="mb-3">
                                <strong>Pending Earnings:</strong><br>
                                <span class="text-warning fw-bold">${{ number_format($affiliate['pending_earnings'], 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Commissions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Commissions</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Order ID</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($commissions as $commission)
                                    <tr>
                                        <td><strong>${{ number_format($commission['amount'], 2) }}</strong></td>
                                        <td>
                                            @if($commission['status'] === 'paid')
                                                <span class="badge bg-success">Paid</span>
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td><code>{{ $commission['order_id'] }}</code></td>
                                        <td>{{ date('M d, Y', strtotime($commission['created_at'])) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3">
                                            <span class="text-muted">No commissions yet</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Referrals -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Referrals</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>User Email</th>
                                    <th>Status</th>
                                    <th>Commission Earned</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($referrals as $referral)
                                    <tr>
                                        <td>{{ $referral['user_email'] }}</td>
                                        <td>
                                            @if($referral['status'] === 'converted')
                                                <span class="badge bg-success">Converted</span>
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($referral['commission_earned'] > 0)
                                                <span class="text-success">${{ number_format($referral['commission_earned'], 2) }}</span>
                                            @else
                                                <span class="text-muted">$0.00</span>
                                            @endif
                                        </td>
                                        <td>{{ date('M d, Y', strtotime($referral['created_at'])) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3">
                                            <span class="text-muted">No referrals yet</span>
                                        </td>
                                    </tr>
                                @endforelse
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
                        <strong>Total Referrals:</strong><br>
                        <span class="badge bg-info fs-6">{{ $affiliate['referrals_count'] }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Conversion Rate:</strong><br>
                        <span class="badge bg-light text-dark fs-6">{{ $affiliate['conversion_rate'] }}%</span>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Joined Date:</strong><br>
                        <span class="text-muted">{{ date('M d, Y', strtotime($affiliate['joined_date'])) }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Last Activity:</strong><br>
                        <span class="text-muted">{{ date('M d, Y', strtotime($affiliate['last_activity'])) }}</span>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.affiliates.edit', $affiliate['id']) }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit me-1"></i> Edit Affiliate
                        </a>
                        <a href="{{ route('admin.affiliates.commissions') }}" class="btn btn-outline-info">
                            <i class="fas fa-money-bill me-1"></i> View All Commissions
                        </a>
                        <a href="{{ route('admin.affiliates.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back to Affiliates
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
