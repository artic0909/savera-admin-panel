@extends('admin.layouts.app')

@section('title', 'Home Page Configuration')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Settings /</span> Home Page Configuration</h4>

        <div class="row">
            <!-- 1. Top Banner Section -->
            <div class="col-md-12 mb-4">
                <div class="card">
                    <h5 class="card-header">Top Banner Section (Exactly 4 Banners)</h5>
                    <div class="card-body">
                        <form action="{{ route('admin.home-settings.update-section', 'banners') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                @for ($i = 1; $i <= 4; $i++)
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Banner {{ $i }}</label>
                                        <div class="mb-2">
                                            @if (isset($settings["banner_$i"]))
                                                <img src="{{ str_contains($settings["banner_$i"], 'assets/images/') ? asset($settings["banner_$i"]) : asset('storage/' . $settings["banner_$i"]) }}"
                                                    class="img-fluid rounded border" style="max-height: 150px;">
                                            @endif
                                        </div>
                                        <input type="file" name="banner_{{ $i }}" class="form-control">
                                    </div>
                                @endfor
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Update Banners</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- 2. For Every Beginning Section -->
            <div class="col-md-12 mb-4">
                <div class="card">
                    <h5 class="card-header" id="beginning-header">
                        {{ $settings['beginning_heading'] ?? 'For Every Beginning Section' }}</h5>
                    <div class="card-body">
                        <form action="{{ route('admin.home-settings.update-section', 'beginning') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Section Heading</label>
                                <input type="text" name="beginning_heading" class="form-control heading-sync"
                                    data-header="#beginning-header" data-btn="#beginning-btn"
                                    value="{{ $settings['beginning_heading'] ?? '' }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Current Photos (Min 8, Max 20)</label>
                                <div class="row g-2 mb-3">
                                    @php
                                        $beginningPhotos = json_decode($settings['beginning_photos'] ?? '[]', true);
                                    @endphp
                                    @foreach ($beginningPhotos as $index => $photo)
                                        <div class="col-auto position-relative">
                                            <img src="{{ str_contains($photo, 'assets/images/') ? asset($photo) : asset('storage/' . $photo) }}"
                                                class="img-thumbnail"
                                                style="width: 100px; height: 100px; object-fit: cover;">
                                            <input type="hidden" name="beginning_photos_existing[]"
                                                value="{{ $photo }}">
                                            <div class="form-check mt-1">
                                                <input class="form-check-input remove-photo" type="checkbox" checked
                                                    onclick="if(!this.checked) this.closest('.col-auto').style.opacity='0.5'; else this.closest('.col-auto').style.opacity='1';">
                                                <small>Keep</small>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Add New Photos</label>
                                <input type="file" name="beginning_photos_new[]" class="form-control" multiple
                                    accept="image/*">
                                <small class="text-muted">You can select multiple photos. Total photos will be capped at
                                    20.</small>
                            </div>

                            <button type="submit" class="btn btn-primary" id="beginning-btn">Update
                                {{ $settings['beginning_heading'] ?? 'Beginning Section' }}</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- 3. For the moments that matter Section -->
            <div class="col-md-12 mb-4">
                <div class="card">
                    <h5 class="card-header" id="moments-header">
                        {{ $settings['moments_heading'] ?? 'For the moments that matter' }}</h5>
                    <div class="card-body">
                        <form action="{{ route('admin.home-settings.update-section', 'moments') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Section Heading</label>
                                <input type="text" name="moments_heading" class="form-control heading-sync"
                                    data-header="#moments-header" data-btn="#moments-btn"
                                    value="{{ $settings['moments_heading'] ?? '' }}">
                            </div>

                            <div class="row">
                                @php
                                    $moments = json_decode($settings['moments_videos'] ?? '[]', true);
                                @endphp
                                @for ($i = 0; $i < 4; $i++)
                                    <div class="col-md-6 mb-4 border-bottom pb-3">
                                        <h6>Video {{ $i + 1 }}</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label">Video File</label>
                                                @if (isset($moments[$i]['video']))
                                                    <div class="mb-2">
                                                        <video width="100%" height="auto" class="rounded border">
                                                            <source
                                                                src="{{ str_contains($moments[$i]['video'], 'assets/videos/') ? asset($moments[$i]['video']) : asset('storage/' . $moments[$i]['video']) }}"
                                                                type="video/mp4">
                                                        </video>
                                                    </div>
                                                    <input type="hidden" name="moments_video_existing_{{ $i }}"
                                                        value="{{ $moments[$i]['video'] }}">
                                                @endif
                                                <input type="file" name="moments_video_{{ $i }}"
                                                    class="form-control" accept="video/mp4">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Thumbnail Image</label>
                                                @if (isset($moments[$i]['thumbnail']))
                                                    <div class="mb-2">
                                                        <img src="{{ str_contains($moments[$i]['thumbnail'], 'assets/images/') ? asset($moments[$i]['thumbnail']) : asset('storage/' . $moments[$i]['thumbnail']) }}"
                                                            class="img-fluid rounded border" style="max-height: 100px;">
                                                    </div>
                                                    <input type="hidden"
                                                        name="moments_thumb_existing_{{ $i }}"
                                                        value="{{ $moments[$i]['thumbnail'] }}">
                                                @endif
                                                <input type="file" name="moments_thumb_{{ $i }}"
                                                    class="form-control" accept="image/*">
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>

                            <button type="submit" class="btn btn-primary" id="moments-btn">Update
                                {{ $settings['moments_heading'] ?? 'Moments Section' }}</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- 4. Why Choose Savera Section -->
            <div class="col-md-12 mb-4">
                <div class="card">
                    <h5 class="card-header" id="why-choose-header">
                        {{ $settings['why_choose_heading'] ?? 'Why Choose Savera Section' }}</h5>
                    <div class="card-body">
                        <form action="{{ route('admin.home-settings.update-section', 'why_choose') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Section Heading</label>
                                <input type="text" name="why_choose_heading" class="form-control heading-sync"
                                    data-header="#why-choose-header" data-btn="#why-choose-btn"
                                    value="{{ $settings['why_choose_heading'] ?? '' }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Center Logo</label>
                                <div class="mb-2">
                                    @if (isset($settings['why_choose_logo']))
                                        <img src="{{ str_contains($settings['why_choose_logo'], 'assets/images/') ? asset($settings['why_choose_logo']) : asset('storage/' . $settings['why_choose_logo']) }}"
                                            class="img-fluid rounded border" style="max-height: 80px;">
                                    @endif
                                </div>
                                <input type="file" name="why_choose_logo" class="form-control">
                            </div>

                            <div class="row">
                                @php
                                    $whyChoosePhotos = json_decode($settings['why_choose_photos'] ?? '[]', true);
                                @endphp
                                @for ($i = 0; $i < 4; $i++)
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Position {{ $i + 1 }} Photo</label>
                                        <div class="mb-2">
                                            @if (isset($whyChoosePhotos[$i]))
                                                <img src="{{ str_contains($whyChoosePhotos[$i], 'assets/images/') ? asset($whyChoosePhotos[$i]) : asset('storage/' . $whyChoosePhotos[$i]) }}"
                                                    class="img-fluid rounded border" style="max-height: 120px;">
                                                <input type="hidden"
                                                    name="why_choose_photo_existing_{{ $i }}"
                                                    value="{{ $whyChoosePhotos[$i] }}">
                                            @endif
                                        </div>
                                        <input type="file" name="why_choose_photo_{{ $i }}"
                                            class="form-control">
                                    </div>
                                @endfor
                            </div>

                            <button type="submit" class="btn btn-primary mt-3" id="why-choose-btn">Update
                                {{ $settings['why_choose_heading'] ?? 'Why Choose Section' }}</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- 5. Store Front Section (After Why Choose) -->
            <div class="col-md-12 mb-4">
                <div class="card">
                    <h5 class="card-header">Store Front Section (Full Width Image)</h5>
                    <div class="card-body">
                        <form action="{{ route('admin.home-settings.update-section', 'store_front') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Store Front Image</label>
                                <div class="mb-2">
                                    @if (isset($settings['store_front_image']))
                                        <img src="{{ str_contains($settings['store_front_image'], 'assets/images/') ? asset($settings['store_front_image']) : asset('storage/' . $settings['store_front_image']) }}"
                                            class="img-fluid rounded border" style="max-height: 250px;">
                                    @endif
                                </div>
                                <input type="file" name="store_front_image" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary">Update Store Front</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const syncInputs = document.querySelectorAll('.heading-sync');
            syncInputs.forEach(input => {
                input.addEventListener('input', function() {
                    const header = document.querySelector(this.dataset.header);
                    const btn = document.querySelector(this.dataset.btn);
                    const val = this.value.trim() || 'Section';

                    if (header) header.innerText = val;
                    if (btn) btn.innerText = 'Update ' + val;
                });
            });
        });
    </script>
@endpush
