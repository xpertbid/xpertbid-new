<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XpertBid - Create Vehicle</title>
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
                            <a class="nav-link" href="/admin/properties">
                                <i class="fas fa-home me-2"></i>Properties
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="/admin/vehicles">
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
                    <h1 class="h2"><i class="fas fa-plus me-2"></i>Create New Vehicle</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <a href="/admin/vehicles" class="btn btn-sm btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Vehicles
                            </a>
                        </div>
                    </div>
                </div>

                <div class="form-container">
                    <form id="vehicleForm" method="POST" action="/api/admin/vehicles">
                        @csrf
                        
                        <!-- Basic Information Section -->
                        <div class="section-header">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Basic Information</h5>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label required-field">Vehicle Title</label>
                                    <input type="text" class="form-control" id="title" name="title" required>
                                </div>
                                <div class="mb-3">
                                    <label for="make" class="form-label required-field">Make</label>
                                    <input type="text" class="form-control" id="make" name="make" required>
                                </div>
                                <div class="mb-3">
                                    <label for="model" class="form-label required-field">Model</label>
                                    <input type="text" class="form-control" id="model" name="model" required>
                                </div>
                                <div class="mb-3">
                                    <label for="year" class="form-label required-field">Year</label>
                                    <input type="number" class="form-control" id="year" name="year" min="1900" max="2030" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="description" class="form-label required-field">Description</label>
                                    @include('admin.components.shopify-editor', [
                                        'name' => 'description',
                                        'value' => old('description'),
                                        'height' => 300,
                                        'placeholder' => 'Describe the vehicle in detail...',
                                        'required' => true
                                    ])
                                </div>
                                <div class="mb-3">
                                    <label for="short_description" class="form-label">Short Description</label>
                                    @include('admin.components.shopify-editor', [
                                        'name' => 'short_description',
                                        'value' => old('short_description'),
                                        'height' => 150,
                                        'placeholder' => 'Brief vehicle summary...'
                                    ])
                                </div>
                            </div>
                        </div>

                        <!-- Vehicle Details Section -->
                        <div class="section-header">
                            <h5 class="mb-0"><i class="fas fa-car me-2"></i>Vehicle Details</h5>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="vehicle_type" class="form-label required-field">Vehicle Type</label>
                                    <select class="form-control" id="vehicle_type" name="vehicle_type" required>
                                        <option value="">Select Vehicle Type</option>
                                        <option value="car">Car</option>
                                        <option value="truck">Truck</option>
                                        <option value="suv">SUV</option>
                                        <option value="motorcycle">Motorcycle</option>
                                        <option value="boat">Boat</option>
                                        <option value="rv">RV</option>
                                        <option value="trailer">Trailer</option>
                                        <option value="commercial">Commercial</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="body_style" class="form-label">Body Style</label>
                                    <select class="form-control" id="body_style" name="body_style">
                                        <option value="">Select Body Style</option>
                                        <option value="sedan">Sedan</option>
                                        <option value="coupe">Coupe</option>
                                        <option value="hatchback">Hatchback</option>
                                        <option value="wagon">Wagon</option>
                                        <option value="convertible">Convertible</option>
                                        <option value="suv">SUV</option>
                                        <option value="truck">Truck</option>
                                        <option value="van">Van</option>
                                        <option value="pickup">Pickup</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="fuel_type" class="form-label">Fuel Type</label>
                                    <select class="form-control" id="fuel_type" name="fuel_type">
                                        <option value="">Select Fuel Type</option>
                                        <option value="gasoline">Gasoline</option>
                                        <option value="diesel">Diesel</option>
                                        <option value="electric">Electric</option>
                                        <option value="hybrid">Hybrid</option>
                                        <option value="plug_in_hybrid">Plug-in Hybrid</option>
                                        <option value="lpg">LPG</option>
                                        <option value="cng">CNG</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="transmission" class="form-label">Transmission</label>
                                    <select class="form-control" id="transmission" name="transmission">
                                        <option value="">Select Transmission</option>
                                        <option value="manual">Manual</option>
                                        <option value="automatic">Automatic</option>
                                        <option value="cvt">CVT</option>
                                        <option value="semi_automatic">Semi-Automatic</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Engine & Performance Section -->
                        <div class="section-header">
                            <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Engine & Performance</h5>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="engine_size" class="form-label">Engine Size (L)</label>
                                    <input type="number" step="0.1" class="form-control" id="engine_size" name="engine_size">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="horsepower" class="form-label">Horsepower</label>
                                    <input type="number" class="form-control" id="horsepower" name="horsepower">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="torque" class="form-label">Torque (lb-ft)</label>
                                    <input type="number" class="form-control" id="torque" name="torque">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="cylinders" class="form-label">Cylinders</label>
                                    <input type="number" class="form-control" id="cylinders" name="cylinders" min="1" max="16">
                                </div>
                            </div>
                        </div>

                        <!-- Mileage & Condition Section -->
                        <div class="section-header">
                            <h5 class="mb-0"><i class="fas fa-tachometer-alt me-2"></i>Mileage & Condition</h5>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="mileage" class="form-label">Mileage</label>
                                    <input type="number" class="form-control" id="mileage" name="mileage" min="0">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="condition" class="form-label">Condition</label>
                                    <select class="form-control" id="condition" name="condition">
                                        <option value="excellent">Excellent</option>
                                        <option value="very_good">Very Good</option>
                                        <option value="good">Good</option>
                                        <option value="fair">Fair</option>
                                        <option value="poor">Poor</option>
                                        <option value="salvage">Salvage</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="accident_history" class="form-label">Accident History</label>
                                    <select class="form-control" id="accident_history" name="accident_history">
                                        <option value="none">No Accidents</option>
                                        <option value="minor">Minor Accidents</option>
                                        <option value="moderate">Moderate Accidents</option>
                                        <option value="major">Major Accidents</option>
                                        <option value="unknown">Unknown</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="service_history" class="form-label">Service History</label>
                                    <select class="form-control" id="service_history" name="service_history">
                                        <option value="complete">Complete</option>
                                        <option value="partial">Partial</option>
                                        <option value="minimal">Minimal</option>
                                        <option value="unknown">Unknown</option>
                                    </select>
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
                                    <label for="listing_type" class="form-label required-field">Listing Type</label>
                                    <select class="form-control" id="listing_type" name="listing_type" required>
                                        <option value="">Select Listing Type</option>
                                        <option value="sale">For Sale</option>
                                        <option value="rent">For Rent</option>
                                        <option value="lease">For Lease</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="currency" class="form-label">Currency</label>
                                    <select class="form-control" id="currency" name="currency">
                                        <option value="USD">USD</option>
                                        <option value="EUR">EUR</option>
                                        <option value="GBP">GBP</option>
                                        <option value="CAD">CAD</option>
                                        <option value="AUD">AUD</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="available">Available</option>
                                        <option value="pending">Pending</option>
                                        <option value="sold">Sold</option>
                                        <option value="rented">Rented</option>
                                        <option value="off_market">Off Market</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Location Section -->
                        <div class="section-header">
                            <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Location</h5>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="location" class="form-label">Location</label>
                                    <input type="text" class="form-control" id="location" name="location">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control" id="city" name="city">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="state" class="form-label">State/Province</label>
                                    <input type="text" class="form-control" id="state" name="state">
                                </div>
                            </div>
                        </div>

                        <!-- Features Section -->
                        <div class="section-header">
                            <h5 class="mb-0"><i class="fas fa-star me-2"></i>Features & Amenities</h5>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <h6>Interior Features</h6>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="leather_seats" name="features[]" value="leather_seats">
                                    <label class="form-check-label" for="leather_seats">Leather Seats</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="heated_seats" name="features[]" value="heated_seats">
                                    <label class="form-check-label" for="heated_seats">Heated Seats</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="cooled_seats" name="features[]" value="cooled_seats">
                                    <label class="form-check-label" for="cooled_seats">Cooled Seats</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="sunroof" name="features[]" value="sunroof">
                                    <label class="form-check-label" for="sunroof">Sunroof</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="navigation" name="features[]" value="navigation">
                                    <label class="form-check-label" for="navigation">Navigation System</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h6>Safety Features</h6>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="abs" name="features[]" value="abs">
                                    <label class="form-check-label" for="abs">ABS</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="airbags" name="features[]" value="airbags">
                                    <label class="form-check-label" for="airbags">Airbags</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="backup_camera" name="features[]" value="backup_camera">
                                    <label class="form-check-label" for="backup_camera">Backup Camera</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="blind_spot" name="features[]" value="blind_spot">
                                    <label class="form-check-label" for="blind_spot">Blind Spot Monitor</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="lane_departure" name="features[]" value="lane_departure">
                                    <label class="form-check-label" for="lane_departure">Lane Departure Warning</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h6>Exterior Features</h6>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="alloy_wheels" name="features[]" value="alloy_wheels">
                                    <label class="form-check-label" for="alloy_wheels">Alloy Wheels</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="fog_lights" name="features[]" value="fog_lights">
                                    <label class="form-check-label" for="fog_lights">Fog Lights</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="led_lights" name="features[]" value="led_lights">
                                    <label class="form-check-label" for="led_lights">LED Lights</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="power_windows" name="features[]" value="power_windows">
                                    <label class="form-check-label" for="power_windows">Power Windows</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="power_locks" name="features[]" value="power_locks">
                                    <label class="form-check-label" for="power_locks">Power Locks</label>
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

                        <!-- Submit Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="/admin/vehicles" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Create Vehicle
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
        document.getElementById('vehicleForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            
            // Convert numeric fields
            if (data.price) data.price = parseFloat(data.price);
            if (data.year) data.year = parseInt(data.year);
            if (data.engine_size) data.engine_size = parseFloat(data.engine_size);
            if (data.horsepower) data.horsepower = parseInt(data.horsepower);
            if (data.torque) data.torque = parseInt(data.torque);
            if (data.cylinders) data.cylinders = parseInt(data.cylinders);
            if (data.mileage) data.mileage = parseInt(data.mileage);
            
            // Convert features array
            const features = Array.from(document.querySelectorAll('input[name="features[]"]:checked')).map(cb => cb.value);
            data.features = features;
            
            fetch('/api/admin/vehicles', {
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
                        text: 'Vehicle created successfully!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = '/admin/vehicles';
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
                    text: 'Failed to create vehicle',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        });
    </script>
</body>
</html>
