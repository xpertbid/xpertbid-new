@extends('admin.layouts.app')

@section('title', 'Shipping Carriers')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Shipping Carriers</h1>
            <p class="text-muted mb-0">Manage your shipping providers and API integrations</p>
        </div>
        <div>
            <a href="/admin/shipping/carriers/create" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Carrier
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <div class="stat-content">
                        <h2>{{ count($carriers) }}</h2>
                        <h6>Total Carriers</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <h2>{{ collect($carriers)->where('status', 'active')->count() }}</h2>
                        <h6>Active Carriers</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon">
                        <i class="fas fa-plug"></i>
                    </div>
                    <div class="stat-content">
                        <h2>{{ collect($carriers)->where('api_status', 'Connected')->count() }}</h2>
                        <h6>Connected APIs</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <div class="stat-content">
                        <h2>{{ collect($carriers)->where('tracking', 'Available')->count() }}</h2>
                        <h6>With Tracking</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Bar -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="searchInput" placeholder="Search carriers..." class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex gap-2 justify-content-end">
                        <select id="statusFilter" class="form-select" style="width: auto;">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        <select id="typeFilter" class="form-select" style="width: auto;">
                            <option value="">All Types</option>
                            <option value="Domestic">Domestic</option>
                            <option value="International">International</option>
                            <option value="Domestic & International">Domestic & International</option>
                        </select>
                        <button class="btn btn-outline-secondary" onclick="resetFilters()">
                            <i class="fas fa-refresh"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Carriers Grid -->
    <div class="row" id="carriersGrid">
        @forelse($carriers as $carrier)
            <div class="col-xl-4 col-lg-6 mb-4 carrier-card" 
                 data-name="{{ strtolower($carrier['name']) }}" 
                 data-status="{{ $carrier['status'] }}" 
                 data-type="{{ $carrier['type'] }}">
                <div class="card carrier-item h-100">
                    <div class="card-body">
                        <!-- Carrier Header -->
                        <div class="d-flex align-items-center mb-3">
                            <div class="carrier-avatar">
                                <i class="fas fa-shipping-fast"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="card-title mb-1">{{ $carrier['name'] }}</h5>
                                <small class="text-muted">{{ $carrier['slug'] }}</small>
                            </div>
                            <div class="carrier-status">
                                @if($carrier['status'] === 'active')
                                    <span class="status-badge status-active">
                                        <i class="fas fa-circle"></i> Active
                                    </span>
                                @else
                                    <span class="status-badge status-inactive">
                                        <i class="fas fa-circle"></i> Inactive
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Carrier Details -->
                        <div class="carrier-details">
                            <div class="detail-row">
                                <span class="detail-label">Type:</span>
                                <span class="detail-value">
                                    <span class="badge badge-type">{{ $carrier['type'] }}</span>
                                </span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">API Status:</span>
                                <span class="detail-value">
                                    @if($carrier['api_status'] === 'Connected')
                                        <span class="badge badge-success">
                                            <i class="fas fa-check"></i> Connected
                                        </span>
                                    @elseif($carrier['api_status'] === 'Pending')
                                        <span class="badge badge-warning">
                                            <i class="fas fa-clock"></i> Pending
                                        </span>
                                    @else
                                        <span class="badge badge-danger">
                                            <i class="fas fa-times"></i> Disconnected
                                        </span>
                                    @endif
                                </span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Tracking:</span>
                                <span class="detail-value">
                                    @if($carrier['tracking'] === 'Available')
                                        <span class="badge badge-success">
                                            <i class="fas fa-map-marked-alt"></i> Available
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">
                                            <i class="fas fa-ban"></i> Not Available
                                        </span>
                                    @endif
                                </span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Base Rate:</span>
                                <span class="detail-value rate-value">${{ number_format($carrier['base_rate'] ?? 0, 2) }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="carrier-actions mt-3">
                            <div class="btn-group w-100" role="group">
                                <a href="/admin/shipping/carriers/{{ $carrier['id'] }}/edit" 
                                   class="btn btn-outline-primary btn-sm" title="Edit Carrier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-outline-info btn-sm" title="Configure API">
                                    <i class="fas fa-cog"></i>
                                </button>
                                <form action="/admin/shipping/carriers/{{ $carrier['slug'] }}/toggle" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" 
                                            class="btn btn-sm {{ $carrier['status'] === 'active' ? 'btn-outline-warning' : 'btn-outline-success' }}" 
                                            title="{{ $carrier['status'] === 'active' ? 'Disable' : 'Enable' }}">
                                        <i class="fas fa-power-off"></i>
                                    </button>
                                </form>
                                <button type="button" 
                                        class="btn btn-outline-danger btn-sm" 
                                        onclick="deleteCarrier({{ $carrier['id'] }}, '{{ $carrier['name'] }}')" 
                                        title="Delete Carrier">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card text-center py-5">
                    <div class="card-body">
                        <div class="empty-state">
                            <i class="fas fa-shipping-fast fa-4x text-muted mb-4"></i>
                            <h4 class="text-muted">No Carriers Found</h4>
                            <p class="text-muted mb-4">Start by adding your first shipping carrier to begin managing deliveries.</p>
                            <a href="/admin/shipping/carriers/create" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Add Your First Carrier
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteCarrierModal" tabindex="-1" aria-labelledby="deleteCarrierModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCarrierModalLabel">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>Confirm Delete
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning!</strong> This action cannot be undone.
                </div>
                <p>Are you sure you want to delete the carrier <strong id="carrierNameToDelete"></strong>?</p>
                <p class="text-muted">This will remove all carrier settings and configurations.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteCarrierForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Delete Carrier
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* Carrier Cards Styling */
.carrier-card {
    transition: var(--transition);
}

.carrier-item {
    border: none;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.carrier-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), #5ba3d4);
    opacity: 0;
    transition: var(--transition);
}

