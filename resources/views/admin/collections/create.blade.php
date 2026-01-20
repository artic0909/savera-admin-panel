@extends('admin.layouts.app')

@section('title', 'Create Collection')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Collections /</span> Create New</h4>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">Collection Details</h5>
                    <div class="card-body">
                        <form action="{{ route('admin.collections.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Collection Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="slug" class="form-label">Slug (Optional)</label>
                                <input type="text" class="form-control" id="slug" name="slug"
                                    value="{{ old('slug') }}">
                                <div class="form-text">Leave blank to auto-generate from name.</div>
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Collection Image (Thumbnail)</label>
                                <input class="form-control" type="file" id="image" name="image">
                            </div>
                            <div class="mb-3">
                                <label for="banner_image" class="form-label">Banner Image (Resolution: 3134x1390 px, Max:
                                    2MB)</label>
                                <input class="form-control" type="file" id="banner_image" name="banner_image">
                            </div>
                            <div class="mb-3">
                                <label for="products" class="form-label">Select Products</label>
                                <select class="form-select select2" id="products" name="products[]" multiple>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->product_name }} (ID:
                                            {{ $product->id }})</option>
                                    @endforeach
                                </select>
                                <div class="form-text">Hold Ctrl/Cmd to select multiple products.</div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                                    <label class="form-check-label" for="is_active">Active Status</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Create Collection</button>
                            <a href="{{ route('admin.collections.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#products').select2({
                placeholder: "Select Products",
                allowClear: true
            });
        });
    </script>
@endpush
