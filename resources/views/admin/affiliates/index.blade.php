@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="/admin">Dashboard</a></li>
                        <li class="breadcrumb-item active">Affiliates & Referrals</li>
                    </ol>
                </div>
                <h4 class="page-title">Affiliate & Referral Management</h4>
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
                            <p class="text-truncate font-size-14 mb-2">Total Affiliates</p>
                            <h4 class="mb-2">{{ $stats['total_affiliates'] }}</h4>
                            <p class="text-muted mb-0"><span class="text-success me-2"><i class="mdi mdi-arrow-up-bold me-1"></i></span>From previous period</p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-light text-primary rounded-3">
                                <i class="fas fa-users font-size-18"></i>
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
                            <p class="text-truncate font-size-14 mb-2">Active Affiliates</p>
                            <h4 class="mb-2">{{ $stats['active_affiliates'] }}</h4>
                            <p class="text-muted mb-0"><span class="text-success me-2"><i class="mdi mdi-arrow-up-bold me-1"></i></span>Currently active</p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-light text-success rounded-3">
                                <i class="fas fa-user-check font-size-18"></i>
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
                            <p class="text-truncate font-size-14 mb-2">Total Commissions Paid</p>
                            <h4 class="mb-2">${{ number_format($stats['total_commissions_paid'], 2) }}</h4>
                            <p class="text-muted mb-0"><span class="text-success me-2"><i class="mdi mdi-arrow-up-bold me-1"></i></span>All time</p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-light text-warning rounded-3">
                                <i class="fas fa-dollar-sign font-size-18"></i>
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
                            <p class="text-truncate font-size-14 mb-2">Pending Commissions</p>
                            <h4 class="mb-2">${{ number_format($stats['pending_commissions'], 2) }}</h4>
                            <p class="text-muted mb-0"><span class="text-warning me-2"><i class="mdi mdi-clock me-1"></i></span>Awaiting payout</p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-light text-info rounded-3">
                                <i class="fas fa-clock font-size-18"></i>
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
                            <h5 class="card-title mb-0">All Affiliates</h5>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.affiliates.create') }}" class="btn btn-primary me-2">
                                <i class="fas fa-plus me-1"></i> Add New Affiliate
                            </a>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.affiliates.programs') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-cogs me-1"></i> Programs
                                </a>
                                <a href="{{ route('admin.affiliates.commissions') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-money-bill me-1"></i> Commissions
                                </a>
                            </div>
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
                                    <th>Affiliate</th>
                                    <th>Status</th>
                                    <th>Commission Rate</th>
                                    <th>Total Earnings</th>
                                    <th>Pending</th>
                                    <th>Referrals</th>
                                    <th>Conversion Rate</th>
                                    <th>Joined</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($affiliates as $affiliate)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-3">
                                                    <span class="avatar-title bg-light text-primary rounded-circle">
                                                        {{ substr($affiliate['name'], 0, 1) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <strong>{{ $affiliate['name'] }}</strong>
                                                    <br><small class="text-muted">{{ $affiliate['email'] }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($affiliate['status'] === 'active')
                                                <span class="badge bg-success">Active</span>
                                            @elseif($affiliate['status'] === 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">{{ $affiliate['commission_rate'] }}%</span>
                                        </td>
                                        <td>
                                            <strong>${{ number_format($affiliate['total_earnings'], 2) }}</strong>
                                        </td>
                                        <td>
                                            <span class="text-warning">${{ number_format($affiliate['pending_earnings'], 2) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $affiliate['referrals_count'] }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">{{ $affiliate['conversion_rate'] }}%</span>
                                        </td>
                                        <td>
                                            {{ date('M d, Y', strtotime($affiliate['joined_date'])) }}
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.affiliates.show', $affiliate['id']) }}" class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.affiliates.edit', $affiliate['id']) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.affiliates.destroy', $affiliate['id']) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this affiliate?')">
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
                                        <td colspan="9" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-handshake fa-3x mb-3"></i>
                                                <p>No affiliates found. <a href="{{ route('admin.affiliates.create') }}">Add your first affiliate</a></p>
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
