@extends('admin.layouts.app')

@section('title', '@trans("edit") @trans("properties")')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">@trans('edit') @trans('properties')</h1>
        <div>
            <a href="/admin/properties" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> @trans('back') @trans('properties')
            </a>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs" id="propertyTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button" role="tab">
                <i class="fas fa-info-circle"></i> @trans('basic_information')
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab">
                <i class="fas fa-home"></i> @trans('property_details')
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="location-tab" data-bs-toggle="tab" data-bs-target="#location" type="button" role="tab">
                <i class="fas fa-map-marker-alt"></i> @trans('location')
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pricing-tab" data-bs-toggle="tab" data-bs-target="#pricing" type="button" role="tab">
                <i class="fas fa-dollar-sign"></i> @trans('pricing')
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

    <form id="propertyForm" enctype="multipart/form-data">
        <input type="hidden" id="propertyId" name="property_id" value="{{ $id }}">
        
        <div class="tab-content" id="propertyTabsContent">
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
                                    @include('admin.components.shopify-editor', [
                                        'name' => 'description',
                                        'value' => old('description', $property->description ?? ''),
                                        'height' => 300,
                                        'placeholder' => 'Describe the property in detail...'
                                    ])
                                </div>
                                <div class="form-group mb-3">
                                    <label for="short_description">@trans('short_description')</label>
                                    @include('admin.components.shopify-editor', [
                                        'name' => 'short_description',
                                        'value' => old('short_description', $property->short_description ?? ''),
                                        'height' => 150,
                                        'placeholder' => 'Brief property summary...'
                                    ])
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="property_type">@trans('property_type')</label>
                                            <select class="form-control" id="property_type" name="property_type">
                                                <option value="house">@trans('house')</option>
                                                <option value="apartment">@trans('apartment')</option>
                                                <option value="commercial">@trans('commercial')</option>
                                                <option value="land">@trans('land')</option>
                                                <option value="condo">@trans('condo')</option>
                                                <option value="townhouse">@trans('townhouse')</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="listing_type">@trans('listing_type')</label>
                                            <select class="form-control" id="listing_type" name="listing_type">
                                                <option value="sale">@trans('for_sale')</option>
                                                <option value="rent">@trans('for_rent')</option>
                                                <option value="both">@trans('sale_rent')</option>
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
                                <h6 class="m-0 font-weight-bold text-primary">@trans('property_status')</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="property_status">@trans('status')</label>
                                    <select class="form-control" id="property_status" name="property_status">
                                        <option value="available">@trans('available')</option>
                                        <option value="sold">@trans('sold')</option>
                                        <option value="rented">@trans('rented')</option>
                                        <option value="pending">@trans('pending')</option>
                                    </select>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured">
                                    <label class="form-check-label" for="is_featured">@trans('featured') @trans('properties')</label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_verified" name="is_verified">
                                    <label class="form-check-label" for="is_verified">@trans('verified') @trans('properties')</label>
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
                                <h6 class="m-0 font-weight-bold text-primary">@trans('property_details')</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="bedrooms">@trans('bedrooms')</label>
                                            <input type="number" class="form-control" id="bedrooms" name="bedrooms" min="0">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="bathrooms">@trans('bathrooms')</label>
                                            <input type="number" class="form-control" id="bathrooms" name="bathrooms" min="0" step="0.5">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="area_sqft">@trans('area_sqft')</label>
                                            <input type="number" class="form-control" id="area_sqft" name="area_sqft" min="0">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="lot_size">@trans('lot_size')</label>
                                            <input type="text" class="form-control" id="lot_size" name="lot_size">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="year_built">@trans('year_built')</label>
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
                                <h6 class="m-0 font-weight-bold text-primary">@trans('location_information')</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="address">@trans('address')</label>
                                    <input type="text" class="form-control" id="address" name="address">
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="city">@trans('city')</label>
                                            <input type="text" class="form-control" id="city" name="city">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="state">@trans('state')</label>
                                            <input type="text" class="form-control" id="state" name="state">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="postal_code">@trans('postal_code')</label>
                                            <input type="text" class="form-control" id="postal_code" name="postal_code">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="latitude">@trans('latitude')</label>
                                            <input type="number" step="any" class="form-control" id="latitude" name="latitude">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="longitude">@trans('longitude')</label>
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
                                <h6 class="m-0 font-weight-bold text-primary">@trans('pricing_information')</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="price">@trans('sale_price')</label>
                                            <input type="number" step="0.01" class="form-control" id="price" name="price">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="rent_price">@trans('rent_price')</label>
                                            <input type="number" step="0.01" class="form-control" id="rent_price" name="rent_price">
                                        </div>
                                    </div>
                                </div>
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
                                    <label for="images">@trans('property_images')</label>
                                    <input type="file" class="form-control" id="images" name="images[]" accept="image/*" multiple>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="floor_plans">@trans('floor_plans')</label>
                                    <input type="file" class="form-control" id="floor_plans" name="floor_plans[]" accept="image/*,.pdf" multiple>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="virtual_tour">@trans('virtual_tour')</label>
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
                            <i class="fas fa-save"></i> @trans('update') @trans('properties')
                        </button>
                        <a href="/admin/properties" class="btn btn-secondary btn-lg">
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
    const propertyId = $('#propertyId').val();
    
    // Load property data
    loadPropertyData(propertyId);
    
    // Form submission
    $('#propertyForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        $.ajax({
            url: `/api/admin/properties/${propertyId}`,
            type: 'PUT',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    Swal.fire('@trans("success")!', '@trans("updated_successfully")', 'success')
                        .then(() => {
                            window.location.href = '/admin/properties';
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

function loadPropertyData(propertyId) {
    $.ajax({
        url: `/api/admin/properties/${propertyId}`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                const property = response.data;
                
                // Fill form fields
                $('#title').val(property.title);
                $('#description').val(property.description);
                $('#short_description').val(property.short_description);
                $('#property_type').val(property.property_type);
                $('#listing_type').val(property.listing_type);
                $('#property_status').val(property.property_status);
                $('#bedrooms').val(property.bedrooms);
                $('#bathrooms').val(property.bathrooms);
                $('#area_sqft').val(property.area_sqft);
                $('#lot_size').val(property.lot_size);
                $('#year_built').val(property.year_built);
                $('#address').val(property.address);
                $('#city').val(property.city);
                $('#state').val(property.state);
                $('#postal_code').val(property.postal_code);
                $('#latitude').val(property.latitude);
                $('#longitude').val(property.longitude);
                $('#price').val(property.price);
                $('#rent_price').val(property.rent_price);
                $('#currency').val(property.currency);
                $('#meta_title').val(property.meta_title);
                $('#meta_description').val(property.meta_description);
                $('#meta_keywords').val(property.meta_keywords);
                
                // Set checkboxes
                $('#is_featured').prop('checked', property.is_featured);
                $('#is_verified').prop('checked', property.is_verified);
                
                // Load translation manager
                loadTranslationManager(propertyId);
            }
        },
        error: function(xhr) {
            Swal.fire('@trans("error")!', '@trans("failed_load_property")', 'error');
        }
    });
}

function loadTranslationManager(propertyId) {
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
                    @trans('failed_load_translation_manager')
                </div>
            `);
        }
    });
}
</script>
@endsection
