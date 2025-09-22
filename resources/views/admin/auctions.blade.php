@extends('admin.layouts.app')

@section('title', 'Auctions Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-gavel me-2"></i>
                    Auctions Management
                </h5>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <a href="/admin/auctions/create" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i>
                            Create Auction
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Auction</th>
                                    <th>Type</th>
                                    <th>Starting Price</th>
                                    <th>Current Bid</th>
                                    <th>Reserve Price</th>
                                    <th>Buy Now</th>
                                    <th>Bid Count</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Status</th>
                                    <th>Featured</th>
                                    <th>Winner</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($auctions as $auction)
                                <tr>
                                    <td>{{ $auction->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($auction->images)
                                                @php
                                                    $images = json_decode($auction->images, true);
                                                    $firstImage = is_array($images) ? $images[0] : null;
                                                @endphp
                                                @if($firstImage)
                                                    <img src="{{ $firstImage }}" alt="Auction" class="rounded me-2" width="40" height="40">
                                                @endif
                                            @endif
                                            <div>
                                                <strong>{{ $auction->title }}</strong>
                                                <br>
                                                <small class="text-muted">{{ Str::limit($auction->description, 50) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($auction->type) }}</span>
                                    </td>
                                    <td>
                                        <strong>${{ number_format($auction->starting_price, 2) }}</strong>
                                    </td>
                                    <td>
                                        @if($auction->current_bid)
                                            <strong class="text-success">${{ number_format($auction->current_bid, 2) }}</strong>
                                        @else
                                            <span class="text-muted">No bids</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($auction->reserve_price)
                                            <span class="text-warning">${{ number_format($auction->reserve_price, 2) }}</span>
                                        @else
                                            <span class="text-muted">No reserve</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($auction->buy_now_price)
                                            <span class="text-primary">${{ number_format($auction->buy_now_price, 2) }}</span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $auction->bid_count }}</span>
                                    </td>
                                    <td>
                                        @if($auction->start_time)
                                            {{ \Carbon\Carbon::parse($auction->start_time)->format('M d, Y H:i') }}
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($auction->end_time)
                                            <div class="countdown">
                                                {{ \Carbon\Carbon::parse($auction->end_time)->format('M d, Y H:i') }}
                                            </div>
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $auction->status == 'active' ? 'success' : ($auction->status == 'ended' ? 'danger' : ($auction->status == 'cancelled' ? 'secondary' : 'warning')) }}">
                                            {{ ucfirst($auction->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($auction->is_featured)
                                            <span class="badge bg-warning">
                                                <i class="fas fa-star"></i> Featured
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">Regular</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($auction->winner_id)
                                            <span class="badge bg-success">
                                                <i class="fas fa-trophy"></i> Won
                                            </span>
                                        @else
                                            <span class="text-muted">No winner</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" title="View" onclick="viewAuction({{ $auction->id }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a href="/admin/auctions/{{ $auction->id }}/edit" class="btn btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-outline-info" title="Bids" onclick="viewBids({{ $auction->id }})">
                                                <i class="fas fa-gavel"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" title="Delete" onclick="deleteAuction({{ $auction->id }}, '{{ $auction->title }}')">
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
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
        // View Auction Details
        function viewAuction(id) {
            fetch(`/api/admin/auctions/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const auction = data.data;
                        Swal.fire({
                            title: auction.title,
                            html: `
                                <div class="text-start">
                                    <p><strong>Product:</strong> ${auction.product_name}</p>
                                    <p><strong>Vendor:</strong> ${auction.business_name}</p>
                                    <p><strong>Type:</strong> <span class="badge bg-info">${auction.type}</span></p>
                                    <p><strong>Starting Price:</strong> $${parseFloat(auction.starting_price).toFixed(2)}</p>
                                    <p><strong>Current Bid:</strong> $${parseFloat(auction.current_bid || auction.starting_price).toFixed(2)}</p>
                                    <p><strong>Reserve Price:</strong> ${auction.reserve_price ? '$' + parseFloat(auction.reserve_price).toFixed(2) : 'No reserve'}</p>
                                    <p><strong>Buy Now Price:</strong> ${auction.buy_now_price ? '$' + parseFloat(auction.buy_now_price).toFixed(2) : 'N/A'}</p>
                                    <p><strong>Bid Count:</strong> ${auction.bid_count}</p>
                                    <p><strong>Status:</strong> <span class="badge bg-${auction.status === 'active' ? 'success' : 'warning'}">${auction.status}</span></p>
                                    <p><strong>Start Time:</strong> ${new Date(auction.start_time).toLocaleString()}</p>
                                    <p><strong>End Time:</strong> ${new Date(auction.end_time).toLocaleString()}</p>
                                    <p><strong>Auto Extend:</strong> ${auction.auto_extend ? 'Yes' : 'No'}</p>
                                    <p><strong>Featured:</strong> ${auction.is_featured ? 'Yes' : 'No'}</p>
                                </div>
                            `,
                            showCloseButton: true,
                            showConfirmButton: false,
                            width: 700
                        });
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error', 'Failed to fetch auction details', 'error');
                });
        }

        // Edit Auction
        function editAuction(id) {
            fetch(`/api/admin/auctions/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const auction = data.data;
                        Swal.fire({
                            title: 'Edit Auction',
                            html: `
                                <form id="editForm">
                                    <div class="mb-3">
                                        <label class="form-label">Title</label>
                                        <input type="text" class="form-control" id="title" value="${auction.title}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" id="description" rows="3" required>${auction.description}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Type</label>
                                        <select class="form-control" id="type">
                                            <option value="english" ${auction.type === 'english' ? 'selected' : ''}>English Auction</option>
                                            <option value="reserve" ${auction.type === 'reserve' ? 'selected' : ''}>Reserve Auction</option>
                                            <option value="buy_now" ${auction.type === 'buy_now' ? 'selected' : ''}>Buy It Now</option>
                                            <option value="private" ${auction.type === 'private' ? 'selected' : ''}>Private Offer</option>
                                        </select>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Starting Price</label>
                                                <input type="number" class="form-control" id="starting_price" value="${auction.starting_price}" step="0.01" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Reserve Price</label>
                                                <input type="number" class="form-control" id="reserve_price" value="${auction.reserve_price || ''}" step="0.01">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Buy Now Price</label>
                                                <input type="number" class="form-control" id="buy_now_price" value="${auction.buy_now_price || ''}" step="0.01">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Bid Increment</label>
                                        <input type="number" class="form-control" id="bid_increment" value="${auction.bid_increment}" step="0.01" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Start Time</label>
                                                <input type="datetime-local" class="form-control" id="start_time" value="${new Date(auction.start_time).toISOString().slice(0, 16)}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">End Time</label>
                                                <input type="datetime-local" class="form-control" id="end_time" value="${new Date(auction.end_time).toISOString().slice(0, 16)}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select class="form-control" id="status">
                                            <option value="scheduled" ${auction.status === 'scheduled' ? 'selected' : ''}>Scheduled</option>
                                            <option value="active" ${auction.status === 'active' ? 'selected' : ''}>Active</option>
                                            <option value="ended" ${auction.status === 'ended' ? 'selected' : ''}>Ended</option>
                                            <option value="cancelled" ${auction.status === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                                        </select>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="is_featured" ${auction.is_featured ? 'checked' : ''}>
                                                <label class="form-check-label" for="is_featured">Featured Auction</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="auto_extend" ${auction.auto_extend ? 'checked' : ''}>
                                                <label class="form-check-label" for="auto_extend">Auto Extend</label>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            `,
                            showCancelButton: true,
                            confirmButtonText: 'Update',
                            cancelButtonText: 'Cancel',
                            preConfirm: () => {
                                const formData = {
                                    title: document.getElementById('title').value,
                                    description: document.getElementById('description').value,
                                    type: document.getElementById('type').value,
                                    starting_price: document.getElementById('starting_price').value,
                                    reserve_price: document.getElementById('reserve_price').value,
                                    buy_now_price: document.getElementById('buy_now_price').value,
                                    bid_increment: document.getElementById('bid_increment').value,
                                    start_time: document.getElementById('start_time').value,
                                    end_time: document.getElementById('end_time').value,
                                    status: document.getElementById('status').value,
                                    is_featured: document.getElementById('is_featured').checked,
                                    auto_extend: document.getElementById('auto_extend').checked
                                };

                                return fetch(`/api/admin/auctions/${id}`, {
                                    method: 'PUT',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    },
                                    body: JSON.stringify(formData)
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (!data.success) {
                                        throw new Error(data.message);
                                    }
                                    return data;
                                });
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                Swal.fire('Success!', 'Auction updated successfully', 'success').then(() => {
                                    location.reload();
                                });
                            }
                        }).catch(error => {
                            Swal.fire('Error', error.message, 'error');
                        });
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error', 'Failed to fetch auction details', 'error');
                });
        }

        // View Bids
        function viewBids(id) {
            fetch(`/api/admin/auctions/${id}/bids`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const bids = data.data;
                        let bidsHtml = '<div class="text-start">';
                        
                        if (bids.length === 0) {
                            bidsHtml += '<p class="text-muted">No bids placed yet.</p>';
                        } else {
                            bidsHtml += '<div class="table-responsive"><table class="table table-sm">';
                            bidsHtml += '<thead><tr><th>Bidder</th><th>Amount</th><th>Time</th><th>Type</th></tr></thead><tbody>';
                            
                            bids.forEach(bid => {
                                bidsHtml += `
                                    <tr>
                                        <td>${bid.bidder_name}</td>
                                        <td><strong>$${parseFloat(bid.amount).toFixed(2)}</strong></td>
                                        <td>${new Date(bid.created_at).toLocaleString()}</td>
                                        <td><span class="badge bg-${bid.is_proxy_bid ? 'info' : 'primary'}">${bid.is_proxy_bid ? 'Proxy' : 'Manual'}</span></td>
                                    </tr>
                                `;
                            });
                            
                            bidsHtml += '</tbody></table></div>';
                        }
                        
                        bidsHtml += '</div>';

                        Swal.fire({
                            title: 'Auction Bids',
                            html: bidsHtml,
                            showCloseButton: true,
                            showConfirmButton: false,
                            width: 800
                        });
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error', 'Failed to fetch auction bids', 'error');
                });
        }

        // Delete Auction
        function deleteAuction(id, title) {
            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete auction "${title}". This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/api/admin/auctions/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Deleted!', 'Auction has been deleted successfully', 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error', 'Failed to delete auction', 'error');
                    });
                }
            });
        }

        // Add Auction
        function addAuction() {
            Swal.fire({
                title: 'Create New Auction',
                html: `
                    <form id="addForm">
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" id="description" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <select class="form-control" id="type">
                                <option value="english">English Auction</option>
                                <option value="reserve">Reserve Auction</option>
                                <option value="buy_now">Buy It Now</option>
                                <option value="private">Private Offer</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Starting Price</label>
                                    <input type="number" class="form-control" id="starting_price" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Reserve Price</label>
                                    <input type="number" class="form-control" id="reserve_price" step="0.01">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Buy Now Price</label>
                                    <input type="number" class="form-control" id="buy_now_price" step="0.01">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Bid Increment</label>
                            <input type="number" class="form-control" id="bid_increment" value="1.00" step="0.01" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Start Time</label>
                                    <input type="datetime-local" class="form-control" id="start_time" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">End Time</label>
                                    <input type="datetime-local" class="form-control" id="end_time" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_featured">
                                    <label class="form-check-label" for="is_featured">Featured Auction</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="auto_extend">
                                    <label class="form-check-label" for="auto_extend">Auto Extend</label>
                                </div>
                            </div>
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Create',
                cancelButtonText: 'Cancel',
                preConfirm: () => {
                    const formData = {
                        tenant_id: 1, // Default to first tenant
                        vendor_id: 1, // Default to first vendor
                        product_id: 1, // Default to first product
                        title: document.getElementById('title').value,
                        description: document.getElementById('description').value,
                        type: document.getElementById('type').value,
                        starting_price: document.getElementById('starting_price').value,
                        reserve_price: document.getElementById('reserve_price').value,
                        buy_now_price: document.getElementById('buy_now_price').value,
                        bid_increment: document.getElementById('bid_increment').value,
                        start_time: document.getElementById('start_time').value,
                        end_time: document.getElementById('end_time').value,
                        is_featured: document.getElementById('is_featured').checked,
                        auto_extend: document.getElementById('auto_extend').checked
                    };

                    return fetch('/api/admin/auctions', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(formData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            throw new Error(data.message);
                        }
                        return data;
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Success!', 'Auction created successfully', 'success').then(() => {
                        location.reload();
                    });
                }
            }).catch(error => {
                Swal.fire('Error', error.message, 'error');
            });
        }
</script>
@endsection
