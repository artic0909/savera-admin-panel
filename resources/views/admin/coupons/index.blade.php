@extends('admin.layouts.app')

@section('title', 'Coupons')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Marketing /</span> Coupons</h4>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Coupon List</h5>
                <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus"></i> Add New Coupon
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Type</th>
                                <th>Value</th>
                                <th>Validity</th>
                                <th>Usage</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse($coupons as $coupon)
                                <tr>
                                    <td><strong>{{ $coupon->code }}</strong></td>
                                    <td>
                                        @if ($coupon->type == 'fixed')
                                            <span class="badge bg-label-primary">Fixed</span>
                                        @else
                                            <span class="badge bg-label-info">Percent</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($coupon->type == 'fixed')
                                            ₹{{ number_format($coupon->value, 2) }}
                                        @else
                                            {{ $coupon->value }}%
                                        @endif
                                    </td>
                                    <td>
                                        @if ($coupon->valid_until)
                                            {{ $coupon->valid_until->format('d M Y') }}
                                        @else
                                            <span class="text-muted">No Expiry</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $coupon->used_count }} / {{ $coupon->usage_limit ?? '∞' }}
                                    </td>
                                    <td>
                                        @if ($coupon->status === 'active')
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <a href="{{ route('admin.coupons.edit', $coupon->id) }}"
                                                class="btn btn-success btn-sm">
                                                <i class="bx bx-edit-alt"></i>
                                            </a>
                                            <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this coupon?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"><i
                                                        class="bx bx-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No coupons found. <a
                                            href="{{ route('admin.coupons.create') }}">Create one now</a></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $coupons->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
