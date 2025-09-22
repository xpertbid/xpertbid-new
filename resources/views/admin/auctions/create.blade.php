@extends('admin.layouts.app')

@section('title', 'Create New Auction')

@section('content')
<div class="row">
    <!-- Left Column - Form -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-gavel me-2"></i>
                    Create New Auction
                </h5>
            </div>
            <div class="card-body">
                <form id="auctionForm" method="POST" action="/admin/auctions">
                    @csrf
                    
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
                                    <input type="text" class="form-control" id="title" name="title" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Auction Type *</label>
                                    <select class="form-select" id="type" name="type" required>
                                        <option value="">Select Type</option>
                                        <option value="english">English Auction</option>
                                        <option value="reserve">Reserve Auction</option>
                                        <option value="buy_now">Buy It Now</option>
                                        <option value="private">Private Offer</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            @include('admin.components.shopify-editor', [
                                'name' => 'description',
                                'value' => old('description'),
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
                                        <input type="number" class="form-control" id="starting_price" name="starting_price" step="0.01" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="reserve_price" class="form-label">Reserve Price</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" id="reserve_price" name="reserve_price" step="0.01">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="buy_now_price" class="form-label">Buy Now Price</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" id="buy_now_price" name="buy_now_price" step="0.01">
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
                                        <input type="number" class="form-control" id="bid_increment" name="bid_increment" value="1.00" step="0.01" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="currency" class="form-label">Currency</label>
                                    <select class="form-select" id="currency" name="currency">
                                        <option value="USD">USD - US Dollar</option>
                                        <option value="EUR">EUR - Euro</option>
                                        <option value="GBP">GBP - British Pound</option>
                                        <option value="CAD">CAD - Canadian Dollar</option>
                                    </select>
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
                                    <input type="datetime-local" class="form-control" id="start_time" name="start_time" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_time" class="form-label">End Time *</label>
                                    <input type="datetime-local" class="form-control" id="end_time" name="end_time" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="auto_extend" name="auto_extend">
                                    <label class="form-check-label" for="auto_extend">
                                        Auto Extend (5 minutes before end if bid placed)
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured">
                                    <label class="form-check-label" for="is_featured">
                                        Featured Auction
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Information -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-box me-2"></i>
                            Product Information
                        </h6>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="product_id" class="form-label">Product *</label>
                                    <select class="form-select" id="product_id" name="product_id" required>
                                        <option value="">Select Product</option>
                                        <!-- Products will be loaded via AJAX -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="vendor_id" class="form-label">Vendor *</label>
                                    <select class="form-select" id="vendor_id" name="vendor_id" required>
                                        <option value="">Select Vendor</option>
                                        <!-- Vendors will be loaded via AJAX -->
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Settings -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-cog me-2"></i>
                            Additional Settings
                        </h6>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="scheduled">Scheduled</option>
                                        <option value="active">Active</option>
                                        <option value="draft">Draft</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Category</label>
                                    <select class="form-select" id="category_id" name="category_id">
                                        <option value="">Select Category</option>
                                        <!-- Categories will be loaded via AJAX -->
                                    </select>
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
                            Create Auction
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Column - Preview & Help -->
    <div class="col-lg-4">
        <!-- Preview Card -->
        <div class="card mb-4">
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
                        <p class="text-muted mb-0">No image selected</p>
                    </div>
                </div>
                <h6 id="preview-title" class="text-muted">Auction Title</h6>
                <p id="preview-description" class="text-muted small">Auction description will appear here...</p>
                <div class="row text-center">
                    <div class="col-6">
                        <small class="text-muted">Starting Price</small>
                        <div class="fw-bold" id="preview-starting">$0.00</div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">End Time</small>
                        <div class="fw-bold" id="preview-end">Not set</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Help Card -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-question-circle me-2"></i>
                    Help & Tips
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="text-primary">Auction Types:</h6>
                    <ul class="small mb-0">
                        <li><strong>English:</strong> Traditional ascending bid auction</li>
                        <li><strong>Reserve:</strong> Minimum price must be met</li>
                        <li><strong>Buy Now:</strong> Fixed price purchase option</li>
                        <li><strong>Private:</strong> Invitation-only auction</li>
                    </ul>
                </div>
                
                <div class="mb-3">
                    <h6 class="text-primary">Pricing Tips:</h6>
                    <ul class="small mb-0">
                        <li>Set competitive starting prices</li>
                        <li>Use reserve prices for valuable items</li>
                        <li>Consider buy-now options for quick sales</li>
                    </ul>
                </div>
                
                <div class="mb-0">
                    <h6 class="text-primary">Timing Tips:</h6>
                    <ul class="small mb-0">
                        <li>Allow sufficient time for bidding</li>
                        <li>Consider time zones for international buyers</li>
                        <li>Enable auto-extend for active auctions</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load products, vendors, and categories
    loadProducts();
    loadVendors();
    loadCategories();
    
    // Preview functionality
    setupPreview();
    
    // Form validation
    setupFormValidation();
});

function loadProducts() {
    // Mock data - replace with actual API call
    const products = [
        { id: 1, name: 'Vintage Watch Collection' },
        { id: 2, name: 'Antique Furniture Set' },
        { id: 3, name: 'Rare Coin Collection' },
        { id: 4, name: 'Artwork by Famous Artist' }
    ];
    
    const select = document.getElementById('product_id');
    products.forEach(product => {
        const option = document.createElement('option');
        option.value = product.id;
        option.textContent = product.name;
        select.appendChild(option);
    });
}

function loadVendors() {
    // Mock data - replace with actual API call
    const vendors = [
        { id: 1, name: 'Antique Emporium' },
        { id: 2, name: 'Collectibles Plus' },
        { id: 3, name: 'Rare Finds Co.' },
        { id: 4, name: 'Vintage Treasures' }
    ];
    
    const select = document.getElementById('vendor_id');
    vendors.forEach(vendor => {
        const option = document.createElement('option');
        option.value = vendor.id;
        option.textContent = vendor.name;
        select.appendChild(option);
    });
}

function loadCategories() {
    // Mock data - replace with actual API call
    const categories = [
        { id: 1, name: 'Antiques' },
        { id: 2, name: 'Art & Collectibles' },
        { id: 3, name: 'Jewelry & Watches' },
        { id: 4, name: 'Electronics' }
    ];
    
    const select = document.getElementById('category_id');
    categories.forEach(category => {
        const option = document.createElement('option');
        option.value = category.id;
        option.textContent = category.name;
        select.appendChild(option);
    });
}

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
            document.getElementById('preview-end').textContent = date.toLocaleString();
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
        const requiredFields = ['title', 'description', 'type', 'starting_price', 'bid_increment', 'start_time', 'end_time', 'product_id', 'vendor_id'];
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
            alert('Please fill in all required fields correctly.');
        }
    });
}
</script>
@endsection