@extends('admin.layouts.app')

@section('title', '@trans("edit") @trans("vehicles")')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">@trans('edit') @trans('vehicles')</h1>
        <div>
            <a href="/admin/vehicles" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> @trans('back') @trans('vehicles')
            </a>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs" id="vehicleTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button" role="tab">
                <i class="fas fa-info-circle"></i> @trans('basic_information')
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab">
                <i class="fas fa-car"></i> @trans('vehicle_details')
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="specs-tab" data-bs-toggle="tab" data-bs-target="#specs" type="button" role="tab">
                <i class="fas fa-cogs"></i> @trans('specifications')
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

    <form id="vehicleForm" enctype="multipart/form-data">
        <input type="hidden" id="vehicleId" name="vehicle_id" value="{{ $id }}">
        
        <div class="tab-content" id="vehicleTabsContent">
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
                                        'value' => old('description', $vehicle->description ?? ''),
                                        'height' => 300,
                                        'placeholder' => 'Describe the vehicle in detail...'
                                    ])
                                </div>
                                <div class="form-group mb-3">
                                    <label for="short_description">@trans('short_description')</label>
                                    @include('admin.components.shopify-editor', [
                                        'name' => 'short_description',
                                        'value' => old('short_description', $vehicle->short_description ?? ''),
                                        'height' => 150,
                                        'placeholder' => 'Brief vehicle summary...'
                                    ])
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="vehicle_type">@trans('vehicle_type')</label>
                                            <select class="form-control" id="vehicle_type" name="vehicle_type">
                                                <option value="car">@trans('car')</option>
                                                <option value="motorcycle">@trans('motorcycle')</option>
                                                <option value="truck">@trans('truck')</option>
                                                <option value="bus">@trans('bus')</option>
                                                <option value="boat">@trans('boat')</option>
                                                <option value="rv">@trans('rv')</option>
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
                                <h6 class="m-0 font-weight-bold text-primary">@trans('vehicle_status')</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="vehicle_status">@trans('status')</label>
                                    <select class="form-control" id="vehicle_status" name="vehicle_status">
                                        <option value="available">@trans('available')</option>
                                        <option value="sold">@trans('sold')</option>
                                        <option value="rented">@trans('rented')</option>
                                        <option value="pending">@trans('pending')</option>
                                    </select>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured">
                                    <label class="form-check-label" for="is_featured">@trans('featured') @trans('vehicles')</label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_verified" name="is_verified">
                                    <label class="form-check-label" for="is_verified">@trans('verified') @trans('vehicles')</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vehicle Details Tab -->
            <div class="tab-pane fade" id="details" role="tabpanel">
                <div class="row mt-3">
                    <div class="col-lg-8">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">@trans('vehicle_details')</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="make">@trans('make')</label>
                                            <input type="text" class="form-control" id="make" name="make">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="model">@trans('model')</label>
                                            <input type="text" class="form-control" id="model" name="model">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="year">@trans('year')</label>
                                            <input type="number" class="form-control" id="year" name="year" min="1900" max="2024">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="variant">@trans('variant')</label>
                                            <input type="text" class="form-control" id="variant" name="variant">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="body_type">@trans('body_type')</label>
                                            <input type="text" class="form-control" id="body_type" name="body_type">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="fuel_type">@trans('fuel_type')</label>
                                            <select class="form-control" id="fuel_type" name="fuel_type">
                                                <option value="petrol">@trans('petrol')</option>
                                                <option value="diesel">@trans('diesel')</option>
                                                <option value="electric">@trans('electric')</option>
                                                <option value="hybrid">@trans('hybrid')</option>
                                                <option value="lpg">@trans('lpg')</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="transmission">@trans('transmission')</label>
                                            <select class="form-control" id="transmission" name="transmission">
                                                <option value="manual">@trans('manual')</option>
                                                <option value="automatic">@trans('automatic')</option>
                                                <option value="semi-automatic">@trans('semi_automatic')</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Specifications Tab -->
            <div class="tab-pane fade" id="specs" role="tabpanel">
                <div class="row mt-3">
                    <div class="col-lg-8">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">@trans('vehicle_specifications')</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="mileage">@trans('mileage')</label>
                                            <input type="number" class="form-control" id="mileage" name="mileage" min="0">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="mileage_unit">@trans('mileage_unit')</label>
                                            <select class="form-control" id="mileage_unit" name="mileage_unit">
                                                <option value="km">@trans('kilometers')</option>
                                                <option value="miles">@trans('miles')</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="color">@trans('color')</label>
                                            <input type="text" class="form-control" id="color" name="color">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="doors">@trans('doors')</label>
                                            <input type="number" class="form-control" id="doors" name="doors" min="0">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="seats">@trans('seats')</label>
                                            <input type="number" class="form-control" id="seats" name="seats" min="0">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="engine_size">@trans('engine_size')</label>
                                            <input type="text" class="form-control" id="engine_size" name="engine_size">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="engine_power">@trans('engine_power')</label>
                                            <input type="text" class="form-control" id="engine_power" name="engine_power">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="condition">@trans('condition')</label>
                                            <select class="form-control" id="condition" name="condition">
                                                <option value="new">@trans('new')</option>
                                                <option value="used">@trans('used')</option>
                                                <option value="certified">@trans('certified')</option>
                                                <option value="refurbished">@trans('refurbished')</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="vin_number">@trans('vin_number')</label>
                                            <input type="text" class="form-control" id="vin_number" name="vin_number">
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
                                    <label for="images">@trans('vehicle_images')</label>
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
                            <i class="fas fa-save"></i> @trans('update') @trans('vehicles')
                        </button>
                        <a href="/admin/vehicles" class="btn btn-secondary btn-lg">
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
    const vehicleId = $('#vehicleId').val();
    
    // Load vehicle data
    loadVehicleData(vehicleId);
    
    // Form submission
    $('#vehicleForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        $.ajax({
            url: `/api/admin/vehicles/${vehicleId}`,
            type: 'PUT',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    Swal.fire('@trans("success")!', '@trans("updated_successfully")', 'success')
                        .then(() => {
                            window.location.href = '/admin/vehicles';
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

function loadVehicleData(vehicleId) {
    $.ajax({
        url: `/api/admin/vehicles/${vehicleId}`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                const vehicle = response.data;
                
                // Fill form fields
                $('#title').val(vehicle.title);
                $('#description').val(vehicle.description);
                $('#short_description').val(vehicle.short_description);
                $('#vehicle_type').val(vehicle.vehicle_type);
                $('#listing_type').val(vehicle.listing_type);
                $('#vehicle_status').val(vehicle.vehicle_status);
                $('#make').val(vehicle.make);
                $('#model').val(vehicle.model);
                $('#year').val(vehicle.year);
                $('#variant').val(vehicle.variant);
                $('#body_type').val(vehicle.body_type);
                $('#fuel_type').val(vehicle.fuel_type);
                $('#transmission').val(vehicle.transmission);
                $('#mileage').val(vehicle.mileage);
                $('#mileage_unit').val(vehicle.mileage_unit);
                $('#color').val(vehicle.color);
                $('#doors').val(vehicle.doors);
                $('#seats').val(vehicle.seats);
                $('#engine_size').val(vehicle.engine_size);
                $('#engine_power').val(vehicle.engine_power);
                $('#condition').val(vehicle.condition);
                $('#vin_number').val(vehicle.vin_number);
                $('#price').val(vehicle.price);
                $('#rent_price').val(vehicle.rent_price);
                $('#currency').val(vehicle.currency);
                $('#meta_title').val(vehicle.meta_title);
                $('#meta_description').val(vehicle.meta_description);
                $('#meta_keywords').val(vehicle.meta_keywords);
                
                // Set checkboxes
                $('#is_featured').prop('checked', vehicle.is_featured);
                $('#is_verified').prop('checked', vehicle.is_verified);
                
                // Load translation manager
                loadTranslationManager(vehicleId);
            }
        },
        error: function(xhr) {
            Swal.fire('@trans("error")!', '@trans("failed_load_vehicle")', 'error');
        }
    });
}

function loadTranslationManager(vehicleId) {
    $.ajax({
        url: `/admin/components/translation-manager/vehicle/${vehicleId}`,
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
