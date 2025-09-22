@extends('admin.layouts.app')

@section('title', 'Products Management')

@section('content')
    <div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Products Management</h1>
            <p class="text-muted mb-0">Manage your product catalog and inventory</p>
        </div>
        <div>
            <a href="/admin/products/simple/create-new" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Product
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
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="stat-content">
                        <h2>156</h2>
                        <h6>Total Products</h6>
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
                        <h2>142</h2>
                        <h6>Active Products</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stat-content">
                        <h2>$24.5K</h2>
                        <h6>Total Value</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div class="stat-content">
                        <h2>1.2K</h2>
                        <h6>Views Today</h6>
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
                        <input type="text" id="searchInput" placeholder="Search products..." class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex gap-2 justify-content-end">
                        <select id="statusFilter" class="form-select" style="width: auto;">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="draft">Draft</option>
                        </select>
                        <select id="categoryFilter" class="form-select" style="width: auto;">
                            <option value="">All Categories</option>
                            <option value="electronics">Electronics</option>
                            <option value="clothing">Clothing</option>
                            <option value="home">Home & Garden</option>
                            <option value="sports">Sports</option>
                        </select>
                        <button class="btn btn-outline-secondary" onclick="resetFilters()">
                            <i class="fas fa-refresh"></i>
                        </button>
                    </div>
                </div>
                        </div>
                    </div>
                </div>

    <!-- Products Grid -->
    <div class="row" id="productsGrid">
        <!-- Sample Products -->
        <div class="col-xl-4 col-lg-6 mb-4 product-card" data-name="iphone 15 pro" data-status="active" data-category="electronics">
            <div class="card product-item h-100">
                <div class="product-image">
                    <img src="https://via.placeholder.com/300x200/43ACE9/ffffff?text=iPhone+15+Pro" alt="iPhone 15 Pro" class="card-img-top">
                    <div class="product-badges">
                        <span class="badge badge-featured">Featured</span>
                        <span class="badge badge-sale">Sale</span>
                                            </div>
                                        </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="product-avatar">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="card-title mb-1">iPhone 15 Pro</h5>
                            <small class="text-muted">SKU: IPH15P-001</small>
                        </div>
                        <div class="product-status">
                            <span class="status-badge status-active">
                                <i class="fas fa-circle"></i> Active
                                        </span>
                        </div>
                    </div>

                    <div class="product-details">
                        <div class="detail-row">
                            <span class="detail-label">Category:</span>
                            <span class="detail-value">
                                <span class="badge badge-category">Electronics</span>
                                        </span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Price:</span>
                            <span class="detail-value price-value">$999.00</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Stock:</span>
                            <span class="detail-value">
                                <span class="badge badge-success">In Stock (45)</span>
                                            </span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Views:</span>
                            <span class="detail-value">1,234 views</span>
                        </div>
                    </div>

                    <div class="product-actions mt-3">
                        <div class="btn-group w-100" role="group">
                            <a href="/admin/products/1/edit" class="btn btn-outline-primary btn-sm" title="Edit Product">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-outline-info btn-sm" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                            <button class="btn btn-outline-warning btn-sm" title="Duplicate">
                                <i class="fas fa-copy"></i>
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteProduct(1, 'iPhone 15 Pro')" title="Delete Product">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-6 mb-4 product-card" data-name="macbook pro m3" data-status="active" data-category="electronics">
            <div class="card product-item h-100">
                <div class="product-image">
                    <img src="https://via.placeholder.com/300x200/43ACE9/ffffff?text=MacBook+Pro+M3" alt="MacBook Pro M3" class="card-img-top">
                    <div class="product-badges">
                        <span class="badge badge-new">New</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="product-avatar">
                            <i class="fas fa-laptop"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="card-title mb-1">MacBook Pro M3</h5>
                            <small class="text-muted">SKU: MBP-M3-001</small>
                        </div>
                        <div class="product-status">
                            <span class="status-badge status-active">
                                <i class="fas fa-circle"></i> Active
                            </span>
                        </div>
                    </div>

                    <div class="product-details">
                        <div class="detail-row">
                            <span class="detail-label">Category:</span>
                            <span class="detail-value">
                                <span class="badge badge-category">Electronics</span>
                            </span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Price:</span>
                            <span class="detail-value price-value">$1,999.00</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Stock:</span>
                            <span class="detail-value">
                                <span class="badge badge-warning">Low Stock (3)</span>
                            </span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Views:</span>
                            <span class="detail-value">856 views</span>
                        </div>
                    </div>

                    <div class="product-actions mt-3">
                        <div class="btn-group w-100" role="group">
                            <a href="/admin/products/2/edit" class="btn btn-outline-primary btn-sm" title="Edit Product">
                                                <i class="fas fa-edit"></i>
                                            </a>
                            <button class="btn btn-outline-info btn-sm" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-outline-warning btn-sm" title="Duplicate">
                                <i class="fas fa-copy"></i>
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteProduct(2, 'MacBook Pro M3')" title="Delete Product">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                    </div>
                </div>
        </div>
    </div>

        <div class="col-xl-4 col-lg-6 mb-4 product-card" data-name="nike air max" data-status="active" data-category="clothing">
            <div class="card product-item h-100">
                <div class="product-image">
                    <img src="https://via.placeholder.com/300x200/43ACE9/ffffff?text=Nike+Air+Max" alt="Nike Air Max" class="card-img-top">
                    <div class="product-badges">
                        <span class="badge badge-featured">Featured</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="product-avatar">
                            <i class="fas fa-shoe-prints"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="card-title mb-1">Nike Air Max 270</h5>
                            <small class="text-muted">SKU: NAM-270-001</small>
                        </div>
                        <div class="product-status">
                            <span class="status-badge status-active">
                                <i class="fas fa-circle"></i> Active
                            </span>
                        </div>
                    </div>

                    <div class="product-details">
                        <div class="detail-row">
                            <span class="detail-label">Category:</span>
                            <span class="detail-value">
                                <span class="badge badge-category">Clothing</span>
                            </span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Price:</span>
                            <span class="detail-value price-value">$150.00</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Stock:</span>
                            <span class="detail-value">
                                <span class="badge badge-success">In Stock (28)</span>
                            </span>
                            </div>
                        <div class="detail-row">
                            <span class="detail-label">Views:</span>
                            <span class="detail-value">2,156 views</span>
                            </div>
                        </div>

                    <div class="product-actions mt-3">
                        <div class="btn-group w-100" role="group">
                            <a href="/admin/products/3/edit" class="btn btn-outline-primary btn-sm" title="Edit Product">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-outline-info btn-sm" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-outline-warning btn-sm" title="Duplicate">
                                <i class="fas fa-copy"></i>
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteProduct(3, 'Nike Air Max 270')" title="Delete Product">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                                    </div>
                                    </div>
                                    </div>
                                    </div>
                                </div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteProductModalLabel">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>Confirm Delete
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning!</strong> This action cannot be undone.
                                    </div>
                <p>Are you sure you want to delete the product <strong id="productNameToDelete"></strong>?</p>
                <p class="text-muted">This will remove the product and all its associated data.</p>
                                    </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteProductForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Delete Product
                    </button>
                </form>
                                    </div>
                                    </div>
                                </div>
                            </div>

