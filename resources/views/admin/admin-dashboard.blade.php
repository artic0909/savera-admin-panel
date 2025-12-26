@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 mb-4 order-0">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-sm-7">
                            <div class="card-body">
                                <h5 class="card-title text-primary">
                                    Welcome Back {{ auth()->user()->name }}! 🎉
                                </h5>
                                <p class="mb-4">Here is your store overview.</p>
                            </div>
                        </div>
                        <div class="col-sm-5 text-center text-sm-left">
                            <div class="card-body pb-0 px-0 px-md-4">
                                <img src="{{ asset('./admin/assets/img/illustrations/man-with-laptop-light.png') }}"
                                    height="140" alt="View Badge User"
                                    data-app-light-img="illustrations/man-with-laptop-light.png"
                                    data-app-dark-img="illustrations/man-with-laptop-dark.png" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Total Products --}}
            <div class="col-lg-3 col-md-6 col-6 mb-4">
                <a href="{{ route('admin.products.index') }}" class="text-decoration-none">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <span class="avatar-initial rounded bg-label-primary"><i
                                            class="bx bx-cube-alt"></i></span>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1 text-heading">Total Products</span>
                            <h3 class="card-title mb-2 text-heading">{{ $totalProducts }}</h3>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Total Sales --}}
            <div class="col-lg-3 col-md-6 col-6 mb-4">
                <a href="{{ route('admin.orders.index') }}" class="text-decoration-none">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <span class="avatar-initial rounded bg-label-success"><i
                                            class="bx bx-dollar"></i></span>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1 text-heading">Total Sales</span>
                            <h3 class="card-title mb-2 text-heading">₹{{ number_format($totalSales, 2) }}</h3>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Total Orders --}}
            <div class="col-lg-3 col-md-6 col-6 mb-4">
                <a href="{{ route('admin.orders.index') }}" class="text-decoration-none">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <span class="avatar-initial rounded bg-label-info"><i class="bx bx-cart"></i></span>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1 text-heading">Total Orders</span>
                            <h3 class="card-title mb-2 text-heading">{{ $totalOrders }}</h3>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Total Customers --}}
            <div class="col-lg-3 col-md-6 col-6 mb-4">
                <a href="{{ route('admin.orders.index') }}" class="text-decoration-none">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-user"></i></span>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1 text-heading">Total Customers</span>
                            <h3 class="card-title mb-2 text-heading">{{ $totalCustomers }}</h3>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Pending Orders --}}
            <div class="col-lg-3 col-md-6 col-6 mb-4">
                <a href="{{ route('admin.orders.index') }}" class="text-decoration-none">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <span class="avatar-initial rounded bg-label-danger"><i class="bx bx-time"></i></span>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1 text-heading">Pending Orders</span>
                            <h3 class="card-title mb-2 text-heading">{{ $pendingOrders }}</h3>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Stock Alerts --}}
            <div class="col-lg-3 col-md-6 col-6 mb-4">
                <a href="{{ route('admin.stock-notifications.index') }}" class="text-decoration-none">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <span class="avatar-initial rounded bg-label-secondary"><i
                                            class="bx bx-bell"></i></span>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1 text-heading">Stock Alerts</span>
                            <h3 class="card-title mb-2 text-heading">{{ $pendingNotifications }}</h3>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Total Coupons --}}
            <div class="col-lg-3 col-md-6 col-6 mb-4">
                <a href="{{ route('admin.coupons.index') }}" class="text-decoration-none">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-gift"></i></span>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1 text-heading">Total Coupons</span>
                            <h3 class="card-title mb-2 text-heading">{{ $totalCoupons }}</h3>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Total Materials --}}
            <div class="col-lg-3 col-md-6 col-6 mb-4">
                <a href="{{ route('admin.materials.index') }}" class="text-decoration-none">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <span class="avatar-initial rounded bg-label-info"><i
                                            class="bx bx-collection"></i></span>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1 text-heading">Total Materials</span>
                            <h3 class="card-title mb-2 text-heading">{{ $totalMaterials }}</h3>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Page specific scripts -->
@endpush
