@extends('admin.layouts.app')

@section('title', 'Stock Notifications')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-3 mb-0">
                <span class="text-muted fw-light">Inventory /</span> Stock Notifications
            </h4>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Customer Notification Requests</h5>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>WhatsApp/Phone</th>
                            <th>Customer</th>
                            <th>Request Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($notifications as $notification)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('storage/' . $notification->product->main_image) }}"
                                            alt="{{ $notification->product->product_name }}" class="rounded me-3"
                                            width="40" height="40" style="object-fit: cover;">
                                        <div>
                                            <span
                                                class="fw-bold d-block">{{ strlen($notification->product->product_name) > 20 ? substr($notification->product->product_name, 0, 20) . '...' : $notification->product->product_name }}</span>
                                            <small class="text-muted">Stock:
                                                {{ $notification->product->stock_quantity }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $notification->phone_number) }}"
                                        target="_blank" class="btn btn-sm btn-label-success">
                                        <i class="bx bxl-whatsapp me-1"></i> {{ $notification->phone_number }}
                                    </a>
                                </td>
                                <td>
                                    @if ($notification->customer)
                                        <span class="d-block">{{ $notification->customer->name }}</span>
                                        <small class="text-muted">{{ $notification->customer->email }}</small>
                                    @else
                                        <span class="text-muted">Guest</span>
                                    @endif
                                </td>
                                <td>{{ $notification->created_at->format('d M, Y h:i A') }}</td>
                                <td>
                                    @if ($notification->status == 'pending')
                                        <span class="badge bg-label-warning">Pending</span>
                                    @else
                                        <span class="badge bg-label-success">Notified</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <form
                                                action="{{ route('admin.stock-notifications.updateStatus', $notification->id) }}"
                                                method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="notified">
                                                <button type="submit" class="dropdown-item"><i
                                                        class="bx bx-check me-1"></i> Mark as Notified</button>
                                            </form>
                                            <form
                                                action="{{ route('admin.stock-notifications.updateStatus', $notification->id) }}"
                                                method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="pending">
                                                <button type="submit" class="dropdown-item"><i class="bx bx-redo me-1"></i>
                                                    Mark as Pending</button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bx bx-bell-off fs-1 d-block mb-3"></i>
                                        No notification requests found.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
@endsection
