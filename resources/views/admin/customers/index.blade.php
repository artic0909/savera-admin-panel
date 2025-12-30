@extends('admin.layouts.app')

@section('title', 'Customers')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 mb-4 order-0">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-sm-12">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title text-primary mb-0">All Customers</h5>
                                    <form action="{{ route('admin.customers.index') }}" method="GET" class="d-flex">
                                        <div class="input-group">
                                            <input type="text" name="search" class="form-control"
                                                placeholder="Search customer..." value="{{ request('search') }}">
                                            <button class="btn btn-outline-primary" type="submit">
                                                <i class="bx bx-search"></i>
                                            </button>
                                            @if (request('search'))
                                                <a href="{{ route('admin.customers.index') }}"
                                                    class="btn btn-outline-secondary">
                                                    <i class="bx bx-x"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </form>
                                </div>

                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif

                                <div class="table-responsive text-nowrap mt-4">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Email</th>
                                                <th scope="col">Phone</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Registered At</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($customers as $customer)
                                                <tr>
                                                    <th scope="row">
                                                        {{ ($customers->currentPage() - 1) * $customers->perPage() + $loop->iteration }}
                                                    </th>
                                                    <td>{{ $customer->name }}</td>
                                                    <td>{{ $customer->email }}</td>
                                                    <td>{{ $customer->phone ?? 'N/A' }}</td>
                                                    <td>
                                                        @if ($customer->status)
                                                            <span class="badge bg-success">Active</span>
                                                        @else
                                                            <span class="badge bg-danger">Inactive</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $customer->created_at->format('d M Y') }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-info btn-sm me-1"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#viewModal{{ $customer->id }}">
                                                            <i class="bx bx-show me-1"></i> View
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#deleteModal{{ $customer->id }}">
                                                            <i class="bx bx-trash me-1"></i> Delete
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center text-danger fw-bold">
                                                        No customers found
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                <div class="mt-4">
                                    {{ $customers->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Details Modals -->
    @foreach ($customers as $customer)
        <div class="modal fade" id="viewModal{{ $customer->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Customer Details: {{ $customer->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6>Personal Information</h6>
                                <p><strong>Name:</strong> {{ $customer->name }}</p>
                                <p><strong>Email:</strong> {{ $customer->email }}</p>
                                <p><strong>Phone:</strong> {{ $customer->phone ?? 'N/A' }}</p>
                                <p><strong>Status:</strong>
                                    @if ($customer->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </p>
                                <p><strong>Registered:</strong> {{ $customer->created_at->format('d M Y, h:i A') }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6>Saved Addresses</h6>
                                @forelse($customer->addresses as $address)
                                    <div class="card mb-2 bg-light">
                                        <div class="card-body p-2 px-3">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <span
                                                    class="badge bg-primary mb-1">{{ strtoupper($address->address_type) }}</span>
                                                @if ($address->is_default)
                                                    <span class="badge bg-warning text-dark">DEFAULT</span>
                                                @endif
                                            </div>
                                            <p class="mb-0 small"><strong>{{ $address->full_name }}</strong>
                                                ({{ $address->phone }})</p>
                                            <p class="mb-0 small">{{ $address->formatted_address }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted">No addresses saved yet.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Delete Modals -->
    @foreach ($customers as $customer)
        <div class="modal fade" id="deleteModal{{ $customer->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-header">
                            <h5 class="modal-title">Delete Customer</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <i class="bx bx-error-circle text-danger mb-3" style="font-size: 3rem;"></i>
                            <p>Are you sure you want to remove <strong>{{ $customer->name }}</strong>?</p>
                            <p class="text-muted small">This action cannot be undone and will permanently delete the
                                customer's account.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Confirm Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection
