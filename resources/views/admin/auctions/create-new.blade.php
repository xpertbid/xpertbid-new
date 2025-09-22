@extends('admin.layouts.app')

@section('title', 'Create Auction')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create Auction</h1>
        <div>
            <a href="/admin/auctions" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Auctions
            </a>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs" id="auctionTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button" role="tab">
                <i class="fas fa-info-circle"></i> Basic Information
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pricing-tab" data-bs-toggle="tab" data-bs-target="#pricing" type="button" role="tab">
                <i class="fas fa-gavel"></i> Auction Pricing
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="timing-tab" data-bs-toggle="tab" data-bs-target="#timing" type="button" role="tab">
                <i class="fas fa-clock"></i> Timing & Duration
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="media-tab" data-bs-toggle="tab" data-bs-target="#media" type="button" role="tab">
                <i class="fas fa-images"></i> Media & Files
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo" type="button" role="tab">
                <i class="fas fa-search"></i> SEO & Meta
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="translations-tab" data-bs-toggle="tab" data-bs-target="#translations" type="button" role="tab">
                <i class="fas fa-globe"></i> Translations
            </button>
        </li>
    </ul>

    <form id="auctionForm" enctype="multipart/form-data">
        <div class="tab-content" id="auctionTabsContent">
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
                                    <label for="title">Auction Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="title" name="title" required>
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
                                            <label for="auction_type">Auction Type</label>
                                            <select class="form-control" id="auction_type" name="auction_type">
                                                <option value="english">English Auction</option>
                                                <option value="dutch">Dutch Auction</option>
                                                <option value="sealed_bid">Sealed Bid</option>
                                                <option value="vickrey">Vickrey Auction</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="category">Category</label>
                                            <select class="form-control" id="category" name="category">
                                                <option value="art">Art & Antiques</option>
                                                <option value="jewelry">Jewelry</option>
                                                <option value="electronics">Electronics</option>
                                                <option value="vehicles">Vehicles</option>
                                                <option value="real_estate">Real Estate</option>
                                                <option value="collectibles">Collectibles</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Auction Status</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="status">Status</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="draft">Draft</option>
                                        <option value="scheduled">Scheduled</option>
                                        <option value="active">Active</option>
                                        <option value="ended">Ended</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured">
                                    <label class="form-check-label" for="is_featured">Featured Auction</label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_verified" name="is_verified">
                                    <label class="form-check-label" for="is_verified">Verified Auction</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Auction Pricing Tab -->
            <div class="tab-pane fade" id="pricing" role="tabpanel">
                <div class="row mt-3">
                    <div class="col-lg-8">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Auction Pricing</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="starting_price">Starting Price <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" class="form-control" id="starting_price" name="starting_price" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="reserve_price">Reserve Price</label>
                                            <input type="number" step="0.01" class="form-control" id="reserve_price" name="reserve_price">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="buy_now_price">Buy Now Price</label>
                                            <input type="number" step="0.01" class="form-control" id="buy_now_price" name="buy_now_price">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="bid_increment">Bid Increment</label>
                                            <input type="number" step="0.01" class="form-control" id="bid_increment" name="bid_increment" value="1.00">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="currency">Currency</label>
                                            <select class="form-control" id="currency" name="currency">
                                                <option value="USD">USD - US Dollar</option>
                                                <option value="EUR">EUR - Euro</option>
                                                <option value="GBP">GBP - British Pound</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timing Tab -->
            <div class="tab-pane fade" id="timing" role="tabpanel">
                <div class="row mt-3">
                    <div class="col-lg-8">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Timing & Duration</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="start_time">Start Time</label>
                                            <input type="datetime-local" class="form-control" id="start_time" name="start_time">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="end_time">End Time</label>
                                            <input type="datetime-local" class="form-control" id="end_time" name="end_time">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="duration_hours">Duration (Hours)</label>
                                            <input type="number" class="form-control" id="duration_hours" name="duration_hours" min="1" max="168">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="timezone">Timezone</label>
                                            <select class="form-control" id="timezone" name="timezone">
                                                <option value="UTC">UTC</option>
                                                <option value="America/New_York">Eastern Time</option>
                                                <option value="America/Chicago">Central Time</option>
                                                <option value="America/Denver">Mountain Time</option>
                                                <option value="America/Los_Angeles">Pacific Time</option>
                                                <option value="Europe/London">London</option>
                                                <option value="Europe/Paris">Paris</option>
                                                <option value="Asia/Tokyo">Tokyo</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Media Tab -->
            <div class="tab-pane fade" id="media" role="tabpanel">
                <div class="row mt-3">
                    <div class="col-lg-8">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Media & Files</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="images">Auction Images</label>
                                    <input type="file" class="form-control" id="images" name="images[]" accept="image/*" multiple>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="documents">Documents</label>
                                    <input type="file" class="form-control" id="documents" name="documents[]" accept=".pdf,.jpg,.png" multiple>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="videos">Videos</label>
                                    <input type="file" class="form-control" id="videos" name="videos[]" accept="video/*" multiple>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEO Tab -->
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
                                <small class="text-muted">Add translations for different languages. Save the auction first to enable translations.</small>
                            </div>
                            <div class="card-body">
                                <div id="translationManagerContainer">
                                    <div class="text-center py-4">
                                        <i class="fas fa-globe fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">Save the auction first to add translations</h5>
                                        <p class="text-muted">After saving the auction, you'll be able to add content in multiple languages.</p>
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
                            <i class="fas fa-save"></i> Create Auction
                        </button>
                        <a href="/admin/auctions" class="btn btn-secondary btn-lg">
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
    // Form submission
    $('#auctionForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        $.ajax({
            url: '/api/admin/auctions',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    Swal.fire('Success!', 'Auction created successfully.', 'success')
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

function showTranslationManager(auctionId) {
    // Switch to translations tab
    $('#translations-tab').tab('show');
    
    // Load translation manager component
    $.ajax({
        url: `/admin/components/translation-manager/auction/${auctionId}`,
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
