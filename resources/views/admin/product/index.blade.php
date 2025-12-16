@extends('admin.layouts.app')

@section('title', 'Products')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Product /</span> Product List</h4>

    <div class="card">
        <h5 class="card-header">Products</h5>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Details</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach($products as $product)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <img src="{{ asset('storage/' . $product->main_image) }}" alt="Product Image" width="50" height="50" class="rounded-circle">
                            </td>
                            <td>{{ $product->product_name }}</td>
                            <td>{{ $product->category->name }}</td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm view-details-btn" data-product-id="{{ $product->id }}">
                                    Details
                                </button>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-success btn-sm">
                                    <i class="bx bx-edit-alt"></i>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="bx bx-trash"></i></button>
                                </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Details Modal -->
<div class="modal fade" id="productDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel4">Product Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="product-details-content">
                <div class="text-center p-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modal instance
        const productDetailsModal = new bootstrap.Modal(document.getElementById('productDetailsModal'));
        const modalContent = document.getElementById('product-details-content');

        document.querySelectorAll('.view-details-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                const url = `{{ url('admin/products') }}/${productId}`;
                
                // Show modal with loader
                modalContent.innerHTML = `
                    <div class="text-center p-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                `;
                productDetailsModal.show();

                // Fetch details
                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        modalContent.innerHTML = html;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        modalContent.innerHTML = '<div class="alert alert-danger">Failed to load details.</div>';
                    });
            });
        });
    });
</script>
@endpush