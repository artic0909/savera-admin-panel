@extends('frontend.layouts.app')

@section('title', 'Home')

@section('content')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/css/pages/home.css') }}">
    @endpush
    <div class="banner">
        <div class="carousel-container">
            @for ($i = 1; $i <= 4; $i++)
                @if (isset($homeSettings["banner_$i"]))
                    <div class="carousel-slide {{ $i == 1 ? 'active' : '' }}">
                        <img src="{{ str_contains($homeSettings["banner_$i"], 'assets/images/') ? asset($homeSettings["banner_$i"]) : asset('storage/' . $homeSettings["banner_$i"]) }}"
                            alt="Model" />
                    </div>
                @endif
            @endfor

            <div class="carousel-dots">
                <span class="dot active"></span>
                <span class="dot"></span>
                <span class="dot"></span>
                <span class="dot"></span>
            </div>

            <div class="nav-arrow prev">
                <img src="{{ asset('assets/images/banner-arrow.png') }}" alt="Previous" />
            </div>
            <div class="nav-arrow next">
                <img src="{{ asset('assets/images/banner-arrow.png') }}" alt="Next" />
            </div>
        </div>
    </div>
    <div class="category">
        <div class="wrapper">
            <h2>Category</h2>
            <div class="cat-list">
                @foreach ($categories as $category)
                    <a href="#" class="cat-item {{ $loop->first ? 'active' : '' }}" data-id="{{ $category->id }}"
                        data-slug="{{ $category->slug }}" onclick="loadCategory(event, this)">
                        <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" />
                        <h3>{{ $category->name }}</h3>
                    </a>
                @endforeach
            </div>
        </div>
        <div class="product">
            <div class="wrapper">
                <div class="product-list" id="product-container">
                    @include('frontend.partials.product_loop', ['products' => $products])
                </div>
                <!-- Loader -->
                <div id="product-loader" style="display: none; text-align: center; width: 100%; padding: 20px;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <div class="explore-btn">
                    <a href="{{ route('category.show', ['slug' => $selectedCategory->slug ?? '#']) }}"
                        id="explore-more-btn">Explore More</a>
                </div>
            </div>
        </div>
    </div>


    <div class="beginning-section">
        <h2>{{ $homeSettings['beginning_heading'] ?? 'For Every Beginning' }}</h2>
        <div class="swiper beginning-swiper">
            <div class="swiper-wrapper">
                @php
                    $photos = json_decode($homeSettings['beginning_photos'] ?? '[]', true);
                    $displayPhotos = $photos;
                    if (count($photos) > 0 && count($photos) < 16) {
                        while (count($displayPhotos) < 16) {
                            foreach ($photos as $p) {
                                if (count($displayPhotos) >= 16) {
                                    break;
                                }
                                $displayPhotos[] = $p;
                            }
                        }
                    }
                @endphp
                @foreach ($displayPhotos as $photo)
                    <div class="swiper-slide">
                        <img src="{{ str_contains($photo, 'assets/images/') ? asset($photo) : asset('storage/' . $photo) }}"
                            alt="Beginning Collection" />
                    </div>
                @endforeach
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>
    <div class="media-section">
        <div class="wrapper">
            <h2>{{ $homeSettings['moments_heading'] ?? 'For the moments that matter' }}</h2>
            <div class="media-grid">
                @php
                    $moments = json_decode($homeSettings['moments_videos'] ?? '[]', true);
                @endphp
                @foreach ($moments as $index => $moment)
                    <div class="media-card {{ $index % 2 != 0 ? 'down' : '' }}" onclick="playVideo(this)">
                        <img src="{{ str_contains($moment['thumbnail'], 'assets/images/') ? asset($moment['thumbnail']) : asset('storage/' . $moment['thumbnail']) }}"
                            class="thumb" />
                        <div class="play-btn"><i class="fi fi-rr-play"></i></div>

                        <video class="video" controls>
                            <source
                                src="{{ str_contains($moment['video'], 'assets/videos/') ? asset($moment['video']) : asset('storage/' . $moment['video']) }}"
                                type="video/mp4">
                        </video>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    </div>

    </div>

    <div class="choose-section">
        <div class="wrapper">
            <h2>{{ $homeSettings['why_choose_heading'] ?? 'Why Choose Savera' }}</h2>
            <div class="choose-container">
                <div class="choose-grid">
                    @php
                        $whyChoosePhotos = json_decode($homeSettings['why_choose_photos'] ?? '[]', true);
                        $classes = ['item-tl', 'item-tr', 'item-bl', 'item-br'];
                    @endphp
                    @foreach ($whyChoosePhotos as $index => $photo)
                        <div class="choose-item {{ $classes[$index] ?? '' }}">
                            <img src="{{ str_contains($photo, 'assets/images/') ? asset($photo) : asset('storage/' . $photo) }}"
                                alt="Why Choose {{ $index + 1 }}" />
                        </div>
                    @endforeach
                </div>
                <div class="center-logo">
                    <div class="logo-inner">
                        @if (isset($homeSettings['why_choose_logo']))
                            <img src="{{ str_contains($homeSettings['why_choose_logo'], 'assets/images/') ? asset($homeSettings['why_choose_logo']) : asset('storage/' . $homeSettings['why_choose_logo']) }}"
                                alt="Logo" />
                        @else
                            <img src="{{ asset('assets/images/logo-icon.png') }}" alt="Logo" />
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- </div> -->

    <div class="store-section">
        <div class="store-image">
            @if (isset($homeSettings['store_front_image']))
                <img src="{{ str_contains($homeSettings['store_front_image'], 'assets/images/') ? asset($homeSettings['store_front_image']) : asset('storage/' . $homeSettings['store_front_image']) }}"
                    alt="Store Front" />
            @else
                <img src="{{ asset('assets/images/Priyanka-store-front-1.webp') }}" alt="Store Front" />
            @endif
        </div>
        <!-- <div class="store-content">
                                                                                                                                                                                                                                        {{-- <h2>Store Front</h2>
            <p>Sub Text</p>
            <a href="#" class="find-store-btn">Find Store</a> --}}
                                                                                                                                                                                                                                    </div> -->
    </div>

    @php
        $agent = request()->header('User-Agent');
        $isMobile = preg_match(
            '/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i',
            $agent,
        );
    @endphp

    @if ($isMobile && $storyVideos->count() > 0)
        <div class="moments-sticky-container" id="moments-section">
            <div class="moments-vertical-wrapper">
                <div class="swiper moments-vertical-swiper">
                    <div class="swiper-wrapper">
                        @foreach ($storyVideos as $storyVideo)
                            <div class="swiper-slide" data-id="{{ $storyVideo->id }}">
                                <div class="moments-section">
                                    <div class="moments-container">
                                        <!-- Background Video/Image -->
                                        <div class="moments-bg">
                                            <video class="story-video-bg" loop playsinline
                                                poster="{{ asset('assets/images/loding.gif') }}"
                                                style="width: 100%; height: 100%; object-fit: cover; position: absolute; top:0; left:0;">
                                                <source src="{{ asset('storage/' . $storyVideo->video_path) }}"
                                                    type="video/mp4">
                                            </video>

                                            <!-- Video Loader / Animated Thumbnail -->
                                            <div class="video-loader">
                                                <img src="{{ asset('assets/images/white-icon-logo.png') }}" alt="Loading"
                                                    class="spinning-logo">
                                            </div>

                                            <!-- Play/Pause Icon Overlay -->
                                            <div class="play-pause-overlay" style="display: none;">
                                                <i class="fi fi-sr-play"></i>
                                            </div>
                                            <div class="moments-overlay"></div>
                                        </div>

                                        <!-- Top Navigation -->
                                        <div class="moments-top">
                                            <div class="moments-logo">
                                                <a href="{{ route('home') }}"><img
                                                        src="{{ asset('assets/images/white-icon-logo.png') }}"
                                                        alt="Logo" /></a>
                                            </div>
                                            <div class="moments-actions">
                                                @php
                                                    $videoProductIds = $storyVideo->products->pluck('id')->toArray();
                                                    $isAllInWishlist =
                                                        count($videoProductIds) > 0 &&
                                                        collect($videoProductIds)->every(
                                                            fn($id) => in_array($id, $wishlistProductIds),
                                                        );
                                                @endphp
                                                <!-- Mute/Unmute Toggle -->
                                                <div class="action-item mute-toggle muted" onclick="toggleMute(this)">
                                                    <i class="fi fi-rr-volume-mute"></i>
                                                </div>
                                                <div class="action-item bulk-wishlist {{ $isAllInWishlist ? 'wishlist-active' : '' }}"
                                                    data-product-ids="{{ implode(',', $videoProductIds) }}"
                                                    onclick="toggleBulkWishlist(this)">
                                                    <i
                                                        class="fi {{ $isAllInWishlist ? 'fi-sr-heart' : 'fi-rr-heart' }}"></i>
                                                </div>
                                                <div class="action-item share-moment"
                                                    onclick="shareMoment('{{ $storyVideo->video_path }}', '{{ route('home') }}')">
                                                    <i class="fi fi-rr-share"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Bottom Product Slider -->
                                        <div class="moments-bottom">
                                            <div class="swiper moments-products-swiper">
                                                <div class="swiper-wrapper">
                                                    @foreach ($storyVideo->products as $product)
                                                        <div class="swiper-slide">
                                                            <a href="{{ route('product.show', $product->slug) }}"
                                                                class="moments-product-card text-decoration-none">
                                                                <div class="product-img-circle">
                                                                    <img src="{{ asset('storage/' . $product->main_image) }}"
                                                                        alt="{{ $product->product_name }}">
                                                                </div>
                                                                <div class="product-info">
                                                                    <h4 class="text-white">{{ $product->product_name }}
                                                                    </h4>
                                                                    <p class="text-white">₹{{ $product->display_price }}
                                                                    </p>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection

@push('scripts')
    <script>
        const homeConfig = {
            ajaxProductsUrl: "{{ route('ajax.products') }}",
            categoryUrl: "{{ url('/category') }}",
            wishlistAddUrl: "{{ route('wishlist.add') }}",
            wishlistRemoveUrl: "{{ route('wishlist.removeByProduct') }}",
            csrfToken: "{{ csrf_token() }}"
        };
    </script>
    <script src="{{ asset('assets/js/pages/home.js') }}"></script>
@endpush
