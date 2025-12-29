@extends('admin.layouts.app')

@section('title', 'Edit Story Video')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #d9dee3;
            border-radius: 0.375rem;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #696cff;
        }
    </style>
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Edit Story Video</h5>
                <a href="{{ route('admin.story-videos.index') }}" class="btn btn-secondary">
                    <i class="bx bx-arrow-back me-1"></i> Back to List
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.story-videos.update', $video->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="title">Title (Optional)</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                                name="title" value="{{ old('title', $video->title) }}" placeholder="Enter video title">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="video">Change Video (Optional)</label>
                            <input type="file" class="form-control @error('video') is-invalid @enderror" id="video"
                                name="video" accept="video/*">
                            <div class="form-text">Leave blank to keep current video. Max size: 20MB. Aspect Ratio: 9:16
                                (Vertical).</div>

                            <div class="mt-2">
                                <label class="d-block mb-1">Current Video:</label>
                                <video width="150" height="200" style="object-fit: cover; border-radius: 8px;">
                                    <source src="{{ asset('storage/' . $video->video_path) }}" type="video/mp4">
                                </video>
                            </div>
                            @error('video')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="product_ids">Select Products <span
                                    class="text-danger">*</span></label>
                            <select class="form-select select2 @error('product_ids') is-invalid @enderror" id="product_ids"
                                name="product_ids[]" multiple="multiple" required>
                                @php
                                    $currentProductIds = $video->products->pluck('id')->toArray();
                                @endphp
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}"
                                        {{ in_array($product->id, old('product_ids', $currentProductIds)) ? 'selected' : '' }}>
                                        {{ $product->product_name }} (SKU: {{ $product->sku }})
                                    </option>
                                @endforeach
                            </select>
                            @error('product_ids')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                    value="1" {{ $video->is_active ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save me-1"></i> Update Story Video
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Search and select products",
                allowClear: true
            });
        });
    </script>
@endpush
