@extends('admin.layouts.app')

<style>
    .floating-btn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 55px;
        height: 55px;
        border-radius: 50%;
        background-color: #0d6efd;
        color: #fff;
        font-size: 30px;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        z-index: 999;
    }

    .floating-btn:hover {
        background-color: #084298;
    }
</style>

@section('title', 'All Categories')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">
                                All Categories
                            </h5>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img
                                src="{{ asset('./admin/assets/img/illustrations/man-with-laptop-light.png') }}"
                                height="140"
                                alt="View Badge User" />
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card-footer text-end">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Image</th>
                                        <th scope="col">Category Name</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($categories as $category)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td><img src="{{ asset($category->image) }}" width="50" height="50"></td>
                                        <td>{{ $category->name }}</td>
                                        <td>
                                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{{$category->id}}">Edit</a>
                                            <a href="{{ route('admin.categories.delete', $category->id) }}" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{$category->id}}">Delete</a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-danger fw-bold">
                                            No record found
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>

                                <!-- Pagination Section -->
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            @if ($categories->hasPages())
                                            <nav aria-label="Page navigation">
                                                <ul class="pagination justify-content-center mt-4 align-items-center">

                                                    <!-- {{-- Prev Button --}} -->
                                                    <li class="page-item {{ $categories->onFirstPage() ? 'disabled' : '' }}">
                                                        <a class="page-link btn btn-primary"
                                                            href="{{ $categories->previousPageUrl() }}">Prev</a>
                                                    </li>
                                                    &nbsp;
                                                    <!-- {{-- Page Input + Total --}} -->
                                                    <li class="page-item d-flex align-items-center" style="margin: 0 2px;">
                                                        <form action="" method="GET" class="d-flex align-items-center" style="margin:0; padding:0;">
                                                            <input type="number" name="page"
                                                                value="{{ $categories->currentPage() }}"
                                                                min="1"
                                                                max="{{ $categories->lastPage() }}"
                                                                readonly
                                                                class="form-control">
                                                            <input type="text"
                                                                value="/ {{ $categories->lastPage() }}"
                                                                readonly
                                                                class="form-control">
                                                        </form>
                                                    </li>
                                                    &nbsp;
                                                    <!-- {{-- Next Button --}} -->
                                                    <li class="page-item {{ !$categories->hasMorePages() ? 'disabled' : '' }}">
                                                        <a class="page-link btn btn-primary"
                                                            href="{{ $categories->nextPageUrl() }}">Next</a>
                                                    </li>

                                                </ul>
                                            </nav>
                                            @endif
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Floating Add (+) btn with modal -->
<button class="floating-btn" data-bs-toggle="modal" data-bs-target="#addModal">+</button>


<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.categories.store') }}" class="modal-content" enctype="multipart/form-data">
            @csrf

            <div class="modal-header">
                <h5 class="modal-title">Add Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <!-- Image Upload -->
                <div class="mb-3">
                    <label class="form-label">Category Image <span class="text-danger">*</span></label>
                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" required>
                    @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Category Name -->
                <div class="mb-3">
                    <label class="form-label">Category Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        placeholder="Enter category name" value="{{ old('name') }}" required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save me-1"></i> Save Category
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
@foreach ($categories as $category)
<div class="modal fade" id="editModal{{$category->id}}" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="editForm" class="modal-content" enctype="multipart/form-data" action="{{ route('admin.categories.update', $category->id) }}">
            @csrf

            <div class="modal-header">
                <h5 class="modal-title">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <!-- img show -->
                <img src="{{ asset($category->image) }}" width="100" height="100" class="mb-3"> <br> <br>
                <!-- Image Upload -->
                <label for="editImage" class="form-label">Category Image</label>
                <input type="file" name="image" id="editImage" class="form-control mb-3">
                <!-- Category Name -->
                <label for="editName" class="form-label">Category Name</label>
                <input type="text" name="name" id="editName" class="form-control" placeholder="Category Name" value="{{$category->name}}">
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-success" type="submit">Update</button>
            </div>
        </form>
    </div>
</div>
@endforeach

<!-- Delete Modal -->
@foreach ($categories as $category)
<div class="modal fade" id="deleteModal{{$category->id}}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" id="deleteForm" class="modal-content" action="{{ route('admin.categories.delete', $category->id) }}">
            @csrf

            <div class="modal-body text-center">
                <h5>Are you sure?</h5>
                <p class="text-muted">This action cannot be undone.</p>

                <div class="mt-3">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger" type="submit">Delete</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach



@endsection

@push('scripts')
@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var addModal = new bootstrap.Modal(document.getElementById('addModal'));
        addModal.show();
    });
</script>
@endif
@endpush