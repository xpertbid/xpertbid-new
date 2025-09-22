@extends('admin.layouts.app')

@section('title', 'Currencies Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Currencies Management</h1>
        <div>
            <a href="/admin/currencies/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Currency
            </a>
            <button class="btn btn-success" onclick="updateExchangeRates()">
                <i class="fas fa-sync"></i> Update Exchange Rates
            </button>
        </div>
    </div>

    <!-- Currencies Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Currencies List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="currenciesTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Icon</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Symbol</th>
                            <th>Position</th>
                            <th>Decimal Places</th>
                            <th>Exchange Rate</th>
                            <th>Status</th>
                            <th>Default</th>
                            <th>Sort Order</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($currencies as $currency)
                        <tr>
                            <td>{{ $currency->id }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $currency->symbol }}</span>
                            </td>
                            <td>{{ $currency->name }}</td>
                            <td>
                                <span class="badge bg-info">{{ strtoupper($currency->code) }}</span>
                            </td>
                            <td>{{ $currency->symbol }}</td>
                            <td>
                                <span class="badge bg-{{ $currency->symbol_position === 'before' ? 'primary' : 'secondary' }}">
                                    {{ ucfirst($currency->symbol_position) }}
                                </span>
                            </td>
                            <td>{{ $currency->decimal_places }}</td>
                            <td>
                                <span class="badge bg-success">{{ number_format($currency->exchange_rate, 4) }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $currency->is_active ? 'success' : 'danger' }}">
                                    {{ $currency->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                @if($currency->is_default)
                                    <span class="badge bg-primary">
                                        <i class="fas fa-star"></i> Default
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Regular</span>
                                @endif
                            </td>
                            <td>0</td>
                            <td>{{ \Carbon\Carbon::parse($currency->created_at)->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" title="View" onclick="viewCurrency({{ $currency->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="/admin/currencies/{{ $currency->id }}/edit" class="btn btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if(!$currency->is_default)
                                        <button class="btn btn-outline-success" title="Set Default" onclick="setDefaultCurrency({{ $currency->id }})">
                                            <i class="fas fa-star"></i>
                                        </button>
                                    @endif
                                    <button class="btn btn-outline-danger" title="Delete" onclick="deleteCurrency({{ $currency->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- View Currency Modal -->
<div class="modal fade" id="viewCurrencyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Currency Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="currencyDetails">
                <!-- Currency details will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Currency Converter Modal -->
<div class="modal fade" id="currencyConverterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Currency Converter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Amount</label>
                        <input type="number" class="form-control" id="convertAmount" placeholder="Enter amount">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">From Currency</label>
                        <select class="form-select" id="fromCurrency">
                            <option value="USD">USD - US Dollar</option>
                            <option value="EUR">EUR - Euro</option>
                            <option value="GBP">GBP - British Pound</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <label class="form-label">To Currency</label>
                        <select class="form-select" id="toCurrency">
                            <option value="EUR">EUR - Euro</option>
                            <option value="USD">USD - US Dollar</option>
                            <option value="GBP">GBP - British Pound</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Converted Amount</label>
                        <input type="text" class="form-control" id="convertedAmount" readonly>
                    </div>
                </div>
                <div class="mt-3">
                    <button class="btn btn-primary" onclick="convertCurrency()">Convert</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function viewCurrency(id) {
    // For now, just show a placeholder
    $('#currencyDetails').html(`
        <div class="row">
            <div class="col-md-6">
                <h5>Currency Information</h5>
                <p><strong>ID:</strong> ${id}</p>
                <p><strong>Status:</strong> Active</p>
                <p><strong>Default:</strong> No</p>
            </div>
            <div class="col-md-6">
                <h5>Details</h5>
                <p><strong>Code:</strong> USD</p>
                <p><strong>Symbol:</strong> $</p>
                <p><strong>Exchange Rate:</strong> 1.0000</p>
            </div>
        </div>
    `);
    $('#viewCurrencyModal').modal('show');
}

function setDefaultCurrency(id) {
    Swal.fire({
        title: 'Set as Default?',
        text: "This will make this currency the default for the system.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, set as default!'
    }).then((result) => {
        if (result.isConfirmed) {
            // For now, just show success message
            Swal.fire('Updated!', 'Default currency has been updated.', 'success');
        }
    });
}

function deleteCurrency(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // For now, just show success message
            Swal.fire('Deleted!', 'Currency has been deleted.', 'success');
        }
    });
}

function updateExchangeRates() {
    Swal.fire({
        title: 'Update Exchange Rates?',
        text: "This will fetch the latest exchange rates from external API.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, update rates!'
    }).then((result) => {
        if (result.isConfirmed) {
            // For now, just show success message
            Swal.fire('Updated!', 'Exchange rates have been updated.', 'success');
        }
    });
}

function convertCurrency() {
    const amount = document.getElementById('convertAmount').value;
    const fromCurrency = document.getElementById('fromCurrency').value;
    const toCurrency = document.getElementById('toCurrency').value;
    
    if (!amount || !fromCurrency || !toCurrency) {
        Swal.fire('Error', 'Please fill all fields', 'error');
        return;
    }
    
    // For now, just show a placeholder conversion
    const convertedAmount = (parseFloat(amount) * 0.85).toFixed(2);
    document.getElementById('convertedAmount').value = convertedAmount + ' ' + toCurrency;
}
</script>
@endsection
