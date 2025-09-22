@extends('admin.layouts.app')

@section('title', '@trans("create") @trans("simple_product")')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">@trans('create') @trans('simple_product')</h1>
        <div>
            <a href="/admin/products" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> @trans('back') @trans('products')
            </a>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs" id="productTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button" role="tab">
                <i class="fas fa-info-circle"></i> @trans('basic_information')
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pricing-tab" data-bs-toggle="tab" data-bs-target="#pricing" type="button" role="tab">
                <i class="fas fa-dollar-sign"></i> @trans('pricing_inventory')
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="media-tab" data-bs-toggle="tab" data-bs-target="#media" type="button" role="tab">
                <i class="fas fa-images"></i> @trans('media_files')
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo" type="button" role="tab">
                <i class="fas fa-search"></i> @trans('seo_meta')
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="translations-tab" data-bs-toggle="tab" data-bs-target="#translations" type="button" role="tab">
                <i class="fas fa-globe"></i> @trans('translations')
            </button>
        </li>
    </ul>

    <form id="simpleProductForm" action="/admin/products/simple" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="tab-content" id="productTabsContent">
            <!-- Basic Information Tab -->
            <div class="tab-pane fade show active" id="basic" role="tabpanel">
                <div class="row mt-3">
                    <div class="col-lg-8">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Basic Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="name">Product Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="slug">Slug</label>
                                    <input type="text" class="form-control" id="slug" name="slug">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="short_description">Short Description</label>
                                    <textarea class="form-control" id="short_description" name="short_description" rows="2"></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="sku">SKU</label>
                                            <input type="text" class="form-control" id="sku" name="sku">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="barcode">Barcode</label>
                                            <input type="text" class="form-control" id="barcode" name="barcode">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
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
                                    <label class="form-check-label" for="is_featured">Featured Product</label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_trending" name="is_trending">
                                    <label class="form-check-label" for="is_trending">Trending Product</label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_bestseller" name="is_bestseller">
                                    <label class="form-check-label" for="is_bestseller">Bestseller</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing & Inventory Tab -->
            <div class="tab-pane fade" id="pricing" role="tabpanel">
                <div class="row mt-3">
                    <div class="col-lg-8">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Pricing & Inventory</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="price">Price <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="compare_price">Compare Price</label>
                                            <input type="number" step="0.01" class="form-control" id="compare_price" name="compare_price">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="cost_price">Cost Price</label>
                                            <input type="number" step="0.01" class="form-control" id="cost_price" name="cost_price">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="quantity">Quantity</label>
                                            <input type="number" class="form-control" id="quantity" name="quantity" value="0">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="low_stock_threshold">Low Stock Threshold</label>
                                            <input type="number" class="form-control" id="low_stock_threshold" name="low_stock_threshold" value="5">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="track_quantity" name="track_quantity" checked>
                                    <label class="form-check-label" for="track_quantity">Track Quantity</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Media & Files Tab -->
            <div class="tab-pane fade" id="media" role="tabpanel">
                <div class="row mt-3">
                    <div class="col-lg-8">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Media & Files</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="thumbnail_image">Thumbnail Image</label>
                                    <input type="file" class="form-control" id="thumbnail_image" name="thumbnail_image" accept="image/*">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="gallery_images">Gallery Images</label>
                                    <input type="file" class="form-control" id="gallery_images" name="gallery_images[]" accept="image/*" multiple>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="videos">Videos</label>
                                    <input type="file" class="form-control" id="videos" name="videos[]" accept="video/*" multiple>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="pdf_specification">PDF Specification</label>
                                    <input type="file" class="form-control" id="pdf_specification" name="pdf_specification" accept=".pdf">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEO & Meta Tab -->
            <div class="tab-pane fade" id="seo" role="tabpanel">
                <div class="row mt-3">
                    <div class="col-lg-8">
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
                </div>
            </div>

            <!-- Translations Tab -->
            <div class="tab-pane fade" id="translations" role="tabpanel">
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Multi-Language Content</h6>
                                <small class="text-muted">Add translations for different languages. Save the product first to enable translations.</small>
                            </div>
                            <div class="card-body">
                                <div id="translationManagerContainer">
                                    <div class="text-center py-4">
                                        <i class="fas fa-globe fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">Save the product first to add translations</h5>
                                        <p class="text-muted">After saving the product, you'll be able to add content in multiple languages.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Create Product
                        </button>
                        <a href="/admin/products" class="btn btn-secondary btn-lg">
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
    // Auto-generate slug from name
    $('#name').on('input', function() {
        const name = $(this).val();
        const slug = name.toLowerCase()
            .replace(/[^a-z0-9 -]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');
        $('#slug').val(slug);
    });

    // Form submission
    $('#simpleProductForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        $.ajax({
            url: '/api/admin/products',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    Swal.fire('Success!', 'Simple product created successfully.', 'success')
                        .then(() => {
                            // Show translation manager
                            showTranslationManager(response.data.id);
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
});

function showTranslationManager(productId) {
    // Switch to translations tab
    $('#translations-tab').tab('show');
    
    // Load translation manager component
    $.ajax({
        url: `/admin/components/translation-manager/product/${productId}`,
        method: 'GET',
        success: function(html) {
            $('#translationManagerContainer').html(html);
            // Initialize translation manager
            if (typeof translationManager !== 'undefined') {
                translationManager.init();
            }
        },
        error: function(error) {
            console.error('Failed to load translation manager:', error);
            $('#translationManagerContainer').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    Failed to load translation manager. Please refresh the page.
                </div>
            `);
        }
    });
}
</script>
@endsection
