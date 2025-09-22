@extends('admin.layouts.app')

@section('title', 'Tags Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tags Management</h1>
        <div>
            <a href="/admin/tags/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Tag
            </a>
        </div>
    </div>

    <!-- Tags Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tags List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="tagsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Color</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Sort Order</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tags as $tag)
                        <tr>
                            <td>{{ $tag->id }}</td>
                            <td>
                                <span class="badge bg-primary">{{ $tag->name }}</span>
                            </td>
                            <td>{{ $tag->slug }}</td>
                            <td>
                                @if($tag->color)
                                    <span class="badge" style="background-color: {{ $tag->color }}; color: white;">
                                        {{ $tag->color }}
                                    </span>
                                @else
                                    <span class="text-muted">No Color</span>
                                @endif
                            </td>
                            <td>
                                @if($tag->description)
                                    {{ Str::limit($tag->description, 50) }}
                                @else
                                    <span class="text-muted">No Description</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $tag->status ? 'success' : 'danger' }}">
                                    {{ $tag->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $tag->sort_order }}</td>
                            <td>{{ \Carbon\Carbon::parse($tag->created_at)->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" title="View" onclick="viewTag({{ $tag->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="/admin/tags/{{ $tag->id }}/edit" class="btn btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-outline-danger" title="Delete" onclick="deleteTag({{ $tag->id }})">
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

<!-- View Tag Modal -->
<div class="modal fade" id="viewTagModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tag Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="tagDetails">
                <!-- Tag details will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function viewTag(id) {
    // For now, just show a placeholder
    $('#tagDetails').html(`
        <div class="row">
            <div class="col-md-6">
                <h5>Tag Information</h5>
                <p><strong>ID:</strong> ${id}</p>
                <p><strong>Status:</strong> Active</p>
                <p><strong>Color:</strong> #007bff</p>
            </div>
            <div class="col-md-6">
                <h5>Details</h5>
                <p><strong>Sort Order:</strong> 0</p>
                <p><strong>Created:</strong> ${new Date().toLocaleDateString()}</p>
                <p><strong>Description:</strong> Sample tag description</p>
            </div>
        </div>
    `);
    $('#viewTagModal').modal('show');
}

function deleteTag(id) {
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
            Swal.fire('Deleted!', 'Tag has been deleted.', 'success');
        }
    });
}
</script>
@endsection