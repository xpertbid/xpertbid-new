@extends('admin.layouts.app')

@section('title', 'Create Wholesale Product')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create Wholesale Product</h1>
        <div>
            <a href="/admin/products" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Products
            </a>
        </div>
    </div>

    <form id="wholesaleProductForm" enctype="multipart/form-data">
        <div class="row">
            <!-- Basic Information -->
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Basic Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name">Product Title *</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                    <small class="form-text text-muted">Product title will be used to auto-generate slug</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="sku">SKU *</label>
                                    <input type="text" class="form-control" id="sku" name="sku" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="brand_id">Brand</label>
                                    <select class="form-control" id="brand_id" name="brand_id">
                                        <option value="">Select Brand</option>
                                        <!-- Brands will be loaded via AJAX -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="category_id">Product Category *</label>
                                    <select class="form-control" id="category_id" name="category_id" required>
                                        <option value="">Select Category</option>
                                        <!-- Categories will be loaded via AJAX -->
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="description">Product Description *</label>
                            <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="short_description">Short Description</label>
                            <textarea class="form-control" id="short_description" name="short_description" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Pricing & Inventory -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Pricing & Inventory</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="price">Unit Price *</label>
                                    <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="stock_quantity">Stock Quantity *</label>
                                    <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="set_point">Set Point</label>
                                    <input type="number" class="form-control" id="set_point" name="set_point" min="0">
                                    <small class="form-text text-muted">Minimum quantity for wholesale pricing</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="unit">Units</label>
                                    <select class="form-control" id="unit" name="unit">
                                        <option value="">Select Unit</option>
                                        <!-- Units will be loaded via AJAX -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="weight">Weight</label>
                                    <input type="number" class="form-control" id="weight" name="weight" step="0.01" min="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Wholesale Pricing -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Wholesale Pricing</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="min_wholesale_quantity">Min Quantity *</label>
                                    <input type="number" class="form-control" id="min_wholesale_quantity" name="min_wholesale_quantity" min="1" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="max_wholesale_quantity">Max Quantity</label>
                                    <input type="number" class="form-control" id="max_wholesale_quantity" name="max_wholesale_quantity" min="1">
                                    <small class="form-text text-muted">Leave empty for no maximum limit</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="wholesale_price">Price per Piece *</label>
                                    <input type="number" class="form-control" id="wholesale_price" name="wholesale_price" step="0.01" min="0" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Media -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Media</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="thumbnail_image">Thumbnail Image</label>
                                    <input type="file" class="form-control" id="thumbnail_image" name="thumbnail_image" accept="image/*">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="gallery_images">Gallery Images</label>
                                    <input type="file" class="form-control" id="gallery_images" name="gallery_images[]" accept="image/*" multiple>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="videos">Videos</label>
                                    <input type="file" class="form-control" id="videos" name="videos[]" accept="video/*" multiple>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="pdf_specification">PDF Specification</label>
                                    <input type="file" class="form-control" id="pdf_specification" name="pdf_specification" accept=".pdf">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="youtube_url">YouTube Video URL</label>
                                    <input type="url" class="form-control" id="youtube_url" name="youtube_url" placeholder="https://youtube.com/watch?v=...">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="youtube_shorts_url">YouTube Shorts URL</label>
                                    <input type="url" class="form-control" id="youtube_shorts_url" name="youtube_shorts_url" placeholder="https://youtube.com/shorts/...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SEO -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">SEO Settings</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="meta_title">Meta Title</label>
                            <input type="text" class="form-control" id="meta_title" name="meta_title">
                        </div>
                        <div class="form-group mb-3">
                            <label for="meta_description">Meta Description</label>
                            <textarea class="form-control" id="meta_description" name="meta_description" rows="3"></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="meta_keywords">Meta Keywords</label>
                            <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" placeholder="keyword1, keyword2, keyword3">
                        </div>
                        <div class="form-group mb-3">
                            <label for="meta_image">Meta Image</label>
                            <input type="file" class="form-control" id="meta_image" name="meta_image" accept="image/*">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Product Status -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Product Status</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured">
                            <label class="form-check-label" for="is_featured">
                                Featured Product
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Tags -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Tags</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="tags">Product Tags</label>
                            <select class="form-control" id="tags" name="tags[]" multiple>
                                <!-- Tags will be loaded via AJAX -->
                            </select>
                            <small class="form-text text-muted">Hold Ctrl/Cmd to select multiple tags</small>
                        </div>
                    </div>
                </div>

                <!-- Frequently Bought Together -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Frequently Bought Together</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="frequently_bought_together">Related Products</label>
                            <select class="form-control" id="frequently_bought_together" name="frequently_bought_together[]" multiple>
                                <!-- Products will be loaded via AJAX -->
                            </select>
                            <small class="form-text text-muted">Hold Ctrl/Cmd to select multiple products</small>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary btn-block mb-2">
                            <i class="fas fa-save"></i> Create Wholesale Product
                        </button>
                        <a href="/admin/products" class="btn btn-secondary btn-block">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    loadBrands();
    loadCategories();
    loadTags();
    loadUnits();
    loadProducts();

    // Auto-generate slug from name
    $('#name').on('input', function() {
        const name = $(this).val();
        const slug = name.toLowerCase()
            .replace(/[^a-z0-9 -]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');
        // Slug will be generated on server side
    });
});

function loadBrands() {
    $.get('/api/admin/brands', function(response) {
        if (response.success) {
            let options = '<option value="">Select Brand</option>';
            response.data.forEach(function(brand) {
                options += `<option value="${brand.id}">${brand.name}</option>`;
            });
            $('#brand_id').html(options);
        }
    });
}

function loadCategories() {
    $.get('/api/admin/categories', function(response) {
        if (response.success) {
            let options = '<option value="">Select Category</option>';
            response.data.forEach(function(category) {
                options += `<option value="${category.id}">${category.name}</option>`;
            });
            $('#category_id').html(options);
        }
    });
}

function loadTags() {
    $.get('/api/admin/tags', function(response) {
        if (response.success) {
            let options = '';
            response.data.forEach(function(tag) {
                options += `<option value="${tag.id}">${tag.name}</option>`;
            });
            $('#tags').html(options);
        }
    });
}

function loadUnits() {
    $.get('/api/admin/units', function(response) {
        if (response.success) {
            let options = '<option value="">Select Unit</option>';
            response.data.forEach(function(unit) {
                options += `<option value="${unit.symbol}">${unit.name} (${unit.symbol})</option>`;
            });
            $('#unit').html(options);
        }
    });
}

function loadProducts() {
    $.get('/api/admin/products', function(response) {
        if (response.success) {
            let options = '';
            response.data.forEach(function(product) {
                options += `<option value="${product.id}">${product.name}</option>`;
            });
            $('#frequently_bought_together').html(options);
        }
    });
}

$('#wholesaleProductForm').on('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('product_type', 'wholesale');
    formData.append('tenant_id', 1); // Default tenant
    formData.append('vendor_id', 1); // Default vendor
    
    $.ajax({
        url: '/api/admin/products',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                Swal.fire('Success!', 'Wholesale product created successfully.', 'success')
                    .then(() => {
                        window.location.href = '/admin/products';
                    });
            } else {
                Swal.fire('Error!', response.message, 'error');
            }
        },
        error: function(xhr) {
            const errors = xhr.responseJSON?.errors || {};
            let errorMessage = 'Validation failed:\n';
            for (const field in errors) {
                errorMessage += `${field}: ${errors[field].join(', ')}\n`;
            }
            Swal.fire('Error!', errorMessage, 'error');
        }
    });
});
</script>
@endsection
