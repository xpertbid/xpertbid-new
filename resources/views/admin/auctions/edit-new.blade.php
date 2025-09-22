@extends('admin.layouts.app')

@section('title', '@trans("edit") @trans("auctions")')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">@trans('edit') @trans('auctions')</h1>
        <div>
            <a href="/admin/auctions" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> @trans('back') @trans('auctions')
            </a>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs" id="auctionTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button" role="tab">
                <i class="fas fa-info-circle"></i> @trans('basic_information')
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pricing-tab" data-bs-toggle="tab" data-bs-target="#pricing" type="button" role="tab">
                <i class="fas fa-gavel"></i> @trans('auction_pricing')
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="timing-tab" data-bs-toggle="tab" data-bs-target="#timing" type="button" role="tab">
                <i class="fas fa-clock"></i> @trans('timing_duration')
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

    <form id="auctionForm" enctype="multipart/form-data">
        <input type="hidden" id="auctionId" name="auction_id" value="{{ $id }}">
        
        <div class="tab-content" id="auctionTabsContent">
            <!-- Basic Information Tab -->
            <div class="tab-pane fade show active" id="basic" role="tabpanel">
                <div class="row mt-3">
                    <div class="col-lg-8">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">@trans('basic_information')</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="title">@trans('title') <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="title" name="title" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="description">@trans('description')</label>
                                    <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="short_description">@trans('short_description')</label>
                                    <textarea class="form-control" id="short_description" name="short_description" rows="2"></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="auction_type">@trans('auction_type')</label>
                                            <select class="form-control" id="auction_type" name="auction_type">
                                                <option value="english">@trans('english_auction')</option>
                                                <option value="dutch">@trans('dutch_auction')</option>
                                                <option value="sealed_bid">@trans('sealed_bid')</option>
                                                <option value="vickrey">@trans('vickrey_auction')</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="category">@trans('category')</label>
                                            <select class="form-control" id="category" name="category">
                                                <option value="art">@trans('art_antiques')</option>
                                                <option value="jewelry">@trans('jewelry')</option>
                                                <option value="electronics">@trans('electronics')</option>
                                                <option value="vehicles">@trans('vehicles')</option>
                                                <option value="real_estate">@trans('real_estate')</option>
                                                <option value="collectibles">@trans('collectibles')</option>
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
                                <h6 class="m-0 font-weight-bold text-primary">@trans('auction_status')</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="status">@trans('status')</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="draft">@trans('draft')</option>
                                        <option value="scheduled">@trans('scheduled')</option>
                                        <option value="active">@trans('active')</option>
                                        <option value="ended">@trans('ended')</option>
                                        <option value="cancelled">@trans('cancelled')</option>
                                    </select>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured">
                                    <label class="form-check-label" for="is_featured">@trans('featured') @trans('auctions')</label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_verified" name="is_verified">
                                    <label class="form-check-label" for="is_verified">@trans('verified') @trans('auctions')</label>
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
                                <h6 class="m-0 font-weight-bold text-primary">@trans('auction_pricing')</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="starting_price">@trans('starting_price') <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" class="form-control" id="starting_price" name="starting_price" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="reserve_price">@trans('reserve_price')</label>
                                            <input type="number" step="0.01" class="form-control" id="reserve_price" name="reserve_price">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="buy_now_price">@trans('buy_now_price')</label>
                                            <input type="number" step="0.01" class="form-control" id="buy_now_price" name="buy_now_price">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="bid_increment">@trans('bid_increment')</label>
                                            <input type="number" step="0.01" class="form-control" id="bid_increment" name="bid_increment" value="1.00">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="currency">@trans('currency')</label>
                                            <select class="form-control" id="currency" name="currency">
                                                <option value="USD">@trans('usd')</option>
                                                <option value="EUR">@trans('eur')</option>
                                                <option value="GBP">@trans('gbp')</option>
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
                                <h6 class="m-0 font-weight-bold text-primary">@trans('timing_duration')</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="start_time">@trans('start_time')</label>
                                            <input type="datetime-local" class="form-control" id="start_time" name="start_time">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="end_time">@trans('end_time')</label>
                                            <input type="datetime-local" class="form-control" id="end_time" name="end_time">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="duration_hours">@trans('duration_hours')</label>
                                            <input type="number" class="form-control" id="duration_hours" name="duration_hours" min="1" max="168">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="timezone">@trans('timezone')</label>
                                            <select class="form-control" id="timezone" name="timezone">
                                                <option value="UTC">UTC</option>
                                                <option value="America/New_York">@trans('eastern_time')</option>
                                                <option value="America/Chicago">@trans('central_time')</option>
                                                <option value="America/Denver">@trans('mountain_time')</option>
                                                <option value="America/Los_Angeles">@trans('pacific_time')</option>
                                                <option value="Europe/London">@trans('london')</option>
                                                <option value="Europe/Paris">@trans('paris')</option>
                                                <option value="Asia/Tokyo">@trans('tokyo')</option>
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
                                <h6 class="m-0 font-weight-bold text-primary">@trans('media_files')</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="images">@trans('auction_images')</label>
                                    <input type="file" class="form-control" id="images" name="images[]" accept="image/*" multiple>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="documents">@trans('documents')</label>
                                    <input type="file" class="form-control" id="documents" name="documents[]" accept=".pdf,.jpg,.png" multiple>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="videos">@trans('videos')</label>
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
                                <h6 class="m-0 font-weight-bold text-primary">@trans('seo_settings')</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="meta_title">@trans('meta_title')</label>
                                    <input type="text" class="form-control" id="meta_title" name="meta_title">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="meta_description">@trans('meta_description')</label>
                                    <textarea class="form-control" id="meta_description" name="meta_description" rows="3"></textarea>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="meta_keywords">@trans('meta_keywords')</label>
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
                                <h6 class="m-0 font-weight-bold text-primary">@trans('multi_language_content')</h6>
                                <small class="text-muted">@trans('add_translations_different_languages')</small>
                            </div>
                            <div class="card-body">
                                <div id="translationManagerContainer">
                                    <div class="text-center py-4">
                                        <i class="fas fa-globe fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">@trans('loading_translations')</h5>
                                        <p class="text-muted">@trans('loading_translations_description')</p>
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
                            <i class="fas fa-save"></i> @trans('update') @trans('auctions')
                        </button>
                        <a href="/admin/auctions" class="btn btn-secondary btn-lg">
                            <i class="fas fa-times"></i> @trans('cancel')
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
    const auctionId = $('#auctionId').val();
    
    // Load auction data
    loadAuctionData(auctionId);
    
    // Form submission
    $('#auctionForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        $.ajax({
            url: `/api/admin/auctions/${auctionId}`,
            type: 'PUT',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    Swal.fire('@trans("success")!', '@trans("updated_successfully")', 'success')
                        .then(() => {
                            window.location.href = '/admin/auctions';
                        });
                } else {
                    Swal.fire('@trans("error")!', response.message, 'error');
                }
            },
            error: function(xhr) {
                const errors = xhr.responseJSON?.errors || {};
                let errorMessage = '@trans("validation_failed"):\n';
                for (const field in errors) {
                    errorMessage += `${field}: ${errors[field].join(', ')}\n`;
                }
                Swal.fire('@trans("error")!', errorMessage, 'error');
            }
        });
    });
});

