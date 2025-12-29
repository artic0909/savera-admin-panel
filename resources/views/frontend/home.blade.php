@extends('frontend.layouts.app')

@section('title', 'Home')

@section('content')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/css/pages/home.css') }}">
    @endpush
    <div class="banner">
        <div class="carousel-container">
            <!-- Slide 1 -->
            <div class="carousel-slide active">
                <img src="{{ asset('assets/images/Banner-1.webp') }}" alt="Model" />
            </div>
            <div class="carousel-slide ">
                <img src="{{ asset('assets/images/Banner-2.webp') }}" alt="Model" />
            </div>
            <div class="carousel-slide ">
                <img src="{{ asset('assets/images/Bannerd-3.webp') }}" alt="Model" />
            </div>
            <div class="carousel-slide ">
                <img src="{{ asset('assets/images/Banner-4.webp') }}" alt="Model" />
            </div>




            <div class="carousel-dots">
                <span class="dot active"></span>
                <span class="dot"></span>
                <span class="dot"></span>
                <span class="dot"></span>
            </div>

            <div class="nav-arrow prev">
                <img src="assets/images/banner-arrow.png" alt="Previous" />
            </div>
            <div class="nav-arrow next">
                <img src="assets/images/banner-arrow.png" alt="Next" />
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
        <h2>For Every Beginning</h2>
        <div class="swiper beginning-swiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img src="{{ asset('assets/images/s1.jpg') }}" alt="Beginning Collection" />
                </div>
                <div class="swiper-slide">
                    <img src="{{ asset('assets/images/s2.jpg') }}" alt="Beginning Collection" />
                </div>
                <div class="swiper-slide">
                    <img src="{{ asset('assets/images/s3.jpg') }}" alt="Beginning Collection" />
                </div>
                <div class="swiper-slide">
                    <img src="{{ asset('assets/images/s4.jpg') }}" alt="Beginning Collection" />
                </div>
                <div class="swiper-slide">
                    <img src="{{ asset('assets/images/s5.jpg') }}" alt="Beginning Collection" />
                </div>
                <div class="swiper-slide">
                    <img src="{{ asset('assets/images/s6.jpg') }}" alt="Beginning Collection" />
                </div>
                <div class="swiper-slide">
                    <img src="{{ asset('assets/images/s7.jpg') }}" alt="Beginning Collection" />
                </div>
                <div class="swiper-slide">
                    <img src="{{ asset('assets/images/s8.jpg') }}" alt="Beginning Collection" />
                </div>
                <div class="swiper-slide">
                    <img src="{{ asset('assets/images/s1.jpg') }}" alt="Beginning Collection" />
                </div>
                <div class="swiper-slide">
                    <img src="{{ asset('assets/images/s2.jpg') }}" alt="Beginning Collection" />
                </div>
                <div class="swiper-slide">
                    <img src="{{ asset('assets/images/s3.jpg') }}" alt="Beginning Collection" />
                </div>
                <div class="swiper-slide">
                    <img src="{{ asset('assets/images/s4.jpg') }}" alt="Beginning Collection" />
                </div>
                <div class="swiper-slide">
                    <img src="{{ asset('assets/images/s5.jpg') }}" alt="Beginning Collection" />
                </div>
                <div class="swiper-slide">
                    <img src="{{ asset('assets/images/s6.jpg') }}" alt="Beginning Collection" />
                </div>
                <div class="swiper-slide">
                    <img src="{{ asset('assets/images/s7.jpg') }}" alt="Beginning Collection" />
                </div>
                <div class="swiper-slide">
                    <img src="{{ asset('assets/images/s8.jpg') }}" alt="Beginning Collection" />
                </div>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>
    <div class="media-section">
        <div class="wrapper">
            <h2>For the moments that matter</h2>
            <div class="media-grid">
                <div class="media-card" onclick="playVideo(this)">
                    <img src="assets/images/th-1.webp" class="thumb" />
                    <div class="play-btn"><i class="fi fi-rr-play"></i></div>

                    <video class="video" controls>
                        <source src="{{ asset('assets/videos/website-1st-video.mp4') }}" type="video/mp4">
                    </video>
                </div>
                <div class="media-card down" onclick="playVideo(this)">
                    <img src="assets/images/th-2.webp" class="thumb" />
                    <div class="play-btn"><i class="fi fi-rr-play"></i></div>

                    <video class="video" controls>
                        <source src="{{ asset('assets/videos/website-video-2nd.mp4') }}" type="video/mp4">
                    </video>
                </div>
                <div class="media-card" onclick="playVideo(this)">
                    <img src="assets/images/th-3.webp" class="thumb" />
                    <div class="play-btn"><i class="fi fi-rr-play"></i></div>

                    <video class="video" controls>
                        <source src="{{ asset('assets/videos/website-video-3rd.mp4') }}" type="video/mp4">
                    </video>
                </div>
                <div class="media-card down" onclick="playVideo(this)">
                    <img src="assets/images/th-4.webp" class="thumb" />
                    <div class="play-btn"><i class="fi fi-rr-play"></i></div>

                    <video class="video" controls>
                        <source src="{{ asset('assets/videos/website-video-4th.mp4') }}" type="video/mp4">
                    </video>
                </div>
            </div>
        </div>
    </div>

    </div>

    </div>

    <div class="choose-section">
        <div class="wrapper">
            <h2>Why Choose Savera
                <!-- <img class="saveratext" src="{{ asset('assets/images/saveratext.png') }}" alt="savera" /> -->
            </h2>
            <div class="choose-container">
                <div class="choose-grid">
                    <div class="choose-item item-tl">
                        <img src="{{ asset('storage/' . optional($chooses->get(0))->image) }}" alt="Why Choose 1" />
                    </div>
                    <div class="choose-item item-tr">
                        <img src="{{ asset('storage/' . optional($chooses->get(1))->image) }}" alt="Why Choose 2" />
                    </div>
                    <div class="choose-item item-bl">
                        <img src="{{ asset('storage/' . optional($chooses->get(2))->image) }}" alt="Why Choose 3" />
                    </div>
                    <div class="choose-item item-br">
                        <img src="{{ asset('storage/' . optional($chooses->get(3))->image) }}" alt="Why Choose 4" />
                    </div>
                </div>
                <div class="center-logo">
                    <div class="logo-inner">
                        <img src="assets/images/logo-icon.png" alt="Logo" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- </div> -->

    <div class="store-section">
        <div class="store-image">
            <img src="{{ asset('assets/images/Priyanka-store-front-1.webp') }}" alt="Store Front" />
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
        <div class="moments-sticky-container">
            <div class="moments-vertical-wrapper">
                <div class="swiper moments-vertical-swiper">
                    <div class="swiper-wrapper">
                        @foreach ($storyVideos as $storyVideo)
                            <div class="swiper-slide">
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
                                                <img src="{{ asset('assets/images/white-icon-logo.png') }}"
                                                    alt="Loading" class="spinning-logo">
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
