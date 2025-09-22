@extends('admin.layouts.app')

@section('title', 'Create Property')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create Property</h1>
        <div>
            <a href="/admin/properties" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Properties
            </a>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs" id="propertyTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button" role="tab">
                <i class="fas fa-info-circle"></i> Basic Information
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab">
                <i class="fas fa-home"></i> Property Details
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="location-tab" data-bs-toggle="tab" data-bs-target="#location" type="button" role="tab">
                <i class="fas fa-map-marker-alt"></i> Location
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pricing-tab" data-bs-toggle="tab" data-bs-target="#pricing" type="button" role="tab">
                <i class="fas fa-dollar-sign"></i> Pricing
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

    <!-- Error Messages -->
    @if($errors->any())
        <div class="alert alert-danger">
            <h6><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</h6>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="propertyForm" action="/admin/properties" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="tab-content" id="propertyTabsContent">
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
                                    <label for="title">Property Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label for="description">Description</label>
                                    @include('admin.components.shopify-editor', [
                                        'name' => 'description',
                                        'value' => old('description'),
                                        'height' => 300,
                                        'placeholder' => 'Describe the property in detail...'
                                    ])
                                </div>
                                <div class="form-group mb-3">
                                    <label for="short_description">Short Description</label>
                                    @include('admin.components.shopify-editor', [
                                        'name' => 'short_description',
                                        'value' => old('short_description'),
                                        'height' => 150,
                                        'placeholder' => 'Brief property summary...'
                                    ])
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="property_type">Property Type</label>
                                            <select class="form-control" id="property_type" name="property_type">
                                                <option value="house">House</option>
                                                <option value="apartment">Apartment</option>
                                                <option value="commercial">Commercial</option>
                                                <option value="land">Land</option>
                                                <option value="condo">Condo</option>
                                                <option value="townhouse">Townhouse</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="listing_type">Listing Type</label>
                                            <select class="form-control" id="listing_type" name="listing_type">
                                                <option value="sale">For Sale</option>
                                                <option value="rent">For Rent</option>
                                                <option value="both">Sale & Rent</option>
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
                                <h6 class="m-0 font-weight-bold text-primary">Property Status</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="property_status">Status</label>
                                    <select class="form-control" id="property_status" name="property_status">
                                        <option value="available">Available</option>
                                        <option value="sold">Sold</option>
                                        <option value="rented">Rented</option>
                                        <option value="pending">Pending</option>
                                    </select>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured">
                                    <label class="form-check-label" for="is_featured">Featured Property</label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_verified" name="is_verified">
                                    <label class="form-check-label" for="is_verified">Verified Property</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Property Details Tab -->
            <div class="tab-pane fade" id="details" role="tabpanel">
                <div class="row mt-3">
                    <div class="col-lg-8">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Property Details</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="bedrooms">Bedrooms</label>
                                            <input type="number" class="form-control" id="bedrooms" name="bedrooms" min="0">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="bathrooms">Bathrooms</label>
                                            <input type="number" class="form-control" id="bathrooms" name="bathrooms" min="0" step="0.5">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="area_sqft">Area (sq ft)</label>
                                            <input type="number" class="form-control" id="area_sqft" name="area_sqft" min="0">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="lot_size">Lot Size</label>
                                            <input type="text" class="form-control" id="lot_size" name="lot_size">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="year_built">Year Built</label>
                                            <input type="number" class="form-control" id="year_built" name="year_built" min="1800" max="2024">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location Tab -->
            <div class="tab-pane fade" id="location" role="tabpanel">
                <div class="row mt-3">
                    <div class="col-lg-8">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Location Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="address">Address</label>
                                    <input type="text" class="form-control" id="address" name="address">
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="city">City</label>
                                            <input type="text" class="form-control" id="city" name="city">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="state">State</label>
                                            <input type="text" class="form-control" id="state" name="state">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="postal_code">Postal Code</label>
                                            <input type="text" class="form-control" id="postal_code" name="postal_code">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="latitude">Latitude</label>
                                            <input type="number" step="any" class="form-control" id="latitude" name="latitude">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="longitude">Longitude</label>
                                            <input type="number" step="any" class="form-control" id="longitude" name="longitude">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing Tab -->
            <div class="tab-pane fade" id="pricing" role="tabpanel">
                <div class="row mt-3">
                    <div class="col-lg-8">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Pricing Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="price">Sale Price</label>
                                            <input type="number" step="0.01" class="form-control" id="price" name="price">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="rent_price">Rent Price</label>
                                            <input type="number" step="0.01" class="form-control" id="rent_price" name="rent_price">
                                        </div>
                                    </div>
                                </div>
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
                                    <label for="images">Property Images</label>
                                    <input type="file" class="form-control" id="images" name="images[]" accept="image/*" multiple>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="floor_plans">Floor Plans</label>
                                    <input type="file" class="form-control" id="floor_plans" name="floor_plans[]" accept="image/*,.pdf" multiple>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="virtual_tour">Virtual Tour</label>
                                    <input type="file" class="form-control" id="virtual_tour" name="virtual_tour[]" accept="video/*" multiple>
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
                                <small class="text-muted">Add translations for different languages. Save the property first to enable translations.</small>
                            </div>
                            <div class="card-body">
                                <div id="translationManagerContainer">
                                    <div class="text-center py-4">
                                        <i class="fas fa-globe fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">Save the property first to add translations</h5>
                                        <p class="text-muted">After saving the property, you'll be able to add content in multiple languages.</p>
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
                            <i class="fas fa-save"></i> Create Property
                        </button>
                        <a href="/admin/properties" class="btn btn-secondary btn-lg">
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
    // Form submission - let it submit normally
    $('#propertyForm').on('submit', function(e) {
        // Remove preventDefault to allow normal form submission
        // The form will submit to /admin/properties and redirect on success
    });
});

function showTranslationManager(propertyId) {
    // Switch to translations tab
    $('#translations-tab').tab('show');
    
    // Load translation manager component
    $.ajax({
        url: `/admin/components/translation-manager/property/${propertyId}`,
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
