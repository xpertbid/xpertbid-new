@extends('admin.layouts.app')

@section('title', 'Products Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-box mr-2"></i>
                        Products Management
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-primary">{{ $products->total() }} Total Products</span>
                        <a href="/admin/products/simple/create" class="btn btn-primary btn-sm ml-2">
                            <i class="fas fa-plus mr-1"></i> Add Product
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Category</th>
                                    <th>Vendor</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Featured</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($product->featured_image)
                                                <img src="{{ $product->featured_image }}" 
                                                     alt="{{ $product->name }}" 
                                                     class="img-thumbnail mr-3" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center mr-3" 
                                                     style="width: 50px; height: 50px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $product->name }}</strong>
                                                @if($product->short_description)
                                                    <br><small class="text-muted">{{ Str::limit($product->short_description, 50) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <code>{{ $product->sku }}</code>
                                    </td>
                                    <td>
                                        {{ $product->category_name ?? 'N/A' }}
                                    </td>
                                    <td>
                                        {{ $product->vendor_name ?? 'N/A' }}
                                    </td>
                                    <td>
                                        <div>
                                            <strong>${{ number_format($product->price, 2) }}</strong>
                                            @if($product->sale_price && $product->sale_price < $product->price)
                                                <br><span class="text-success">Sale: ${{ number_format($product->sale_price, 2) }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $stockStatus = $product->stock_status ?? 'unknown';
                                            $stockColors = [
                                                'in_stock' => 'success',
                                                'out_of_stock' => 'danger',
                                                'on_backorder' => 'warning',
                                                'unknown' => 'secondary'
                                            ];
                                        @endphp
                                        <span class="badge badge-{{ $stockColors[$stockStatus] }}">
                                            {{ ucfirst(str_replace('_', ' ', $stockStatus)) }}
                                        </span>
                                        @if($product->quantity)
                                            <br><small class="text-muted">Qty: {{ $product->quantity }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'publish' => 'success',
                                                'draft' => 'warning',
                                                'private' => 'secondary',
                                                'pending' => 'info'
                                            ];
                                        @endphp
                                        <span class="badge badge-{{ $statusColors[$product->status] ?? 'secondary' }}">
                                            {{ ucfirst($product->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($product->is_featured)
                                            <span class="badge badge-warning">
                                                <i class="fas fa-star"></i> Featured
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($product->created_at)->format('M d, Y') }}<br>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($product->created_at)->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="#" class="btn btn-sm btn-outline-primary" title="View Product">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="#" class="btn btn-sm btn-outline-secondary" title="Edit Product">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    onclick="deleteProduct({{ $product->id }})"
                                                    title="Delete Product">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4">
                                        <i class="fas fa-box fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No Products Found</h5>
                                        <p class="text-muted">No products have been created yet.</p>
                                        <a href="/admin/products/simple/create" class="btn btn-primary">
                                            <i class="fas fa-plus mr-1"></i> Create First Product
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($products->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $products->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this product? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete Product</button>
            </div>
        </div>
    </div>
</div>

<script>
function deleteProduct(productId) {
    $('#deleteModal').modal('show');
    $('#confirmDelete').off('click').on('click', function() {
        // Here you would implement the actual delete functionality
        // For now, just close the modal
        $('#deleteModal').modal('hide');
        alert('Delete functionality would be implemented here for product ID: ' + productId);
    });
}
</script>
@endsection
