@extends('admin.layouts.app')

@section('title', 'Reports & Analytics')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Admin /</span> Reports & Exports
        </h4>

        <div class="row">
            <!-- Export Sales Report Card -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar avatar-md bg-label-info p-2 rounded me-3">
                                <i class="bx bx-trending-up fs-3"></i>
                            </div>
                            <h5 class="card-title mb-0">Sales & Revenue</h5>
                        </div>
                        <p class="card-text text-muted">A specialized report of all successful payments and realized revenue
                            transactions.</p>

                        <form action="{{ route('admin.reports.export') }}" method="GET">
                            <input type="hidden" name="type" value="sales">
                            <div class="mb-3">
                                <label class="form-label">Date Range</label>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <input type="date" name="start_date" class="form-control">
                                    </div>
                                    <div class="col-6">
                                        <input type="date" name="end_date" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-info w-100 text-white">
                                <i class="bx bx-download me-1"></i> Export Sales Excel
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Export Inventory Card -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar avatar-md bg-label-primary p-2 rounded me-3">
                                <i class="bx bx-package fs-3"></i>
                            </div>
                            <h5 class="card-title mb-0">Product Inventory</h5>
                        </div>
                        <p class="card-text text-muted">Generate a report of all products, their categories, and current
                            stock levels.</p>

                        <form action="{{ route('admin.reports.export') }}" method="GET">
                            <input type="hidden" name="type" value="inventory">
                            <div class="mb-3">
                                <label class="form-label">Date Range (Created At)</label>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <input type="date" name="start_date" class="form-control"
                                            placeholder="Start Date">
                                    </div>
                                    <div class="col-6">
                                        <input type="date" name="end_date" class="form-control" placeholder="End Date">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bx bx-download me-1"></i> Export Excel
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Export Orders Card -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar avatar-md bg-label-success p-2 rounded me-3">
                                <i class="bx bx-cart fs-3"></i>
                            </div>
                            <h5 class="card-title mb-0">Orders Report</h5>
                        </div>
                        <p class="card-text text-muted">Export detailed order history including customer info, amounts, and
                            statuses.</p>

                        <form action="{{ route('admin.reports.export') }}" method="GET">
                            <input type="hidden" name="type" value="orders">
                            <div class="mb-3">
                                <label class="form-label">Status Filter</label>
                                <select name="status" class="form-select">
                                    <option value="">All Statuses</option>
                                    <option value="pending">Pending</option>
                                    <option value="processing">Processing</option>
                                    <option value="shipped">Shipped</option>
                                    <option value="delivered">Delivered</option>
                                    <option value="cancelled">Cancelled</option>
                                    <option value="returned">Returned</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Date Range</label>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <input type="date" name="start_date" class="form-control">
                                    </div>
                                    <div class="col-6">
                                        <input type="date" name="end_date" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bx bx-download me-1"></i> Export Excel
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Returns & Cancellations Card -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar avatar-md bg-label-danger p-2 rounded me-3">
                                <i class="bx bx-undo fs-3"></i>
                            </div>
                            <h5 class="card-title mb-0">Returns & Cancellations</h5>
                        </div>
                        <p class="card-text text-muted">Quickly export only failed or returned orders for reconciliation.
                        </p>

                        <form action="{{ route('admin.reports.export') }}" method="GET">
                            <input type="hidden" name="type" value="orders">
                            <div class="mb-3">
                                <label class="form-label">Filter Type</label>
                                <select name="status" class="form-select" required>
                                    <option value="cancelled">Cancellations Only</option>
                                    <option value="returned">Returns Only</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Date Range</label>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <input type="date" name="start_date" class="form-control">
                                    </div>
                                    <div class="col-6">
                                        <input type="date" name="end_date" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bx bx-download me-1"></i> Export Excel
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