.carrier-item:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-hover);
}

.carrier-item:hover::before {
    opacity: 1;
}

.carrier-avatar {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, var(--primary-color), #5ba3d4);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    font-size: 20px;
    box-shadow: 0 4px 12px rgba(67, 172, 233, 0.3);
}

.carrier-status {
    position: absolute;
    top: 16px;
    right: 16px;
}

.status-badge {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-active {
    background: rgba(40, 167, 69, 0.1);
    color: #28a745;
}

.status-inactive {
    background: rgba(108, 117, 125, 0.1);
    color: #6c757d;
}

.status-badge i {
    font-size: 8px;
}

.carrier-details {
    margin: 16px 0;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid var(--border-color);
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-label {
    font-size: 13px;
    color: var(--text-light);
    font-weight: 500;
}

.detail-value {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-dark);
}

.rate-value {
    color: var(--primary-color);
    font-size: 16px;
}

.badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-type {
    background: rgba(67, 172, 233, 0.1);
    color: var(--primary-color);
}

.badge-success {
    background: rgba(40, 167, 69, 0.1);
    color: #28a745;
}

.badge-warning {
    background: rgba(255, 193, 7, 0.1);
    color: #ffc107;
}

.badge-danger {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

.badge-secondary {
    background: rgba(108, 117, 125, 0.1);
    color: #6c757d;
}

.carrier-actions .btn-group {
    border-radius: var(--border-radius);
    overflow: hidden;
}

.carrier-actions .btn {
    border-radius: 0;
    border: 1px solid var(--border-color);
    padding: 8px 12px;
    transition: var(--transition);
}

.carrier-actions .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.carrier-actions .btn:first-child {
    border-top-left-radius: var(--border-radius);
    border-bottom-left-radius: var(--border-radius);
}

.carrier-actions .btn:last-child {
    border-top-right-radius: var(--border-radius);
    border-bottom-right-radius: var(--border-radius);
}

/* Search Box */
.search-box {
    position: relative;
}

.search-box i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-light);
    z-index: 1;
}

.search-box input {
    padding-left: 40px;
    border-radius: var(--border-radius);
    border: 2px solid var(--border-color);
    transition: var(--transition);
}

.search-box input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(67, 172, 233, 0.15);
}

/* Empty State */
.empty-state {
    padding: 40px 20px;
}

.empty-state i {
    opacity: 0.3;
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.carrier-card {
    animation: fadeInUp 0.6s ease-out;
}

.carrier-card:nth-child(1) { animation-delay: 0.1s; }
.carrier-card:nth-child(2) { animation-delay: 0.2s; }
.carrier-card:nth-child(3) { animation-delay: 0.3s; }
.carrier-card:nth-child(4) { animation-delay: 0.4s; }
.carrier-card:nth-child(5) { animation-delay: 0.5s; }
.carrier-card:nth-child(6) { animation-delay: 0.6s; }

/* Responsive */
@media (max-width: 768px) {
    .carrier-actions .btn-group {
        flex-direction: column;
    }
    
    .carrier-actions .btn {
        border-radius: var(--border-radius);
        margin-bottom: 4px;
    }
    
    .carrier-actions .btn:last-child {
        margin-bottom: 0;
    }
    
    .detail-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 4px;
    }
}
</style>

<script>
function deleteCarrier(carrierId, carrierName) {
    document.getElementById('carrierNameToDelete').textContent = carrierName;
    document.getElementById('deleteCarrierForm').action = `/admin/shipping/carriers/${carrierId}`;
    new bootstrap.Modal(document.getElementById('deleteCarrierModal')).show();
}

// Search and Filter Functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const typeFilter = document.getElementById('typeFilter');
    const carrierCards = document.querySelectorAll('.carrier-card');

    function filterCarriers() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;
        const typeValue = typeFilter.value;

        carrierCards.forEach(card => {
            const name = card.dataset.name;
            const status = card.dataset.status;
            const type = card.dataset.type;

            const matchesSearch = name.includes(searchTerm);
            const matchesStatus = !statusValue || status === statusValue;
            const matchesType = !typeValue || type === typeValue;

            if (matchesSearch && matchesStatus && matchesType) {
                card.style.display = 'block';
                card.style.animation = 'fadeInUp 0.3s ease-out';
            } else {
                card.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterCarriers);
    statusFilter.addEventListener('change', filterCarriers);
    typeFilter.addEventListener('change', filterCarriers);
});

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('typeFilter').value = '';
    
    document.querySelectorAll('.carrier-card').forEach(card => {
        card.style.display = 'block';
    });
}
</script>
@endsection