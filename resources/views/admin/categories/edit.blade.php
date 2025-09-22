@extends('admin.layouts.app')

@section('title', 'Edit Category')

@section('content')
<div class="row">
    <!-- Left Column - Form -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-layer-group me-2"></i>
                    Edit Category
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="/admin/categories/{{ $id }}">
                    @csrf
                    @method('PUT')
                    
                    <!-- Basic Information -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-info-circle me-2"></i>
                            Category Information
                        </h6>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Category Name *</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $category->name ?? '') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug" class="form-label">Slug</label>
                                    <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug', $category->slug ?? '') }}">
                                    <small class="text-muted">Auto-generated from name if left empty</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            @include('admin.components.shopify-editor', [
                                'name' => 'description',
                                'value' => old('description', $category->description ?? ''),
                                'height' => 150,
                                'placeholder' => 'Describe this category...'
                            ])
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="parent_id" class="form-label">Parent Category</label>
                                    <select class="form-select" id="parent_id" name="parent_id">
                                        <option value="">No Parent (Top Level)</option>
                                        <!-- Parent categories will be loaded via AJAX -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status & Settings -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-cog me-2"></i>
                            Status & Settings
                        </h6>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="is_active" class="form-label">Status</label>
                                    <select class="form-select" id="is_active" name="is_active">
                                        <option value="1" {{ old('is_active', $category->is_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('is_active', $category->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="is_featured" class="form-label">Featured Category</label>
                                    <select class="form-select" id="is_featured" name="is_featured">
                                        <option value="0" {{ old('is_featured', $category->is_featured ?? 0) == 0 ? 'selected' : '' }}>No</option>
                                        <option value="1" {{ old('is_featured', $category->is_featured ?? 0) == 1 ? 'selected' : '' }}>Yes</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="/admin/categories" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>
                            Update Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Column - Category Details & Preview -->
    <div class="col-lg-4">
        <!-- Category Details Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Category Details
                </h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-6">
                        <small class="text-muted">Category ID</small>
                        <div class="fw-bold">#{{ $id }}</div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Created</small>
                        <div class="fw-bold">{{ $category->created_at ? date('M d, Y', strtotime($category->created_at)) : 'N/A' }}</div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-6">
                        <small class="text-muted">Status</small>
                        <div>
                            <span class="badge bg-{{ $category->is_active ? 'success' : 'secondary' }}">
                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Featured</small>
                        <div>
                            <span class="badge bg-{{ $category->is_featured ? 'warning' : 'secondary' }}">
                                {{ $category->is_featured ? 'Yes' : 'No' }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-6">
                        <small class="text-muted">Sort Order</small>
                        <div class="fw-bold">{{ $category->sort_order ?? 0 }}</div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Parent</small>
                        <div class="fw-bold">{{ $category->parent_id ? 'Yes' : 'No' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Preview Card -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-eye me-2"></i>
                    Preview
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="bg-light rounded p-4">
                        <i class="fas fa-layer-group fa-3x text-muted mb-2"></i>
                        <p class="text-muted mb-0">Category Icon</p>
                    </div>
                </div>
                <div class="text-center">
                    <h6 id="preview-name">{{ $category->name ?? 'Category Name' }}</h6>
                    <p class="text-muted mb-0" id="preview-description">{{ $category->description ?? 'Category description will appear here...' }}</p>
                    <small class="text-muted" id="preview-slug">{{ $category->slug ?? 'category-slug' }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    setupPreview();
    loadParentCategories();
});

function setupPreview() {
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    const descriptionInput = document.getElementById('description');
    
    function updatePreview() {
        const name = nameInput.value || 'Category Name';
        const slug = slugInput.value || name.toLowerCase().replace(/\s+/g, '-');
        const description = descriptionInput.value || 'Category description will appear here...';
        
        document.getElementById('preview-name').textContent = name;
        document.getElementById('preview-description').textContent = description;
        document.getElementById('preview-slug').textContent = slug;
    }
    
    nameInput.addEventListener('input', function() {
        updatePreview();
        // Auto-generate slug if slug field is empty
        if (!document.getElementById('slug').value) {
            const slug = this.value.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '');
            document.getElementById('slug').value = slug;
        }
    });
    
    slugInput.addEventListener('input', updatePreview);
    descriptionInput.addEventListener('input', updatePreview);
}

function loadParentCategories() {
    // Mock data - replace with actual API call
    const categories = [
        { id: 1, name: 'Electronics' },
        { id: 2, name: 'Fashion' },
        { id: 3, name: 'Home & Garden' },
        { id: 4, name: 'Sports & Outdoors' }
    ];
    
    const select = document.getElementById('parent_id');
    categories.forEach(category => {
        const option = document.createElement('option');
        option.value = category.id;
        option.textContent = category.name;
        // Set selected if this is the current parent
        if (category.id == {{ $category->parent_id ?? 'null' }}) {
            option.selected = true;
        }
        select.appendChild(option);
    });
}
</script>
@endsection