function loadAuctionData(auctionId) {
    $.ajax({
        url: `/api/admin/auctions/${auctionId}`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                const auction = response.data;
                
                // Fill form fields
                $('#title').val(auction.title);
                $('#description').val(auction.description);
                $('#short_description').val(auction.short_description);
                $('#auction_type').val(auction.auction_type);
                $('#category').val(auction.category);
                $('#status').val(auction.status);
                $('#starting_price').val(auction.starting_price);
                $('#reserve_price').val(auction.reserve_price);
                $('#buy_now_price').val(auction.buy_now_price);
                $('#bid_increment').val(auction.bid_increment);
                $('#currency').val(auction.currency);
                $('#start_time').val(auction.start_time);
                $('#end_time').val(auction.end_time);
                $('#duration_hours').val(auction.duration_hours);
                $('#timezone').val(auction.timezone);
                $('#meta_title').val(auction.meta_title);
                $('#meta_description').val(auction.meta_description);
                $('#meta_keywords').val(auction.meta_keywords);
                
                // Set checkboxes
                $('#is_featured').prop('checked', auction.is_featured);
                $('#is_verified').prop('checked', auction.is_verified);
                
                // Load translation manager
                loadTranslationManager(auctionId);
            }
        },
        error: function(xhr) {
            Swal.fire('@trans("error")!', '@trans("failed_load_auction")', 'error');
        }
    });
}

function loadTranslationManager(auctionId) {
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
                    @trans('failed_load_translation_manager')
                </div>
            `);
        }
    });
}
</script>
@endsection
