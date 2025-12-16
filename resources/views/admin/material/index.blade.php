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

@section('title', 'All Materials')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">
                                All Materials
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
                                        <th scope="col">Name</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($materials as $material)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $material->name }}</td>
                                        <td>{{ $material->price }}</td>
                                        <td>
                                            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{{$material->id}}">Edit</a>
                                            <a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{$material->id}}">Delete</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>

                                <!-- Pagination Section -->
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            @if ($materials->hasPages())
                                            <nav aria-label="Page navigation">
                                                <ul class="pagination justify-content-center mt-4 align-items-center">

                                                    <li class="page-item {{ $materials->onFirstPage() ? 'disabled' : '' }}">
                                                        <a class="page-link btn btn-primary"
                                                            href="{{ $materials->previousPageUrl() }}">Prev</a>
                                                    </li>
                                                    &nbsp;
                                                    <li class="page-item d-flex align-items-center" style="margin: 0 2px;">
                                                        <form action="" method="GET" class="d-flex align-items-center" style="margin:0; padding:0;">
                                                            <input type="number" name="page"
                                                                value="{{ $materials->currentPage() }}"
                                                                min="1"
                                                                max="{{ $materials->lastPage() }}"
                                                                readonly
                                                                class="form-control">
                                                            <input type="text"
                                                                value="/ {{ $materials->lastPage() }}"
                                                                readonly
                                                                class="form-control">
                                                        </form>
                                                    </li>
                                                    &nbsp;
                                                    <li class="page-item {{ !$materials->hasMorePages() ? 'disabled' : '' }}">
                                                        <a class="page-link btn btn-primary"
                                                            href="{{ $materials->nextPageUrl() }}">Next</a>
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
        <form method="POST" action="{{ route('admin.materials.store') }}" class="modal-content">
            @csrf

            <div class="modal-header">
                <h5 class="modal-title">Add Material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <!-- Name -->
                <div class="mb-3">
                    <label class="form-label">Material Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        placeholder="Enter material name" value="{{ old('name') }}" required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Price -->
                <div class="mb-3">
                    <label class="form-label">Price (1 gm) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror"
                        placeholder="Enter price" value="{{ old('price') }}" required>
                    @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save me-1"></i> Save Material
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
@foreach ($materials as $material)
<div class="modal fade" id="editModal{{$material->id}}" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.materials.update', $material->id) }}" class="modal-content">
            @csrf
            
            <div class="modal-header">
                <h5 class="modal-title">Edit Material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <!-- Name -->
                <div class="mb-3">
                    <label class="form-label">Material Name</label>
                    <input type="text" name="name" class="form-control" value="{{$material->name}}" required>
                </div>
                <!-- Price -->
                <div class="mb-3">
                    <label class="form-label">Price  (1 gm)</label>
                    <input type="number" step="0.01" name="price" class="form-control" value="{{$material->price}}" required>
                </div>
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
@foreach ($materials as $material)
<div class="modal fade" id="deleteModal{{$material->id}}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('admin.materials.delete', $material->id) }}" class="modal-content">
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
