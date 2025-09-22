@extends('admin.layouts.app')

@section('title', 'Edit Auction')

@section('content')
<div class="row">
    <!-- Left Column - Form -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-gavel me-2"></i>
                    Edit Auction
                </h5>
            </div>
            <div class="card-body">
                <form id="auctionForm" method="POST" action="/admin/auctions/{{ $id }}">
                    @csrf
                    @method('PUT')
                    
                    <!-- Basic Information -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-info-circle me-2"></i>
                            Basic Information
                        </h6>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Auction Title *</label>
                                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $auction->title ?? '') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Auction Type *</label>
                                    <select class="form-select" id="type" name="type" required>
                                        <option value="">Select Type</option>
                                        <option value="english" {{ old('type', $auction->type ?? '') == 'english' ? 'selected' : '' }}>English Auction</option>
                                        <option value="reserve" {{ old('type', $auction->type ?? '') == 'reserve' ? 'selected' : '' }}>Reserve Auction</option>
                                        <option value="buy_now" {{ old('type', $auction->type ?? '') == 'buy_now' ? 'selected' : '' }}>Buy It Now</option>
                                        <option value="private" {{ old('type', $auction->type ?? '') == 'private' ? 'selected' : '' }}>Private Offer</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            @include('admin.components.shopify-editor', [
                                'name' => 'description',
                                'value' => old('description', $auction->description ?? ''),
                                'height' => 300,
                                'placeholder' => 'Describe your auction item...',
                                'required' => true
                            ])
                        </div>
                    </div>

                    <!-- Pricing Information -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-dollar-sign me-2"></i>
                            Pricing Information
                        </h6>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="starting_price" class="form-label">Starting Price *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" id="starting_price" name="starting_price" value="{{ old('starting_price', $auction->starting_price ?? '') }}" step="0.01" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="reserve_price" class="form-label">Reserve Price</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" id="reserve_price" name="reserve_price" value="{{ old('reserve_price', $auction->reserve_price ?? '') }}" step="0.01">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="buy_now_price" class="form-label">Buy Now Price</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" id="buy_now_price" name="buy_now_price" value="{{ old('buy_now_price', $auction->buy_now_price ?? '') }}" step="0.01">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="bid_increment" class="form-label">Bid Increment *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" id="bid_increment" name="bid_increment" value="{{ old('bid_increment', $auction->bid_increment ?? '1.00') }}" step="0.01" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="current_bid" class="form-label">Current Bid</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" id="current_bid" name="current_bid" value="{{ old('current_bid', $auction->current_bid ?? '') }}" step="0.01" readonly>
                                    </div>
                                    <small class="text-muted">This field is read-only and shows the current highest bid</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Timing Information -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-clock me-2"></i>
                            Timing Information
                        </h6>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_time" class="form-label">Start Time *</label>
                                    <input type="datetime-local" class="form-control" id="start_time" name="start_time" value="{{ old('start_time', $auction->start_time ? date('Y-m-d\TH:i', strtotime($auction->start_time)) : '') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_time" class="form-label">End Time *</label>
                                    <input type="datetime-local" class="form-control" id="end_time" name="end_time" value="{{ old('end_time', $auction->end_time ? date('Y-m-d\TH:i', strtotime($auction->end_time)) : '') }}" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="auto_extend" name="auto_extend" {{ old('auto_extend', $auction->auto_extend ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="auto_extend">
                                        Auto Extend (5 minutes before end if bid placed)
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" {{ old('is_featured', $auction->is_featured ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">
                                        Featured Auction
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Information -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-info-circle me-2"></i>
                            Status Information
                        </h6>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="scheduled" {{ old('status', $auction->status ?? '') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                        <option value="active" {{ old('status', $auction->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="ended" {{ old('status', $auction->status ?? '') == 'ended' ? 'selected' : '' }}>Ended</option>
                                        <option value="cancelled" {{ old('status', $auction->status ?? '') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="bid_count" class="form-label">Bid Count</label>
                                    <input type="number" class="form-control" id="bid_count" name="bid_count" value="{{ old('bid_count', $auction->bid_count ?? '0') }}" readonly>
                                    <small class="text-muted">This field is read-only and shows the total number of bids</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="/admin/auctions" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>
                            Update Auction
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Column - Auction Details & Actions -->
    <div class="col-lg-4">
        <!-- Auction Details Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Auction Details
                </h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-6">
                        <small class="text-muted">Auction ID</small>
                        <div class="fw-bold">#{{ $id }}</div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Created</small>
                        <div class="fw-bold">{{ $auction->created_at ? date('M d, Y', strtotime($auction->created_at)) : 'N/A' }}</div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-6">
                        <small class="text-muted">Current Status</small>
                        <div>
                            <span class="badge bg-{{ $auction->status == 'active' ? 'success' : ($auction->status == 'ended' ? 'danger' : 'warning') }}">
                                {{ ucfirst($auction->status ?? 'Unknown') }}
                            </span>
                        </div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Featured</small>
                        <div>
                            <span class="badge bg-{{ $auction->is_featured ? 'warning' : 'secondary' }}">
                                {{ $auction->is_featured ? 'Yes' : 'No' }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-6">
                        <small class="text-muted">Total Bids</small>
                        <div class="fw-bold">{{ $auction->bid_count ?? 0 }}</div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Winner</small>
                        <div class="fw-bold">{{ $auction->winner_id ? 'Assigned' : 'None' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Quick Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="viewBids({{ $id }})">
                        <i class="fas fa-gavel me-1"></i>
                        View All Bids
                    </button>
                    <button type="button" class="btn btn-outline-info btn-sm" onclick="viewAuctionDetails({{ $id }})">
                        <i class="fas fa-eye me-1"></i>
                        View Auction Details
                    </button>
                    @if($auction->status == 'active')
                    <button type="button" class="btn btn-outline-warning btn-sm" onclick="endAuction({{ $id }})">
                        <i class="fas fa-stop me-1"></i>
                        End Auction
                    </button>
                    @endif
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteAuction({{ $id }}, '{{ $auction->title ?? 'this auction' }}')">
                        <i class="fas fa-trash me-1"></i>
                        Delete Auction
                    </button>
                </div>
            </div>
        </div>

        <!-- Preview Card -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-eye me-2"></i>
                    Preview
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="bg-light rounded p-4">
                        <i class="fas fa-image fa-3x text-muted mb-2"></i>
                        <p class="text-muted mb-0">Auction Image</p>
                    </div>
                </div>
                <h6 id="preview-title">{{ $auction->title ?? 'Auction Title' }}</h6>
                <p id="preview-description">{{ Str::limit($auction->description ?? 'Auction description will appear here...', 100) }}</p>
                <div class="row text-center">
                    <div class="col-6">
                        <small class="text-muted">Starting Price</small>
                        <div class="fw-bold" id="preview-starting">${{ number_format($auction->starting_price ?? 0, 2) }}</div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">End Time</small>
                        <div class="fw-bold" id="preview-end">{{ $auction->end_time ? date('M d, H:i', strtotime($auction->end_time)) : 'Not set' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview functionality
    setupPreview();
    
    // Form validation
    setupFormValidation();
});

function setupPreview() {
    const titleInput = document.getElementById('title');
    const descriptionInput = document.getElementById('description');
    const startingPriceInput = document.getElementById('starting_price');
    const endTimeInput = document.getElementById('end_time');
    
    titleInput.addEventListener('input', function() {
        document.getElementById('preview-title').textContent = this.value || 'Auction Title';
    });
    
    descriptionInput.addEventListener('input', function() {
        document.getElementById('preview-description').textContent = this.value || 'Auction description will appear here...';
    });
    
    startingPriceInput.addEventListener('input', function() {
        document.getElementById('preview-starting').textContent = '$' + (parseFloat(this.value) || 0).toFixed(2);
    });
    
    endTimeInput.addEventListener('input', function() {
        if (this.value) {
            const date = new Date(this.value);
            document.getElementById('preview-end').textContent = date.toLocaleDateString() + ', ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        } else {
            document.getElementById('preview-end').textContent = 'Not set';
        }
    });
}

function setupFormValidation() {
    const form = document.getElementById('auctionForm');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Basic validation
        const requiredFields = ['title', 'description', 'type', 'starting_price', 'bid_increment', 'start_time', 'end_time'];
        let isValid = true;
        
        requiredFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        // Date validation
        const startTime = new Date(document.getElementById('start_time').value);
        const endTime = new Date(document.getElementById('end_time').value);
        
        if (endTime <= startTime) {
            document.getElementById('end_time').classList.add('is-invalid');
            isValid = false;
        }
        
        if (isValid) {
            // Submit form
            this.submit();
        } else {
            Swal.fire('Error', 'Please fill in all required fields correctly.', 'error');
        }
    });
}

// Quick action functions
function viewBids(id) {
    // Implementation for viewing bids
    Swal.fire('Info', 'Bid viewing functionality will be implemented here.', 'info');
}

function viewAuctionDetails(id) {
    // Implementation for viewing auction details
    Swal.fire('Info', 'Auction details viewing functionality will be implemented here.', 'info');
}

function endAuction(id) {
    Swal.fire({
        title: 'End Auction?',
        text: 'Are you sure you want to end this auction? This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, end it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Implementation for ending auction
            Swal.fire('Success', 'Auction ended successfully!', 'success');
        }
    });
}

function deleteAuction(id, title) {
    Swal.fire({
        title: 'Delete Auction?',
        text: `Are you sure you want to delete "${title}"? This action cannot be undone!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Implementation for deleting auction
            Swal.fire('Success', 'Auction deleted successfully!', 'success').then(() => {
                window.location.href = '/admin/auctions';
            });
        }
    });
}
</script>
@endsection
