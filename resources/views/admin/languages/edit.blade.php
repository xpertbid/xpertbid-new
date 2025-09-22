@extends('admin.layouts.app')

@section('title', 'Edit Language')

@section('content')
<div class="row">
    <!-- Left Column - Form -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-language me-2"></i>
                    Edit Language
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="/admin/languages/{{ $id }}">
                    @csrf
                    @method('PUT')
                    
                    <!-- Basic Information -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-info-circle me-2"></i>
                            Language Information
                        </h6>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Language Name *</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $language->name ?? '') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="code" class="form-label">Language Code *</label>
                                    <input type="text" class="form-control" id="code" name="code" maxlength="5" value="{{ old('code', $language->code ?? '') }}" required>
                                    <small class="text-muted">ISO language code (e.g., en, es, fr)</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="native_name" class="form-label">Native Name</label>
                                    <input type="text" class="form-control" id="native_name" name="native_name" value="{{ old('native_name', $language->native_name ?? '') }}">
                                    <small class="text-muted">Name in the language itself</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="flag" class="form-label">Flag</label>
                                    <input type="text" class="form-control" id="flag" name="flag" value="{{ old('flag', $language->flag ?? '') }}" placeholder="🇺🇸">
                                    <small class="text-muted">Flag emoji or image URL</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="direction" class="form-label">Text Direction</label>
                                    <select class="form-select" id="direction" name="direction">
                                        <option value="ltr" {{ old('direction', $language->direction ?? 'ltr') == 'ltr' ? 'selected' : '' }}>Left to Right (LTR)</option>
                                        <option value="rtl" {{ old('direction', $language->direction ?? 'ltr') == 'rtl' ? 'selected' : '' }}>Right to Left (RTL)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_format" class="form-label">Date Format</label>
                                    <select class="form-select" id="date_format" name="date_format">
                                        <option value="Y-m-d" {{ old('date_format', $language->date_format ?? 'Y-m-d') == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                                        <option value="m/d/Y" {{ old('date_format', $language->date_format ?? 'Y-m-d') == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                                        <option value="d/m/Y" {{ old('date_format', $language->date_format ?? 'Y-m-d') == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                                        <option value="d-m-Y" {{ old('date_format', $language->date_format ?? 'Y-m-d') == 'd-m-Y' ? 'selected' : '' }}>DD-MM-YYYY</option>
                                    </select>
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
                                        <option value="1" {{ old('is_active', $language->is_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('is_active', $language->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="is_default" class="form-label">Default Language</label>
                                    <select class="form-select" id="is_default" name="is_default">
                                        <option value="0" {{ old('is_default', $language->is_default ?? 0) == 0 ? 'selected' : '' }}>No</option>
                                        <option value="1" {{ old('is_default', $language->is_default ?? 0) == 1 ? 'selected' : '' }}>Yes</option>
                                    </select>
                                    <small class="text-muted">Only one language can be default</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="/admin/languages" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>
                            Update Language
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Column - Language Details & Preview -->
    <div class="col-lg-4">
        <!-- Language Details Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Language Details
                </h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-6">
                        <small class="text-muted">Language ID</small>
                        <div class="fw-bold">#{{ $id }}</div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Created</small>
                        <div class="fw-bold">{{ $language->created_at ? date('M d, Y', strtotime($language->created_at)) : 'N/A' }}</div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-6">
                        <small class="text-muted">Status</small>
                        <div>
                            <span class="badge bg-{{ $language->is_active ? 'success' : 'secondary' }}">
                                {{ $language->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Default</small>
                        <div>
                            <span class="badge bg-{{ $language->is_default ? 'warning' : 'secondary' }}">
                                {{ $language->is_default ? 'Yes' : 'No' }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-6">
                        <small class="text-muted">Direction</small>
                        <div class="fw-bold">{{ strtoupper($language->direction ?? 'LTR') }}</div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Date Format</small>
                        <div class="fw-bold">{{ $language->date_format ?? 'Y-m-d' }}</div>
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
                        <div class="display-4" id="preview-flag">{{ $language->flag ?? '🇺🇸' }}</div>
                        <div class="text-muted" id="preview-code">{{ $language->code ?? 'en' }}</div>
                    </div>
                </div>
                <div class="text-center">
                    <h6 id="preview-name">{{ $language->name ?? 'Language Name' }}</h6>
                    <p class="text-muted mb-0" id="preview-native">{{ $language->native_name ?? 'Native Name' }}</p>
                    <small class="text-muted" id="preview-direction">{{ strtoupper($language->direction ?? 'LTR') }}</small>
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
});

function setupPreview() {
    const nameInput = document.getElementById('name');
    const codeInput = document.getElementById('code');
    const nativeNameInput = document.getElementById('native_name');
    const flagInput = document.getElementById('flag');
    const directionSelect = document.getElementById('direction');
    
    function updatePreview() {
        const name = nameInput.value || 'Language Name';
        const code = codeInput.value.toLowerCase() || 'en';
        const nativeName = nativeNameInput.value || 'Native Name';
        const flag = flagInput.value || '🇺🇸';
        const direction = directionSelect.value;
        
        document.getElementById('preview-name').textContent = name;
        document.getElementById('preview-code').textContent = code;
        document.getElementById('preview-native').textContent = nativeName;
        document.getElementById('preview-flag').textContent = flag;
        document.getElementById('preview-direction').textContent = direction.toUpperCase();
    }
    
    nameInput.addEventListener('input', updatePreview);
    codeInput.addEventListener('input', updatePreview);
    nativeNameInput.addEventListener('input', updatePreview);
    flagInput.addEventListener('input', updatePreview);
    directionSelect.addEventListener('change', updatePreview);
    
    // Auto-format language code to lowercase
    codeInput.addEventListener('input', function() {
        this.value = this.value.toLowerCase();
    });
}
</script>
@endsection
