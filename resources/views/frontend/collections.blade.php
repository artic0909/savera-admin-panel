@extends('frontend.layouts.app')

@section('title', 'Collections')

@section('content')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/css/pages/collections.css') }}">
    @endpush

    <!-- inner banner -->
    <section>
        <div class="inner-banner-sec"
            style="background-image: url({{ $banner ? (str_contains($banner, 'assets/images/') ? asset($banner) : asset('storage/' . $banner)) : asset('assets/images/bg-carrasol-1.webp') }})">
            {{-- Banner content if needed later --}}
        </div>
    </section>
    <!-- /inner banner -->

    <section class="collections-page">
        <div class="wrapper">
            {{-- <div class="collection-header mb-5 text-center">
                <h1 style="margin-top: 0px">Our Collections</h1>
                <p class="text-muted mt-2">Explore our exclusive range of curated designs</p>
            </div> --}}

            <div class="collections-grid">
                @foreach ($collections as $collection)
                    <a href="{{ route('collection.show', $collection->slug) }}" class="collection-card">
                        <div class="collection-image-wrapper">
                            @if ($collection->image)
                                <img src="{{ asset('storage/' . $collection->image) }}" alt="{{ $collection->name }}"
                                    class="collection-image">
                            @else
                                <div class="collection-placeholder">
                                    <span>No Image</span>
                                </div>
                            @endif
                        </div>
                        <div class="collection-content">
                            <h3 class="collection-title">{{ $collection->name }}</h3>
                            {{-- <p class="collection-count">{{ $collection->products()->count() }} Products</p> --}}
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-5">
                {{ $collections->links('frontend.partials.custom_pagination') }}
            </div>
        </div>
    </section>
@endsection