<style>
/* Product Cards Styling */
.product-card {
    transition: var(--transition);
}

.product-item {
    border: none;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.product-item::before {
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

.product-item:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-hover);
}

.product-item:hover::before {
    opacity: 1;
}

.product-image {
    position: relative;
    overflow: hidden;
    border-radius: var(--border-radius) var(--border-radius) 0 0;
}

.product-image img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    transition: var(--transition);
}

.product-item:hover .product-image img {
    transform: scale(1.05);
}

.product-badges {
    position: absolute;
    top: 12px;
    left: 12px;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.badge-featured {
    background: linear-gradient(135deg, #ff6b6b, #ee5a24);
    color: white;
    font-size: 10px;
    padding: 4px 8px;
    border-radius: 12px;
    font-weight: 600;
}

.badge-sale {
    background: linear-gradient(135deg, #ff9ff3, #f368e0);
    color: white;
    font-size: 10px;
    padding: 4px 8px;
    border-radius: 12px;
    font-weight: 600;
}

.badge-new {
    background: linear-gradient(135deg, #54a0ff, #2e86de);
    color: white;
    font-size: 10px;
    padding: 4px 8px;
    border-radius: 12px;
    font-weight: 600;
}

.product-avatar {
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

.product-status {
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

.product-details {
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

.price-value {
    color: var(--primary-color);
    font-size: 16px;
}

.badge-category {
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

.product-actions .btn-group {
    border-radius: var(--border-radius);
    overflow: hidden;
}

.product-actions .btn {
    border-radius: 0;
    border: 1px solid var(--border-color);
    padding: 8px 12px;
    transition: var(--transition);
}

.product-actions .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.product-actions .btn:first-child {
    border-top-left-radius: var(--border-radius);
    border-bottom-left-radius: var(--border-radius);
}

.product-actions .btn:last-child {
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

.product-card {
    animation: fadeInUp 0.6s ease-out;
}

.product-card:nth-child(1) { animation-delay: 0.1s; }
.product-card:nth-child(2) { animation-delay: 0.2s; }
.product-card:nth-child(3) { animation-delay: 0.3s; }
.product-card:nth-child(4) { animation-delay: 0.4s; }
.product-card:nth-child(5) { animation-delay: 0.5s; }
.product-card:nth-child(6) { animation-delay: 0.6s; }

/* Responsive */
@media (max-width: 768px) {
    .product-actions .btn-group {
        flex-direction: column;
    }
    
    .product-actions .btn {
        border-radius: var(--border-radius);
        margin-bottom: 4px;
    }
    
    .product-actions .btn:last-child {
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
function deleteProduct(productId, productName) {
    document.getElementById('productNameToDelete').textContent = productName;
    document.getElementById('deleteProductForm').action = `/admin/products/${productId}`;
    new bootstrap.Modal(document.getElementById('deleteProductModal')).show();
}

// Search and Filter Functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const categoryFilter = document.getElementById('categoryFilter');
    const productCards = document.querySelectorAll('.product-card');

    function filterProducts() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;
        const categoryValue = categoryFilter.value;

        productCards.forEach(card => {
            const name = card.dataset.name;
            const status = card.dataset.status;
            const category = card.dataset.category;

            const matchesSearch = name.includes(searchTerm);
            const matchesStatus = !statusValue || status === statusValue;
            const matchesCategory = !categoryValue || category === categoryValue;

            if (matchesSearch && matchesStatus && matchesCategory) {
                card.style.display = 'block';
                card.style.animation = 'fadeInUp 0.3s ease-out';
            } else {
                card.style.display = 'none';
                }
            });
        }
        
    searchInput.addEventListener('input', filterProducts);
    statusFilter.addEventListener('change', filterProducts);
    categoryFilter.addEventListener('change', filterProducts);
});

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('categoryFilter').value = '';
    
    document.querySelectorAll('.product-card').forEach(card => {
        card.style.display = 'block';
            });
        }
    </script>
@endsection
