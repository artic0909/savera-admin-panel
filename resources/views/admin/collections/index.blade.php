@extends('admin.layouts.app')

@section('title', 'Manage Collections')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Products /</span> Collections</h4>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Collections List</h5>
                <div>
                    <button type="button" class="btn btn-secondary me-2" data-bs-toggle="modal" data-bs-target="#bannerModal">
                        <i class="bx bx-image me-1"></i> Page Banner
                    </button>
                    <a href="{{ route('admin.collections.create') }}" class="btn btn-primary">Create New Collection</a>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Products</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($collections as $collection)
                            <tr>
                                <td>
                                    @if ($collection->image)
                                        <img src="{{ asset('storage/' . $collection->image) }}"
                                            alt="{{ $collection->name }}" class="d-block rounded" height="50"
                                            width="50" style="object-fit: cover;">
                                    @else
                                        <span class="badge bg-label-secondary">No Image</span>
                                    @endif
                                </td>
                                <td><strong>{{ $collection->name }}</strong></td>
                                <td>{{ $collection->products()->count() }} items</td>
                                <td>
                                    @if ($collection->is_active)
                                        <span class="badge bg-label-success me-1">Active</span>
                                    @else
                                        <span class="badge bg-label-secondary me-1">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="{{ route('admin.collections.edit', $collection->id) }}"><i
                                                    class="bx bx-edit-alt me-1"></i> Edit</a>
                                            <form action="{{ route('admin.collections.destroy', $collection->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this collection?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item"><i
                                                        class="bx bx-trash me-1"></i>
                                                    Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $collections->links() }}
            </div>
        </div>
    </div>

    <!-- Banner Modal -->
    <div class="modal fade" id="bannerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Collection Page Banner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.collections.banner') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="collections_page_banner" class="form-label">Banner Image (Resolution: 3134x1390 px,
                                Max: 2MB)</label>
                            <input class="form-control" type="file" id="collections_page_banner"
                                name="collections_page_banner" required>
                            @php
                                $bannerSetting = \App\Models\HomePageSetting::where(
                                    'key',
                                    'collections_page_banner',
                                )->first();
                            @endphp
                            @if ($bannerSetting && $bannerSetting->value)
                                <div class="mt-2">
                                    <label>Current Banner:</label>
                                    <img src="{{ str_contains($bannerSetting->value, 'assets/images/') ? asset($bannerSetting->value) : asset('storage/' . $bannerSetting->value) }}"
                                        class="img-fluid rounded mt-1" style="max-height: 150px;">
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
