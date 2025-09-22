<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XpertBid - Create Property</title>
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
                            <a class="nav-link" href="/admin/products">
                                <i class="fas fa-box me-2"></i>Products
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="/admin/properties">
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
                    <h1 class="h2"><i class="fas fa-plus me-2"></i>Create New Property</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <a href="/admin/properties" class="btn btn-sm btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Properties
                            </a>
                        </div>
                    </div>
                </div>

                <div class="form-container">
                    <form id="propertyForm" method="POST" action="/api/admin/properties">
                        @csrf
                        
                        <!-- Basic Information Section -->
                        <div class="section-header">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Basic Information</h5>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label required-field">Property Title</label>
                                    <input type="text" class="form-control" id="title" name="title" required>
                                </div>
                                <div class="mb-3">
                                    <label for="property_type" class="form-label required-field">Property Type</label>
                                    <select class="form-control" id="property_type" name="property_type" required>
                                        <option value="">Select Property Type</option>
                                        <option value="house">House</option>
                                        <option value="apartment">Apartment</option>
                                        <option value="condo">Condo</option>
                                        <option value="townhouse">Townhouse</option>
                                        <option value="villa">Villa</option>
                                        <option value="studio">Studio</option>
                                        <option value="penthouse">Penthouse</option>
                                        <option value="commercial">Commercial</option>
                                        <option value="office">Office</option>
                                        <option value="retail">Retail</option>
                                        <option value="warehouse">Warehouse</option>
                                        <option value="land">Land</option>
                                        <option value="farm">Farm</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="listing_type" class="form-label required-field">Listing Type</label>
                                    <select class="form-control" id="listing_type" name="listing_type" required>
                                        <option value="">Select Listing Type</option>
                                        <option value="sale">For Sale</option>
                                        <option value="rent">For Rent</option>
                                        <option value="lease">For Lease</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="price" class="form-label required-field">Price</label>
                                    <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="description" class="form-label required-field">Description</label>
                                    @include('admin.components.shopify-editor', [
                                        'name' => 'description',
                                        'value' => old('description'),
                                        'height' => 300,
                                        'placeholder' => 'Describe the property in detail...',
                                        'required' => true
                                    ])
                                </div>
                                <div class="mb-3">
                                    <label for="short_description" class="form-label">Short Description</label>
                                    @include('admin.components.shopify-editor', [
                                        'name' => 'short_description',
                                        'value' => old('short_description'),
                                        'height' => 150,
                                        'placeholder' => 'Brief property summary...'
                                    ])
                                </div>
                            </div>
                        </div>

                        <!-- Location Section -->
                        <div class="section-header">
                            <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Location</h5>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="address" class="form-label required-field">Address</label>
                                    <input type="text" class="form-control" id="address" name="address" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="city" class="form-label required-field">City</label>
                                    <input type="text" class="form-control" id="city" name="city" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="state" class="form-label required-field">State/Province</label>
                                    <input type="text" class="form-control" id="state" name="state" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="postal_code" class="form-label">Postal Code</label>
                                    <input type="text" class="form-control" id="postal_code" name="postal_code">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="country" class="form-label required-field">Country</label>
                                    <input type="text" class="form-control" id="country" name="country" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="neighborhood" class="form-label">Neighborhood</label>
                                    <input type="text" class="form-control" id="neighborhood" name="neighborhood">
                                </div>
                            </div>
                        </div>

                        <!-- Property Details Section -->
                        <div class="section-header">
                            <h5 class="mb-0"><i class="fas fa-home me-2"></i>Property Details</h5>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="bedrooms" class="form-label">Bedrooms</label>
                                    <input type="number" class="form-control" id="bedrooms" name="bedrooms" min="0">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="bathrooms" class="form-label">Bathrooms</label>
                                    <input type="number" step="0.5" class="form-control" id="bathrooms" name="bathrooms" min="0">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="square_feet" class="form-label">Square Feet</label>
                                    <input type="number" class="form-control" id="square_feet" name="square_feet" min="0">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="lot_size" class="form-label">Lot Size</label>
                                    <input type="number" class="form-control" id="lot_size" name="lot_size" min="0">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="year_built" class="form-label">Year Built</label>
                                    <input type="number" class="form-control" id="year_built" name="year_built" min="1800" max="2030">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="garage_spaces" class="form-label">Garage Spaces</label>
                                    <input type="number" class="form-control" id="garage_spaces" name="garage_spaces" min="0">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="parking_spaces" class="form-label">Parking Spaces</label>
                                    <input type="number" class="form-control" id="parking_spaces" name="parking_spaces" min="0">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="property_status" class="form-label">Property Status</label>
                                    <select class="form-control" id="property_status" name="property_status">
                                        <option value="available">Available</option>
                                        <option value="pending">Pending</option>
                                        <option value="sold">Sold</option>
                                        <option value="rented">Rented</option>
                                        <option value="off_market">Off Market</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Amenities Section -->
                        <div class="section-header">
                            <h5 class="mb-0"><i class="fas fa-star me-2"></i>Amenities & Features</h5>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <h6>Interior Features</h6>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="air_conditioning" name="amenities[]" value="air_conditioning">
                                    <label class="form-check-label" for="air_conditioning">Air Conditioning</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="heating" name="amenities[]" value="heating">
                                    <label class="form-check-label" for="heating">Heating</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="fireplace" name="amenities[]" value="fireplace">
                                    <label class="form-check-label" for="fireplace">Fireplace</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="hardwood_floors" name="amenities[]" value="hardwood_floors">
                                    <label class="form-check-label" for="hardwood_floors">Hardwood Floors</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="carpet" name="amenities[]" value="carpet">
                                    <label class="form-check-label" for="carpet">Carpet</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h6>Kitchen Features</h6>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="dishwasher" name="amenities[]" value="dishwasher">
                                    <label class="form-check-label" for="dishwasher">Dishwasher</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="refrigerator" name="amenities[]" value="refrigerator">
                                    <label class="form-check-label" for="refrigerator">Refrigerator</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="microwave" name="amenities[]" value="microwave">
                                    <label class="form-check-label" for="microwave">Microwave</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="granite_countertops" name="amenities[]" value="granite_countertops">
                                    <label class="form-check-label" for="granite_countertops">Granite Countertops</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="island" name="amenities[]" value="island">
                                    <label class="form-check-label" for="island">Kitchen Island</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h6>Exterior Features</h6>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="pool" name="amenities[]" value="pool">
                                    <label class="form-check-label" for="pool">Swimming Pool</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="garden" name="amenities[]" value="garden">
                                    <label class="form-check-label" for="garden">Garden</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="balcony" name="amenities[]" value="balcony">
                                    <label class="form-check-label" for="balcony">Balcony</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="patio" name="amenities[]" value="patio">
                                    <label class="form-check-label" for="patio">Patio</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="deck" name="amenities[]" value="deck">
                                    <label class="form-check-label" for="deck">Deck</label>
                                </div>
                            </div>
                        </div>

                        <!-- Media Section -->
                        <div class="section-header">
                            <h5 class="mb-0"><i class="fas fa-images me-2"></i>Media</h5>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="featured_image" class="form-label">Featured Image URL</label>
                                    <input type="url" class="form-control" id="featured_image" name="featured_image">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="video_url" class="form-label">Video URL</label>
                                    <input type="url" class="form-control" id="video_url" name="video_url">
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
                                    <label for="meta_tags" class="form-label">Meta Tags</label>
                                    <textarea class="form-control" id="meta_tags" name="meta_tags" rows="3"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden Fields -->
                        <input type="hidden" name="tenant_id" value="1">
                        <input type="hidden" name="vendor_id" value="1">
                        <input type="hidden" name="agent_id" value="1">

                        <!-- Submit Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="/admin/properties" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Create Property
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
        // Form submission
        document.getElementById('propertyForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            
            // Convert numeric fields
            if (data.price) data.price = parseFloat(data.price);
            if (data.bedrooms) data.bedrooms = parseInt(data.bedrooms);
            if (data.bathrooms) data.bathrooms = parseFloat(data.bathrooms);
            if (data.square_feet) data.square_feet = parseInt(data.square_feet);
            if (data.lot_size) data.lot_size = parseInt(data.lot_size);
            if (data.year_built) data.year_built = parseInt(data.year_built);
            if (data.garage_spaces) data.garage_spaces = parseInt(data.garage_spaces);
            if (data.parking_spaces) data.parking_spaces = parseInt(data.parking_spaces);
            
            // Convert amenities array
            const amenities = Array.from(document.querySelectorAll('input[name="amenities[]"]:checked')).map(cb => cb.value);
            data.amenities = amenities;
            
            fetch('/api/admin/properties', {
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
                        text: 'Property created successfully!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = '/admin/properties';
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
                    text: 'Failed to create property',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        });
    </script>
</body>
</html>
