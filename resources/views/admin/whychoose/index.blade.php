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

    .why-choose-image {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
    }
</style>

@section('title', 'Why Choose Us')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">
                                Why Choose Us Images
                            </h5>
                            <p class="text-muted">Maximum 4 images allowed</p>
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
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($whyChooses as $whyChoose)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>
                                            <img src="{{ asset('storage/' . $whyChoose->image) }}" 
                                                 alt="Why Choose" 
                                                 class="why-choose-image">
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{{$whyChoose->id}}">
                                                Edit
                                            </a>
                                            <a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{$whyChoose->id}}">
                                                Delete
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-danger fw-bold">
                                            No record found
                                        </td>
                                    </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Floating Add (+) btn with modal - Only show if less than 4 images -->
@if($whyChooses->count() < 4)
<button class="floating-btn" data-bs-toggle="modal" data-bs-target="#addModal">+</button>
@endif


<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.whychoose.store') }}" class="modal-content" enctype="multipart/form-data">
            @csrf

            <div class="modal-header">
                <h5 class="modal-title">Add Why Choose Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <!-- Image -->
                <div class="mb-3">
                    <label class="form-label">Image <span class="text-danger">*</span></label>
                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                        accept="image/*" required>
                    @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Recommended size: 500x500px</small>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save me-1"></i> Save Image
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
@foreach ($whyChooses as $whyChoose)
<div class="modal fade" id="editModal{{$whyChoose->id}}" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.whychoose.update', $whyChoose->id) }}" class="modal-content" enctype="multipart/form-data">
            @csrf

            <div class="modal-header">
                <h5 class="modal-title">Edit Why Choose Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <!-- Current Image -->
                <div class="mb-3">
                    <label class="form-label">Current Image</label>
                    <div>
                        <img src="{{ asset('storage/' . $whyChoose->image) }}" 
                             alt="Current" 
                             style="max-width: 200px; border-radius: 8px;">
                    </div>
                </div>

                <!-- New Image -->
                <div class="mb-3">
                    <label class="form-label">New Image (Optional)</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <small class="text-muted">Leave empty to keep current image</small>
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
@foreach ($whyChooses as $whyChoose)
<div class="modal fade" id="deleteModal{{$whyChoose->id}}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('admin.whychoose.delete', $whyChoose->id) }}" class="modal-content">
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
