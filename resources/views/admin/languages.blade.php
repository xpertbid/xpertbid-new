@extends('admin.layouts.app')

@section('title', 'Languages Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Languages Management</h1>
        <div>
            <a href="/admin/languages/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Language
            </a>
        </div>
    </div>

    <!-- Languages Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Languages List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="languagesTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Flag</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Native Name</th>
                            <th>RTL</th>
                            <th>Status</th>
                            <th>Default</th>
                            <th>Sort Order</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($languages as $language)
                        <tr>
                            <td>{{ $language->id }}</td>
                            <td>
                                @if($language->flag_url)
                                    <img src="{{ $language->flag_url }}" width="32" height="24" class="rounded">
                                @else
                                    <img src="/images/no-flag.png" width="32" height="24" class="rounded">
                                @endif
                            </td>
                            <td>{{ $language->name }}</td>
                            <td>
                                <span class="badge bg-info">{{ strtoupper($language->code) }}</span>
                            </td>
                            <td>{{ $language->native_name }}</td>
                            <td>
                                @if($language->direction === 'rtl')
                                    <span class="badge bg-warning">RTL</span>
                                @else
                                    <span class="badge bg-secondary">LTR</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $language->is_active ? 'success' : 'danger' }}">
                                    {{ $language->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                @if($language->is_default)
                                    <span class="badge bg-primary">
                                        <i class="fas fa-star"></i> Default
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Regular</span>
                                @endif
                            </td>
                            <td>{{ $language->sort_order }}</td>
                            <td>{{ \Carbon\Carbon::parse($language->created_at)->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" title="View" onclick="viewLanguage({{ $language->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="/admin/languages/{{ $language->id }}/edit" class="btn btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if(!$language->is_default)
                                        <button class="btn btn-outline-success" title="Set Default" onclick="setDefaultLanguage({{ $language->id }})">
                                            <i class="fas fa-star"></i>
                                        </button>
                                    @endif
                                    <button class="btn btn-outline-danger" title="Delete" onclick="deleteLanguage({{ $language->id }})">
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

<!-- View Language Modal -->
<div class="modal fade" id="viewLanguageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Language Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="languageDetails">
                <!-- Language details will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function viewLanguage(id) {
    // For now, just show a placeholder
    $('#languageDetails').html(`
        <div class="row">
            <div class="col-md-6">
                <h5>Language Information</h5>
                <p><strong>ID:</strong> ${id}</p>
                <p><strong>Status:</strong> Active</p>
                <p><strong>Default:</strong> No</p>
            </div>
            <div class="col-md-6">
                <h5>Details</h5>
                <p><strong>Code:</strong> EN</p>
                <p><strong>RTL:</strong> No</p>
                <p><strong>Sort Order:</strong> 1</p>
            </div>
        </div>
    `);
    $('#viewLanguageModal').modal('show');
}

function setDefaultLanguage(id) {
    Swal.fire({
        title: 'Set as Default?',
        text: "This will make this language the default for the system.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, set as default!'
    }).then((result) => {
        if (result.isConfirmed) {
            // For now, just show success message
            Swal.fire('Updated!', 'Default language has been updated.', 'success');
        }
    });
}

function deleteLanguage(id) {
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
            Swal.fire('Deleted!', 'Language has been deleted.', 'success');
        }
    });
}
</script>
@endsection
