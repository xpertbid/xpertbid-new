<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XpertBid - Create Product</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 4px 0;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }
        .main-content {
            background: #f8f9fa;
            min-height: 100vh;
        }
        .form-container {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        .section-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .required-field::after {
            content: " *";
            color: red;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h4 class="text-white"><i class="fas fa-gavel"></i> XpertBid</h4>
                        <small class="text-white-50">Admin Dashboard</small>
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="/admin">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/tenants">
                                <i class="fas fa-building me-2"></i>Tenants
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/users">
                                <i class="fas fa-users me-2"></i>Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/vendors">
                                <i class="fas fa-store me-2"></i>Vendors
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="/admin/products">
                                <i class="fas fa-box me-2"></i>Products
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/properties">
                                <i class="fas fa-home me-2"></i>Properties
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/vehicles">
                                <i class="fas fa-car me-2"></i>Vehicles
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/auctions">
                                <i class="fas fa-gavel me-2"></i>Auctions
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><i class="fas fa-plus me-2"></i>Create New Product</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <a href="/admin/products" class="btn btn-sm btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Products
                            </a>
                        </div>
                    </div>
                </div>

                <div class="form-container">
                    <form id="productForm" method="POST" action="/api/admin/products">
                        @csrf
                        
                        <!-- Basic Information Section -->
                        <div class="section-header">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Basic Information</h5>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label required-field">Product Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="slug" class="form-label">Slug</label>
                                    <input type="text" class="form-control" id="slug" name="slug">
                                    <div class="form-text">Leave empty to auto-generate from product name</div>
                                </div>
                                <div class="mb-3">
                                    <label for="sku" class="form-label required-field">SKU</label>
                                    <input type="text" class="form-control" id="sku" name="sku" required>
                                </div>
                                <div class="mb-3">
                                    <label for="barcode" class="form-label">Barcode</label>
                                    <input type="text" class="form-control" id="barcode" name="barcode">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="short_description" class="form-label">Short Description</label>
                                    @include('admin.components.shopify-editor', [
                                        'name' => 'short_description',
                                        'value' => old('short_description'),
                                        'height' => 150,
                                        'placeholder' => 'Brief product summary...'
                                    ])
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label required-field">Description</label>
                                    @include('admin.components.shopify-editor', [
                                        'name' => 'description',
                                        'value' => old('description'),
                                        'height' => 300,
                                        'placeholder' => 'Describe the product in detail...',
                                        'required' => true
                                    ])
                                </div>
                            </div>
                        </div>

                        <!-- Category & Brand Section -->
                        <div class="section-header">
                            <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Category & Brand</h5>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label required-field">Category</label>
                                    <select class="form-control" id="category_id" name="category_id" required>
                                        <option value="">Select Category</option>
                                    </select>
                                    <div class="form-text">
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="loadCategories()">
                                            <i class="fas fa-sync"></i> Refresh Categories
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="brand_id" class="form-label">Brand</label>
                                    <select class="form-control" id="brand_id" name="brand_id">
                                        <option value="">Select Brand</option>
                                    </select>
                                    <div class="form-text">
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="loadBrands()">
                                            <i class="fas fa-sync"></i> Refresh Brands
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="tags" class="form-label">Tags</label>
                                    <select class="form-control" id="tags" name="tags[]" multiple>
                                    </select>
                                    <div class="form-text">
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="loadTags()">
                                            <i class="fas fa-sync"></i> Refresh Tags
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pricing Section -->
                        <div class="section-header">
                            <h5 class="mb-0"><i class="fas fa-dollar-sign me-2"></i>Pricing</h5>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="price" class="form-label required-field">Price</label>
                                    <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="sale_price" class="form-label">Sale Price</label>
                                    <input type="number" step="0.01" class="form-control" id="sale_price" name="sale_price">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="compare_price" class="form-label">Compare Price</label>
                                    <input type="number" step="0.01" class="form-control" id="compare_price" name="compare_price">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="cost_price" class="form-label">Cost Price</label>
                                    <input type="number" step="0.01" class="form-control" id="cost_price" name="cost_price">
                                </div>
                            </div>
                        </div>

                        <!-- Inventory Section -->
                        <div class="section-header">
                            <h5 class="mb-0"><i class="fas fa-boxes me-2"></i>Inventory</h5>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="stock_quantity" class="form-label required-field">Stock Quantity</label>
                                    <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="min_quantity" class="form-label">Min Quantity</label>
                                    <input type="number" class="form-control" id="min_quantity" name="min_quantity" value="1">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="max_quantity" class="form-label">Max Quantity</label>
                                    <input type="number" class="form-control" id="max_quantity" name="max_quantity">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="low_stock_threshold" class="form-label">Low Stock Threshold</label>
                                    <input type="number" class="form-control" id="low_stock_threshold" name="low_stock_threshold" value="5">
                                </div>
                            </div>
                        </div>

                        <!-- Product Type & Status Section -->
                        <div class="section-header">
                            <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Product Type & Status</h5>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="type" class="form-label required-field">Type</label>
                                    <select class="form-control" id="type" name="type" required>
                                        <option value="physical">Physical</option>
                                        <option value="digital">Digital</option>
                                        <option value="service">Service</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="product_type" class="form-label">Product Type</label>
                                    <select class="form-control" id="product_type" name="product_type">
                                        <option value="simple">Simple</option>
                                        <option value="grouped">Grouped</option>
                                        <option value="external">External</option>
                                        <option value="variable">Variable</option>
                                        <option value="bundle">Bundle</option>
                                        <option value="digital">Digital</option>
                                        <option value="subscription">Subscription</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="status" class="form-label required-field">Status</label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="draft">Draft</option>
                                        <option value="pending">Pending</option>
                                        <option value="published">Published</option>
                                        <option value="private">Private</option>
                                        <option value="archived">Archived</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="visibility" class="form-label">Visibility</label>
                                    <select class="form-control" id="visibility" name="visibility">
                                        <option value="visible">Visible</option>
                                        <option value="catalog">Catalog</option>
                                        <option value="search">Search</option>
                                        <option value="hidden">Hidden</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Physical Properties Section -->
                        <div class="section-header">
                            <h5 class="mb-0"><i class="fas fa-ruler me-2"></i>Physical Properties</h5>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="weight" class="form-label">Weight (kg)</label>
                                    <input type="number" step="0.001" class="form-control" id="weight" name="weight">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="length" class="form-label">Length (cm)</label>
                                    <input type="number" step="0.001" class="form-control" id="length" name="length">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="width" class="form-label">Width (cm)</label>
                                    <input type="number" step="0.001" class="form-control" id="width" name="width">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="height" class="form-label">Height (cm)</label>
                                    <input type="number" step="0.001" class="form-control" id="height" name="height">
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Section -->
                        <div class="section-header">
                            <h5 class="mb-0"><i class="fas fa-shipping-fast me-2"></i>Shipping</h5>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="requires_shipping" name="requires_shipping" checked>
                                        <label class="form-check-label" for="requires_shipping">
                                            Requires Shipping
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="free_shipping" name="free_shipping">
                                        <label class="form-check-label" for="free_shipping">
                                            Free Shipping
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="shipping_class" class="form-label">Shipping Class</label>
                                    <input type="text" class="form-control" id="shipping_class" name="shipping_class">
                                </div>
                            </div>
                        </div>

                        <!-- Digital Product Section -->
                        <div class="section-header">
                            <h5 class="mb-0"><i class="fas fa-download me-2"></i>Digital Product</h5>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_digital" name="is_digital">
                                        <label class="form-check-label" for="is_digital">
                                            Is Digital Product
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_downloadable" name="is_downloadable">
                                        <label class="form-check-label" for="is_downloadable">
                                            Is Downloadable
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="download_limit" class="form-label">Download Limit</label>
                                    <input type="number" class="form-control" id="download_limit" name="download_limit" value="-1">
                                    <div class="form-text">-1 for unlimited</div>
                                </div>
                            </div>
                        </div>

                        <!-- SEO Section -->
                        <div class="section-header">
                            <h5 class="mb-0"><i class="fas fa-search me-2"></i>SEO Settings</h5>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="seo_title" class="form-label">SEO Title</label>
                                    <input type="text" class="form-control" id="seo_title" name="seo_title">
                                </div>
                                <div class="mb-3">
                                    <label for="seo_description" class="form-label">SEO Description</label>
                                    <textarea class="form-control" id="seo_description" name="seo_description" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="seo_keywords" class="form-label">SEO Keywords</label>
                                    <input type="text" class="form-control" id="seo_keywords" name="seo_keywords">
                                </div>
                                <div class="mb-3">
                                    <label for="seo_focus_keyword" class="form-label">Focus Keyword</label>
                                    <input type="text" class="form-control" id="seo_focus_keyword" name="seo_focus_keyword">
                                </div>
                            </div>
                        </div>

                        <!-- Hidden Fields -->
                        <input type="hidden" name="tenant_id" value="1">
                        <input type="hidden" name="vendor_id" value="1">
                        <input type="hidden" name="category_id" value="1">
                        <input type="hidden" name="brand_id" value="1">

                        <!-- Submit Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="/admin/products" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Create Product
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Load categories, brands, and tags on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadCategories();
            loadBrands();
            loadTags();
        });

        // Load categories
        function loadCategories() {
            fetch('/api/admin/categories/hierarchy/tree')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const select = document.getElementById('category_id');
                        select.innerHTML = '<option value="">Select Category</option>';
                        
                        function addCategories(categories, level = 0) {
                            categories.forEach(category => {
                                const indent = '— '.repeat(level);
                                const option = document.createElement('option');
                                option.value = category.id;
                                option.textContent = indent + category.name;
                                select.appendChild(option);
                                
                                if (category.children && category.children.length > 0) {
                                    addCategories(category.children, level + 1);
                                }
                            });
                        }
                        
                        addCategories(data.data);
                    }
                })
                .catch(error => {
                    console.error('Error loading categories:', error);
                });
        }

        // Load brands
        function loadBrands() {
            fetch('/api/admin/brands')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const select = document.getElementById('brand_id');
                        select.innerHTML = '<option value="">Select Brand</option>';
                        
                        data.data.forEach(brand => {
                            const option = document.createElement('option');
                            option.value = brand.id;
                            option.textContent = brand.name;
                            select.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error loading brands:', error);
                });
        }

        // Load tags
        function loadTags() {
            fetch('/api/admin/tags')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const select = document.getElementById('tags');
                        select.innerHTML = '';
                        
                        data.data.forEach(tag => {
                            const option = document.createElement('option');
                            option.value = tag.id;
                            option.textContent = tag.name;
                            if (tag.color) {
                                option.style.color = tag.color;
                            }
                            select.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error loading tags:', error);
                });
        }

        // Auto-generate slug from name
        document.getElementById('name').addEventListener('input', function() {
            const name = this.value;
            const slug = name.toLowerCase()
                .replace(/[^a-z0-9 -]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');
            document.getElementById('slug').value = slug;
        });

        // Form submission
        document.getElementById('productForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            
            // Convert checkboxes to boolean
            data.requires_shipping = document.getElementById('requires_shipping').checked;
            data.free_shipping = document.getElementById('free_shipping').checked;
            data.is_digital = document.getElementById('is_digital').checked;
            data.is_downloadable = document.getElementById('is_downloadable').checked;
            
            // Convert numeric fields
            if (data.price) data.price = parseFloat(data.price);
            if (data.sale_price) data.sale_price = parseFloat(data.sale_price);
            if (data.compare_price) data.compare_price = parseFloat(data.compare_price);
            if (data.cost_price) data.cost_price = parseFloat(data.cost_price);
            if (data.stock_quantity) data.stock_quantity = parseInt(data.stock_quantity);
            if (data.min_quantity) data.min_quantity = parseInt(data.min_quantity);
            if (data.max_quantity) data.max_quantity = parseInt(data.max_quantity);
            if (data.low_stock_threshold) data.low_stock_threshold = parseInt(data.low_stock_threshold);
            if (data.weight) data.weight = parseFloat(data.weight);
            if (data.length) data.length = parseFloat(data.length);
            if (data.width) data.width = parseFloat(data.width);
            if (data.height) data.height = parseFloat(data.height);
            if (data.download_limit) data.download_limit = parseInt(data.download_limit);
            
            // Handle tags array
            const selectedTags = Array.from(document.getElementById('tags').selectedOptions).map(option => parseInt(option.value));
            data.tags = selectedTags;
            
            fetch('/api/admin/products', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Product created successfully!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = '/admin/products';
                    });
                } else {
                    let errorMessage = data.message;
                    if (data.errors) {
                        errorMessage += '<br><br>Validation Errors:<br>';
                        Object.keys(data.errors).forEach(key => {
                            errorMessage += `• ${data.errors[key].join(', ')}<br>`;
                        });
                    }
                    Swal.fire({
                        title: 'Error!',
                        html: errorMessage,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to create product',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        });
    </script>
</body>
</html>
