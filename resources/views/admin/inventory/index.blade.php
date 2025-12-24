@extends('admin.layouts.app')

@section('title', 'Inventory Management')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-3 mb-0">
                <span class="text-muted fw-light">Admin /</span> Inventory Management
            </h4>
            <a href="{{ route('admin.reports.index') }}?type=inventory" class="btn btn-outline-primary">
                <i class="bx bx-export me-1"></i> Export Inventory
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0">Product Stock Levels</h5>
                    </div>
                    <div class="col-md-6">
                        <form action="{{ route('admin.inventory.index') }}" method="GET"
                            class="d-flex align-items-center">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0">
                                    <i class="bx bx-search text-muted"></i>
                                </span>
                                <input type="text" name="search" class="form-control border-start-0 ps-0"
                                    placeholder="Search products..." value="{{ request('search') }}">
                                <button class="btn btn-primary" type="submit">Search</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Current Stock</th>
                            <th>Status</th>
                            <th class="text-center">Quick Update</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($products as $product)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('storage/' . $product->main_image) }}"
                                            alt="{{ $product->product_name }}" class="rounded me-3" width="40"
                                            height="40" style="object-fit: cover;">
                                        <div>
                                            <span class="fw-bold d-block">{{ $product->product_name }}</span>
                                            <small class="text-muted">ID: #{{ $product->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-label-info">{{ $product->category->name ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <span class="stock-display fw-bold" data-id="{{ $product->id }}">
                                        {{ $product->stock_quantity }}
                                    </span>
                                </td>
                                <td>
                                    @if ($product->stock_quantity <= 0)
                                        <span class="badge bg-label-danger">Out of Stock</span>
                                    @elseif($product->stock_quantity <= 5)
                                        <span class="badge bg-label-warning">Low Stock</span>
                                    @else
                                        <span class="badge bg-label-success">In Stock</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="input-group input-group-sm justify-content-center"
                                        style="max-width: 200px; margin: 0 auto;">
                                        <input type="number" class="form-control stock-input"
                                            value="{{ $product->stock_quantity }}" data-id="{{ $product->id }}"
                                            min="0">
                                        <button class="btn btn-primary update-stock-btn" type="button"
                                            data-id="{{ $product->id }}">
                                            <i class="bx bx-check"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bx bx-package fs-1 d-block mb-3"></i>
                                        No products found.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="mb-0 text-muted">Showing {{ $products->firstItem() ?? 0 }} to
                        {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products</p>
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const updateBtns = document.querySelectorAll('.update-stock-btn');

            updateBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const input = document.querySelector(`.stock-input[data-id="${id}"]`);
                    const newStock = input.value;

                    this.disabled = true;
                    this.innerHTML =
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';

                    fetch("{{ route('admin.inventory.updateStock') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                product_id: id,
                                stock_quantity: newStock
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            this.disabled = false;
                            this.innerHTML = '<i class="bx bx-check"></i>';

                            if (data.success) {
                                const display = document.querySelector(
                                    `.stock-display[data-id="${id}"]`);
                                display.innerText = data.new_stock;

                                // Update the status badge if needed
                                const row = this.closest('tr');
                                const badge = row.querySelector('td:nth-child(4) .badge');

                                if (data.new_stock <= 0) {
                                    badge.className = 'badge bg-label-danger';
                                    badge.innerText = 'Out of Stock';
                                } else if (data.new_stock <= 5) {
                                    badge.className = 'badge bg-label-warning';
                                    badge.innerText = 'Low Stock';
                                } else {
                                    badge.className = 'badge bg-label-success';
                                    badge.innerText = 'In Stock';
                                }

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Updated!',
                                    text: 'Stock quantity has been updated.',
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Something went wrong!'
                                });
                            }
                        })
                        .catch(error => {
                            this.disabled = false;
                            this.innerHTML = '<i class="bx bx-check"></i>';
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to update stock.'
                            });
                        });
                });
            });
        });
    </script>
@endpush
