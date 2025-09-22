@extends('admin.layouts.app')

@section('title', 'Products Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products Management</h1>
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-primary" onclick="showAddModal()">
                <i class="fas fa-plus"></i> Add New Product
            </button>
        </div>
    </div>

    <!-- Product Type Tabs -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <ul class="nav nav-tabs card-header-tabs" id="productTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="simple-tab" data-bs-toggle="tab" data-bs-target="#simple" type="button" role="tab">
                                <i class="fas fa-box me-2"></i>Simple/Variation Products
                                <span class="badge bg-primary ms-2" id="simple-count">0</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="digital-tab" data-bs-toggle="tab" data-bs-target="#digital" type="button" role="tab">
                                <i class="fas fa-file-alt me-2"></i>Digital Products
                                <span class="badge bg-info ms-2" id="digital-count">0</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="auction-tab" data-bs-toggle="tab" data-bs-target="#auction" type="button" role="tab">
                                <i class="fas fa-gavel me-2"></i>Auction Products
                                <span class="badge bg-warning ms-2" id="auction-count">0</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="wholesale-tab" data-bs-toggle="tab" data-bs-target="#wholesale" type="button" role="tab">
                                <i class="fas fa-warehouse me-2"></i>Wholesale Products
                                <span class="badge bg-success ms-2" id="wholesale-count">0</span>
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="productTabsContent">
                        <!-- Simple/Variation Products Tab -->
                        <div class="tab-pane fade show active" id="simple" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Simple/Variation Products</h5>
                                <a href="/admin/products/simple/create" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus"></i> Add Simple Product
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="simpleProductsTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>SKU</th>
                                            <th>Brand</th>
                                            <th>Category</th>
                                            <th>Price</th>
                                            <th>Stock</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Simple products will be loaded here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Digital Products Tab -->
                        <div class="tab-pane fade" id="digital" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Digital Products</h5>
                                <a href="/admin/products/digital/create" class="btn btn-sm btn-info">
                                    <i class="fas fa-plus"></i> Add Digital Product
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="digitalProductsTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>SKU</th>
                                            <th>File Type</th>
                                            <th>Price</th>
                                            <th>Downloads</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Digital products will be loaded here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Auction Products Tab -->
                        <div class="tab-pane fade" id="auction" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Auction Products</h5>
                                <a href="/admin/products/auction/create" class="btn btn-sm btn-warning">
                                    <i class="fas fa-plus"></i> Add Auction Product
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="auctionProductsTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>SKU</th>
                                            <th>Reserve Price</th>
                                            <th>Country</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Auction products will be loaded here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Wholesale Products Tab -->
                        <div class="tab-pane fade" id="wholesale" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Wholesale Products</h5>
                                <a href="/admin/products/wholesale/create" class="btn btn-sm btn-success">
                                    <i class="fas fa-plus"></i> Add Wholesale Product
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="wholesaleProductsTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>SKU</th>
                                            <th>Unit Price</th>
                                            <th>Min Qty</th>
                                            <th>Max Qty</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Wholesale products will be loaded here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Product Type Selection Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Product Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-box fa-3x text-primary mb-3"></i>
                                <h5>Simple/Variation Product</h5>
                                <p class="text-muted">Physical products with variations</p>
                                <a href="/admin/products/simple/create" class="btn btn-primary">Create</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-file-alt fa-3x text-info mb-3"></i>
                                <h5>Digital Product</h5>
                                <p class="text-muted">Downloadable files and content</p>
                                <a href="/admin/products/digital/create" class="btn btn-info">Create</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-gavel fa-3x text-warning mb-3"></i>
                                <h5>Auction Product</h5>
                                <p class="text-muted">Products for bidding</p>
                                <a href="/admin/products/auction/create" class="btn btn-warning">Create</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-warehouse fa-3x text-success mb-3"></i>
                                <h5>Wholesale Product</h5>
                                <p class="text-muted">Bulk products with quantity pricing</p>
                                <a href="/admin/products/wholesale/create" class="btn btn-success">Create</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Load product counts
    loadProductCounts();
    
    // Load products for each tab
    loadSimpleProducts();
    loadDigitalProducts();
    loadAuctionProducts();
    loadWholesaleProducts();
});

function showAddModal() {
    $('#addProductModal').modal('show');
}

function loadProductCounts() {
    // Load counts for each product type
    $.get('/api/admin/products/counts', function(response) {
        if (response.success) {
            $('#simple-count').text(response.data.simple || 0);
            $('#digital-count').text(response.data.digital || 0);
            $('#auction-count').text(response.data.auction || 0);
            $('#wholesale-count').text(response.data.wholesale || 0);
        }
    });
}

