@extends('admin.layouts.app')

@section('title', 'Create New Language')

@section('content')
<div class="row">
    <!-- Left Column - Form -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-language me-2"></i>
                    Create New Language
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="/admin/languages">
                    @csrf
                    
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
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="code" class="form-label">Language Code *</label>
                                    <input type="text" class="form-control" id="code" name="code" maxlength="5" required>
                                    <small class="text-muted">ISO language code (e.g., en, es, fr)</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="native_name" class="form-label">Native Name</label>
                                    <input type="text" class="form-control" id="native_name" name="native_name">
                                    <small class="text-muted">Name in the language itself</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="flag" class="form-label">Flag</label>
                                    <input type="text" class="form-control" id="flag" name="flag" placeholder="🇺🇸">
                                    <small class="text-muted">Flag emoji or image URL</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="direction" class="form-label">Text Direction</label>
                                    <select class="form-select" id="direction" name="direction">
                                        <option value="ltr">Left to Right (LTR)</option>
                                        <option value="rtl">Right to Left (RTL)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_format" class="form-label">Date Format</label>
                                    <select class="form-select" id="date_format" name="date_format">
                                        <option value="Y-m-d">YYYY-MM-DD</option>
                                        <option value="m/d/Y">MM/DD/YYYY</option>
                                        <option value="d/m/Y">DD/MM/YYYY</option>
                                        <option value="d-m-Y">DD-MM-YYYY</option>
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
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="is_default" class="form-label">Default Language</label>
                                    <select class="form-select" id="is_default" name="is_default">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
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
                            Create Language
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Column - Preview & Help -->
    <div class="col-lg-4">
        <!-- Preview Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-eye me-2"></i>
                    Preview
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="bg-light rounded p-4">
                        <div class="display-4" id="preview-flag">🇺🇸</div>
                        <div class="text-muted" id="preview-code">en</div>
                    </div>
                </div>
                <div class="text-center">
                    <h6 id="preview-name">Language Name</h6>
                    <p class="text-muted mb-0" id="preview-native">Native Name</p>
                    <small class="text-muted" id="preview-direction">LTR</small>
                </div>
            </div>
        </div>

        <!-- Help Card -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-question-circle me-2"></i>
                    Help & Tips
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="text-primary">Language Codes:</h6>
                    <ul class="small mb-0">
                        <li><strong>en:</strong> English</li>
                        <li><strong>es:</strong> Spanish</li>
                        <li><strong>fr:</strong> French</li>
                        <li><strong>de:</strong> German</li>
                        <li><strong>ar:</strong> Arabic</li>
                    </ul>
                </div>
                
                <div class="mb-3">
                    <h6 class="text-primary">Text Direction:</h6>
                    <ul class="small mb-0">
                        <li><strong>LTR:</strong> English, Spanish, French</li>
                        <li><strong>RTL:</strong> Arabic, Hebrew</li>
                    </ul>
                </div>
                
                <div class="mb-0">
                    <h6 class="text-primary">Date Formats:</h6>
                    <ul class="small mb-0">
                        <li><strong>YYYY-MM-DD:</strong> 2024-01-15</li>
                        <li><strong>MM/DD/YYYY:</strong> 01/15/2024</li>
                        <li><strong>DD/MM/YYYY:</strong> 15/01/2024</li>
                    </ul>
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
