@extends('admin.layouts.app')

@section('title', 'Order Details - ' . $order->order_number)

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Order #{{ $order->order_number }}</h4>
                <small class="text-muted">Placed on {{ $order->created_at->format('d M, Y h:i A') }}</small>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back"></i> Back to Orders
            </a>
        </div>

        <div class="row">
            <!-- Order Details Card -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="mb-0">Order Items</h5>
                        <span
                            class="badge bg-
                            @if ($order->status == 'pending') warning
                            @elseif($order->status == 'processing') info
                            @elseif($order->status == 'shipped') primary
                            @elseif($order->status == 'delivered') success
                            @elseif($order->status == 'returned') secondary
                            @else danger @endif
                        ">{{ ucfirst($order->status) }}</span>
                    </div>
                    <div class="card-body">
                        @foreach ($order->items as $item)
                            <div class="d-flex gap-3 pb-3 mb-3 border-bottom">
                                <img src="{{ asset('storage/' . $item->product->main_image) }}"
                                    alt="{{ $item->product_name }}" class="rounded"
                                    style="width: 80px; height: 80px; object-fit: cover;">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $item->product_name }}</h6>
                                    @if ($item->metal_configuration)
                                        <small class="text-muted d-block">
                                            Material: {{ $item->metal_configuration['material_name'] ?? 'N/A' }} |
                                            Size: {{ $item->metal_configuration['size_name'] ?? 'N/A' }}
                                            @if (isset($item->metal_configuration['color_name']))
                                                | Color: {{ $item->metal_configuration['color_name'] }}
                                            @endif
                                        </small>
                                    @endif
                                    <small class="text-muted">Quantity: {{ $item->quantity }}</small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold">₹{{ number_format($item->subtotal, 2) }}</div>
                                    <small class="text-muted">@ ₹{{ number_format($item->price, 2) }} each</small>
                                </div>
                            </div>
                        @endforeach

                        <!-- Price Summary -->
                        <div class="mt-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>₹{{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax:</span>
                                <span>₹{{ number_format($order->tax, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping:</span>
                                <span
                                    class="text-success">{{ $order->shipping > 0 ? '₹' . number_format($order->shipping, 2) : 'FREE' }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <strong>Total:</strong>
                                <strong class="text-primary">₹{{ number_format($order->total, 2) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer & Address Info -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">Customer Details</h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-1"><strong>{{ $order->customer->name }}</strong></p>
                                <p class="mb-1 text-muted">{{ $order->customer->email }}</p>
                                <p class="mb-0 text-muted">{{ $order->customer->phone ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">Shipping Address</h6>
                            </div>
                            <div class="card-body">
                                @php $address = $order->shipping_address; @endphp
                                <p class="mb-1"><strong>{{ $address['full_name'] }}</strong></p>
                                <p class="mb-1">{{ $address['phone'] }}</p>
                                <p class="mb-1">{{ $address['address_line1'] }}</p>
                                @if (!empty($address['address_line2']))
                                    <p class="mb-1">{{ $address['address_line2'] }}</p>
                                @endif
                                <p class="mb-0">{{ $address['city'] }}, {{ $address['state'] }}
                                    {{ $address['postal_code'] }}</p>
                                <p class="mb-0">{{ $address['country'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($order->notes)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Order Notes</h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-0">{{ $order->notes }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Status Update Sidebar -->
            <div class="col-md-4">
                <!-- Order Status -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Update Order Status</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label">Order Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>
                                        Processing</option>
                                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped
                                    </option>
                                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>
                                        Delivered</option>
                                    <option value="returned" {{ $order->status == 'returned' ? 'selected' : '' }}>
                                        Returned</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>
                                        Cancelled</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Update Status</button>
                        </form>
                    </div>
                </div>

                <!-- Payment Status -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Payment Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label d-block">Payment Method</label>
                            <span class="badge bg-label-primary px-3 py-2">{{ strtoupper($order->payment_method) }}</span>
                        </div>

                        @if ($order->transaction_id)
                            <div class="mb-3">
                                <label class="form-label">Payment ID / Transaction ID</label>
                                <div class="p-2 border rounded bg-light">
                                    <code class="text-primary">{{ $order->transaction_id }}</code>
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('admin.orders.updatePaymentStatus', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label">Payment Status</label>
                                <select name="payment_status" class="form-select" required>
                                    <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>
                                        Pending</option>
                                    <option value="completed"
                                        {{ $order->payment_status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>
                                        Failed</option>
                                    <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>
                                        Refunded</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Update Payment</button>
                        </form>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Order Summary</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Order Number:</span>
                                <strong>{{ $order->order_number }}</strong>
                            </li>
                            <li class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Order Date:</span>
                                <span>{{ $order->created_at->format('d M, Y') }}</span>
                            </li>
                            <li class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Total Items:</span>
                                <span>{{ $order->items->count() }}</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span class="text-muted">Last Updated:</span>
                                <span>{{ $order->updated_at->format('d M, Y') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