function loadSimpleProducts() {
    $.get('/api/admin/products?type=simple', function(response) {
        if (response.success) {
            let html = '';
            response.data.forEach(function(product) {
                html += `
                    <tr>
                        <td>${product.id}</td>
                        <td><img src="${product.thumbnail_image || '/images/no-image.png'}" width="50" height="50" class="rounded"></td>
                        <td>${product.name}</td>
                        <td>${product.sku}</td>
                        <td>${product.brand_name || 'N/A'}</td>
                        <td>${product.category_name || 'N/A'}</td>
                        <td>$${product.price}</td>
                        <td>${product.stock_quantity}</td>
                        <td><span class="badge bg-${product.status === 'active' ? 'success' : 'secondary'}">${product.status}</span></td>
                        <td>
                            <a href="/admin/products/simple/${product.id}/edit" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="deleteProduct(${product.id})" class="btn btn-sm btn-outline-danger" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            $('#simpleProductsTable tbody').html(html);
        }
    });
}

function loadDigitalProducts() {
    $.get('/api/admin/products?type=digital', function(response) {
        if (response.success) {
            let html = '';
            response.data.forEach(function(product) {
                html += `
                    <tr>
                        <td>${product.id}</td>
                        <td><img src="${product.thumbnail_image || '/images/no-image.png'}" width="50" height="50" class="rounded"></td>
                        <td>${product.name}</td>
                        <td>${product.sku}</td>
                        <td>${product.digital_files ? JSON.parse(product.digital_files).length + ' files' : 'No files'}</td>
                        <td>$${product.price}</td>
                        <td>${product.download_count || 0}</td>
                        <td><span class="badge bg-${product.status === 'active' ? 'success' : 'secondary'}">${product.status}</span></td>
                        <td>
                            <a href="/admin/products/digital/${product.id}/edit" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="deleteProduct(${product.id})" class="btn btn-sm btn-outline-danger" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            $('#digitalProductsTable tbody').html(html);
        }
    });
}

function loadAuctionProducts() {
    $.get('/api/admin/products?type=auction', function(response) {
        if (response.success) {
            let html = '';
            response.data.forEach(function(product) {
                html += `
                    <tr>
                        <td>${product.id}</td>
                        <td><img src="${product.thumbnail_image || '/images/no-image.png'}" width="50" height="50" class="rounded"></td>
                        <td>${product.name}</td>
                        <td>${product.sku}</td>
                        <td>$${product.reserve_price || 'N/A'}</td>
                        <td>${product.product_country || 'N/A'}</td>
                        <td><span class="badge bg-${product.status === 'active' ? 'success' : 'secondary'}">${product.status}</span></td>
                        <td>
                            <a href="/admin/products/auction/${product.id}/edit" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="deleteProduct(${product.id})" class="btn btn-sm btn-outline-danger" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            $('#auctionProductsTable tbody').html(html);
        }
    });
}

function loadWholesaleProducts() {
    $.get('/api/admin/products?type=wholesale', function(response) {
        if (response.success) {
            let html = '';
            response.data.forEach(function(product) {
                html += `
                    <tr>
                        <td>${product.id}</td>
                        <td><img src="${product.thumbnail_image || '/images/no-image.png'}" width="50" height="50" class="rounded"></td>
                        <td>${product.name}</td>
                        <td>${product.sku}</td>
                        <td>$${product.price}</td>
                        <td>${product.min_wholesale_quantity || 'N/A'}</td>
                        <td>${product.max_wholesale_quantity || 'N/A'}</td>
                        <td><span class="badge bg-${product.status === 'active' ? 'success' : 'secondary'}">${product.status}</span></td>
                        <td>
                            <a href="/admin/products/wholesale/${product.id}/edit" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="deleteProduct(${product.id})" class="btn btn-sm btn-outline-danger" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            $('#wholesaleProductsTable tbody').html(html);
        }
    });
}

function deleteProduct(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/api/admin/products/${id}`,
                type: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Deleted!', 'Product has been deleted.', 'success');
                        // Reload the current tab
                        loadProductCounts();
                        loadSimpleProducts();
                        loadDigitalProducts();
                        loadAuctionProducts();
                        loadWholesaleProducts();
                    } else {
                        Swal.fire('Error!', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error!', 'Something went wrong.', 'error');
                }
            });
        }
    });
}
</script>
@endsection
