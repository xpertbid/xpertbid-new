@extends('admin.layouts.app')

@section('title', 'Edit Currency')

@section('content')
<div class="row">
    <!-- Left Column - Form -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-dollar-sign me-2"></i>
                    Edit Currency
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="/admin/currencies/{{ $id }}">
                    @csrf
                    @method('PUT')
                    
                    <!-- Basic Information -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-info-circle me-2"></i>
                            Currency Information
                        </h6>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Currency Name *</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $currency->name ?? '') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="code" class="form-label">Currency Code *</label>
                                    <input type="text" class="form-control" id="code" name="code" maxlength="3" value="{{ old('code', $currency->code ?? '') }}" required>
                                    <small class="text-muted">3-letter ISO code (e.g., USD, EUR, GBP)</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="symbol" class="form-label">Currency Symbol *</label>
                                    <input type="text" class="form-control" id="symbol" name="symbol" value="{{ old('symbol', $currency->symbol ?? '') }}" required>
                                    <small class="text-muted">Symbol used for display (e.g., $, €, £)</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="position" class="form-label">Symbol Position</label>
                                    <select class="form-select" id="position" name="position">
                                        <option value="before" {{ old('position', $currency->symbol_position ?? 'before') == 'before' ? 'selected' : '' }}>Before Amount ($100)</option>
                                        <option value="after" {{ old('position', $currency->symbol_position ?? 'before') == 'after' ? 'selected' : '' }}>After Amount (100$)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="decimal_places" class="form-label">Decimal Places</label>
                                    <input type="number" class="form-control" id="decimal_places" name="decimal_places" value="{{ old('decimal_places', $currency->decimal_places ?? 2) }}" min="0" max="4">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exchange_rate" class="form-label">Exchange Rate</label>
                                    <input type="number" class="form-control" id="exchange_rate" name="exchange_rate" step="0.000001" value="{{ old('exchange_rate', $currency->exchange_rate ?? '1.000000') }}">
                                    <small class="text-muted">Rate relative to base currency</small>
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
                                        <option value="1" {{ old('is_active', $currency->is_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('is_active', $currency->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="is_default" class="form-label">Default Currency</label>
                                    <select class="form-select" id="is_default" name="is_default">
                                        <option value="0" {{ old('is_default', $currency->is_default ?? 0) == 0 ? 'selected' : '' }}>No</option>
                                        <option value="1" {{ old('is_default', $currency->is_default ?? 0) == 1 ? 'selected' : '' }}>Yes</option>
                                    </select>
                                    <small class="text-muted">Only one currency can be default</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="/admin/currencies" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>
                            Update Currency
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Column - Currency Details & Preview -->
    <div class="col-lg-4">
        <!-- Currency Details Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Currency Details
                </h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-6">
                        <small class="text-muted">Currency ID</small>
                        <div class="fw-bold">#{{ $id }}</div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Created</small>
                        <div class="fw-bold">{{ $currency->created_at ? date('M d, Y', strtotime($currency->created_at)) : 'N/A' }}</div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-6">
                        <small class="text-muted">Status</small>
                        <div>
                            <span class="badge bg-{{ $currency->is_active ? 'success' : 'secondary' }}">
                                {{ $currency->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Default</small>
                        <div>
                            <span class="badge bg-{{ $currency->is_default ? 'warning' : 'secondary' }}">
                                {{ $currency->is_default ? 'Yes' : 'No' }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-6">
                        <small class="text-muted">Exchange Rate</small>
                        <div class="fw-bold">{{ number_format($currency->exchange_rate ?? 1, 6) }}</div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Decimal Places</small>
                        <div class="fw-bold">{{ $currency->decimal_places ?? 2 }}</div>
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
                        <div class="display-4 text-primary" id="preview-symbol">{{ $currency->symbol ?? '$' }}</div>
                        <div class="text-muted" id="preview-code">{{ $currency->code ?? 'USD' }}</div>
                    </div>
                </div>
                <div class="text-center">
                    <h6 id="preview-name">{{ $currency->name ?? 'Currency Name' }}</h6>
                    <p class="text-muted mb-0" id="preview-amount">
                        @if($currency->symbol_position == 'after')
                            {{ '100.' . str_repeat('0', $currency->decimal_places ?? 2) . ($currency->symbol ?? '$') }}
                        @else
                            {{ ($currency->symbol ?? '$') . '100.' . str_repeat('0', $currency->decimal_places ?? 2) }}
                        @endif
                    </p>
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
    const symbolInput = document.getElementById('symbol');
    const positionSelect = document.getElementById('position');
    const decimalPlacesInput = document.getElementById('decimal_places');
    
    function updatePreview() {
        const name = nameInput.value || 'Currency Name';
        const code = codeInput.value.toUpperCase() || 'USD';
        const symbol = symbolInput.value || '$';
        const position = positionSelect.value;
        const decimalPlaces = parseInt(decimalPlacesInput.value) || 2;
        
        document.getElementById('preview-name').textContent = name;
        document.getElementById('preview-code').textContent = code;
        document.getElementById('preview-symbol').textContent = symbol;
        
        const amount = '100.' + '0'.repeat(decimalPlaces);
        const displayAmount = position === 'before' ? symbol + amount : amount + symbol;
        document.getElementById('preview-amount').textContent = displayAmount;
    }
    
    nameInput.addEventListener('input', updatePreview);
    codeInput.addEventListener('input', updatePreview);
    symbolInput.addEventListener('input', updatePreview);
    positionSelect.addEventListener('change', updatePreview);
    decimalPlacesInput.addEventListener('input', updatePreview);
    
    // Auto-format currency code to uppercase
    codeInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
}
</script>
@endsection
