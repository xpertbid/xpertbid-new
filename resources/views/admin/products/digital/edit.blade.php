@extends('admin.layouts.app')

@section('title', 'Edit Digital Product')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Digital Product</h1>
        <div>
            <a href="/admin/products" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Products
            </a>
        </div>
    </div>

    <form id="digitalProductForm" enctype="multipart/form-data">
        <input type="hidden" id="product_id" name="product_id" value="{{ $id }}">
        
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

                        <div class="form-group mb-3">
                            <label for="description">Product Description *</label>
                            <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
                        </div>
                    </div>
                </div>

                <!-- Digital Files -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Digital Files</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="digital_files">Product Files *</label>
                            <input type="file" class="form-control" id="digital_files" name="digital_files[]" multiple>
                            <small class="form-text text-muted">Upload the digital files that customers will download</small>
                            <div id="current_digital_files" class="mt-2"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="download_limit">Download Limit</label>
                                    <input type="number" class="form-control" id="download_limit" name="download_limit" min="-1" value="-1">
                                    <small class="form-text text-muted">-1 for unlimited downloads</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="download_expiry_days">Download Expiry (Days)</label>
                                    <input type="number" class="form-control" id="download_expiry_days" name="download_expiry_days" min="1">
                                    <small class="form-text text-muted">Leave empty for no expiry</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Pricing</h6>
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
                                    <label for="tax">Tax (%)</label>
                                    <input type="number" class="form-control" id="tax" name="tax" step="0.01" min="0" max="100">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="vat">VAT (%)</label>
                                    <input type="number" class="form-control" id="vat" name="vat" step="0.01" min="0" max="100">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="discount">Discount (%)</label>
                                    <input type="number" class="form-control" id="discount" name="discount" step="0.01" min="0" max="100">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="sale_price">Sale Price</label>
                                    <input type="number" class="form-control" id="sale_price" name="sale_price" step="0.01" min="0">
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
                                    <div id="current_thumbnail" class="mt-2"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="main_images">Main Images</label>
                                    <input type="file" class="form-control" id="main_images" name="main_images[]" accept="image/*" multiple>
                                    <div id="current_main_images" class="mt-2"></div>
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
                            <label for="meta_image">Meta Image</label>
                            <input type="file" class="form-control" id="meta_image" name="meta_image" accept="image/*">
                            <div id="current_meta_image" class="mt-2"></div>
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
                            <i class="fas fa-save"></i> Update Digital Product
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
    const productId = $('#product_id').val();
    
    // Load product data
    loadProductData(productId);
    loadTags();
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

function loadProductData(productId) {
    $.ajax({
        url: `/api/admin/products/${productId}`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const product = response.data;
                
                // Fill form fields
                $('#name').val(product.name);
                $('#sku').val(product.sku);
                $('#description').val(product.description);
                $('#price').val(product.price);
                $('#status').val(product.status);
                $('#meta_title').val(product.meta_title);
                $('#meta_description').val(product.meta_description);
                $('#download_limit').val(product.download_limit);
                $('#download_expiry_days').val(product.download_expiry_days);
                
                // Checkboxes
                $('#is_featured').prop('checked', product.is_featured == 1);
                
                // Display current media files
                displayCurrentMedia(product);
            } else {
                Swal.fire('Error!', 'Failed to load product data.', 'error');
            }
        },
        error: function() {
            Swal.fire('Error!', 'Failed to load product data.', 'error');
        }
    });
}

function displayCurrentMedia(product) {
    // Display current thumbnail
    if (product.thumbnail_image) {
        $('#current_thumbnail').html(`
            <div class="current-file">
                <strong>Current:</strong> 
                <img src="${product.thumbnail_image}" alt="Thumbnail" style="max-width: 100px; max-height: 100px;" class="img-thumbnail">
            </div>
        `);
    }
    
    // Display current digital files
    if (product.digital_files) {
        const digitalFiles = JSON.parse(product.digital_files);
        if (digitalFiles.length > 0) {
            let filesHtml = '<div class="current-files"><strong>Current Files:</strong><ul>';
            digitalFiles.forEach(file => {
                filesHtml += `<li>${file}</li>`;
            });
            filesHtml += '</ul></div>';
            $('#current_digital_files').html(filesHtml);
        }
    }
    
    // Display current main images
    if (product.gallery_images) {
        const galleryImages = JSON.parse(product.gallery_images);
        if (galleryImages.length > 0) {
            let galleryHtml = '<div class="current-files"><strong>Current Images:</strong><div class="row">';
            galleryImages.forEach(image => {
                galleryHtml += `
                    <div class="col-md-6 mb-2">
                        <img src="${image}" alt="Gallery" style="max-width: 100px; max-height: 100px;" class="img-thumbnail">
                    </div>
                `;
            });
            galleryHtml += '</div></div>';
            $('#current_main_images').html(galleryHtml);
        }
    }
    
    // Display current meta image
    if (product.meta_image) {
        $('#current_meta_image').html(`
            <div class="current-file">
                <strong>Current:</strong> 
                <img src="${product.meta_image}" alt="Meta Image" style="max-width: 100px; max-height: 100px;" class="img-thumbnail">
            </div>
        `);
    }
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

$('#digitalProductForm').on('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const productId = $('#product_id').val();
    
    $.ajax({
        url: `/api/admin/products/${productId}`,
        type: 'PUT',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                Swal.fire('Success!', 'Digital product updated successfully.', 'success')
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
