@extends('admin.layouts.app')

@section('title', 'Available Area (Pin)')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Delivery Settings /</span> Available Area (Pin)</h4>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Pincode List</h5>
                <a href="{{ route('admin.pincodes.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus"></i> Add New Pincode
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Pincode</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse($pincodes as $pincode)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $pincode->code }}</strong></td>
                                    <td>
                                        @if ($pincode->status === 'active')
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $pincode->created_at->format('d M Y') }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <a href="{{ route('admin.pincodes.edit', $pincode->id) }}"
                                                class="btn btn-success btn-sm">
                                                <i class="bx bx-edit-alt"></i>
                                            </a>
                                            <form action="{{ route('admin.pincodes.destroy', $pincode->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this pincode?');">
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
                                    <td colspan="5" class="text-center">No pincodes found. <a
                                            href="{{ route('admin.pincodes.create') }}">Add one now</a></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $pincodes->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
