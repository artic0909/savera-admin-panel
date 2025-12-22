@extends('admin.layouts.app')

@section('title', 'Manage Orders')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold">Manage Orders</h4>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.orders.index') }}">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control"
                                placeholder="Order#, Customer, Payment ID" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Order Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>
                                    Processing</option>
                                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped
                                </option>
                                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered
                                </option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Payment Status</label>
                            <select name="payment_status" class="form-select">
                                <option value="">All Payment Status</option>
                                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>
                                    Pending</option>
                                <option value="completed" {{ request('payment_status') == 'completed' ? 'selected' : '' }}>
                                    Completed</option>
                                <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed
                                </option>
                                <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>
                                    Refunded</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Payment Method</label>
                            <select name="payment_method" class="form-select">
                                <option value="">All Methods</option>
                                <option value="cod" {{ request('payment_method') == 'cod' ? 'selected' : '' }}>COD
                                </option>
                                <option value="online" {{ request('payment_method') == 'online' ? 'selected' : '' }}>Online
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Orders List</h5>
                <span class="badge bg-primary">Total: {{ $orders->total() }}</span>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Method</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($orders as $order)
                            <tr>
                                <td>
                                    <strong>{{ $order->order_number }}</strong>
                                </td>
                                <td>
                                    <div>{{ $order->customer->name }}</div>
                                    <small class="text-muted">{{ $order->customer->email }}</small>
                                </td>
                                <td>{{ $order->created_at->format('d M, Y') }}<br><small
                                        class="text-muted">{{ $order->created_at->format('h:i A') }}</small></td>
                                <td>{{ $order->items->count() }} item(s)</td>
                                <td><strong>₹{{ number_format($order->total, 2) }}</strong></td>
                                <td>
                                    <span
                                        class="badge 
    @if ($order->status == 'pending') bg-warning
    @elseif($order->status == 'processing') bg-info
    @elseif($order->status == 'shipped') bg-primary
    @elseif($order->status == 'delivered') bg-success
    @else bg-danger @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    <span
                                        class="badge 
                                        @if ($order->payment_status == 'completed') bg-success
                                        @elseif($order->payment_status == 'pending') bg-warning
                                        @else bg-danger @endif
                                    ">{{ ucfirst($order->payment_status) }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-label-primary">{{ strtoupper($order->payment_method) }}</span>
                                    @if ($order->transaction_id)
                                        <br><small class="text-muted text-truncate d-inline-block" style="max-width: 100px;"
                                            title="{{ $order->transaction_id }}">{{ $order->transaction_id }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('admin.orders.show', $order->id) }}">
                                                <i class="bx bx-show me-1"></i> View Details
                                            </a>
                                            <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this order?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="bx bx-trash me-1"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bx bx-shopping-bag" style="font-size: 48px;"></i>
                                        <p class="mt-2">No orders found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $orders->links() }}
            </div>
        </div>
    </div>

@endsection
