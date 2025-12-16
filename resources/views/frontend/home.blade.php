@extends('frontend.layouts.app')

@section('title', 'Home')

@section('content')
    <div class="banner">
        <div class="carousel-container">
            <!-- Slide 1 -->
            <div class="carousel-slide active">
                <div class="content">
                    <span>Welcome Offer</span>
                    <h2>Flat <span>₹</span>200 OFF</h2>
                    <div class="coupon-btn">Use Code: WLCM200</div>
                </div>
                <div class="image">
                    <img src="assets/images/banner-2.png" alt="Model" />
                </div>
            </div>

            <!-- Slide 2 (Duplicate for demo) -->
            <div class="carousel-slide">
                <div class="content">
                    <span>Exclusive Deal</span>
                    <h2>Gold Plated Rings</h2>
                    <div class="coupon-btn">Shop Now</div>
                </div>
                <div class="image">
                    <img src="assets/images/banner-2.png" alt="Model" />
                </div>
            </div>

            <!-- Slide 3 (Duplicate for demo) -->
            <div class="carousel-slide">
                <div class="content">
                    <span>New Arrivals</span>
                    <h2>Elegant Necklaces</h2>
                    <div class="coupon-btn">View Collection</div>
                </div>
                <div class="image">
                    <img src="assets/images/banner-2.png" alt="Model" />
                </div>
            </div>

            <div class="carousel-dots">
                <span class="dot active"></span>
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
                @foreach($categories as $category)
                <a href="{{ route('home', ['category_id' => $category->id]) }}" class="cat-item {{ isset($selectedCategory) && $selectedCategory->id == $category->id ? 'active' : '' }}" style="text-decoration: none; color: inherit;">
                    <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" />
                    <h3>{{ $category->name }}</h3>
                </a>
                @endforeach
            </div>
        </div>
        <div class="product">
            <div class="wrapper">
                <div class="product-list">
                    @forelse($products as $product)
                    <div class="product-item">
                        <img src="{{ asset($product->main_image) }}" alt="{{ $product->product_name }}" />
                        <p><span>₹</span>{{ $product->price ?? '--' }}</p>
                    </div>
                    @empty
                    <p style="width: 100%; text-align: center;">No products found.</p>
                    @endforelse
                </div>
                <div class="explore-btn">
                    <a href="#">Explore More</a>
                </div>
            </div>
        </div>
    </div>

    <div class="beginning-section">
        <h2>For Every Beginning</h2>
        <div class="swiper beginning-swiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img src="assets/images/carusol.png" alt="Beginning Collection" />
                </div>
                <div class="swiper-slide">
                    <img src="assets/images/carusol.png" alt="Beginning Collection" />
                </div>
                <div class="swiper-slide">
                    <img src="assets/images/carusol.png" alt="Beginning Collection" />
                </div>
                <div class="swiper-slide">
                    <img src="assets/images/carusol.png" alt="Beginning Collection" />
                </div>
                <div class="swiper-slide">
                    <img src="assets/images/carusol.png" alt="Beginning Collection" />
                </div>
                <div class="swiper-slide">
                    <img src="assets/images/carusol.png" alt="Beginning Collection" />
                </div>
                <div class="swiper-slide">
                    <img src="assets/images/carusol.png" alt="Beginning Collection" />
                </div>
                <div class="swiper-slide">
                    <img src="assets/images/carusol.png" alt="Beginning Collection" />
                </div>
                <div class="swiper-slide">
                    <img src="assets/images/carusol.png" alt="Beginning Collection" />
                </div>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>

    <div class="media-section">
        <div class="wrapper">
            <h2>Catchy title here...</h2>
            <div class="media-grid">
                <div class="media-card">
                    <img src="assets/images/media.png" alt="Video Thumbnail" />
                    <div class="play-btn"><i class="fi fi-rr-play"></i></div>
                </div>
                <div class="media-card down">
                    <img src="assets/images/media.png" alt="Video Thumbnail" />
                    <div class="play-btn"><i class="fi fi-rr-play"></i></div>
                </div>
                <div class="media-card">
                    <img src="assets/images/media.png" alt="Video Thumbnail" />
                    <div class="play-btn"><i class="fi fi-rr-play"></i></div>
                </div>
                <div class="media-card down">
                    <img src="assets/images/media.png" alt="Video Thumbnail" />
                    <div class="play-btn"><i class="fi fi-rr-play"></i></div>
                </div>
            </div>
        </div>
    </div>

    </div>

    </div>

    <div class="choose-section">
        <div class="wrapper">
            <h2>WHY CHOOSE S&Lambda;VER&Lambda;?</h2>
            <div class="choose-container">
                <div class="choose-grid">
                    <div class="choose-item item-tl">
                        <img src="assets/images/choose-img.png" alt="Why Choose 1" />
                    </div>
                    <div class="choose-item item-tr">
                        <img src="assets/images/choose-img.png" alt="Why Choose 2" />
                    </div>
                    <div class="choose-item item-bl">
                        <img src="assets/images/choose-img.png" alt="Why Choose 3" />
                    </div>
                    <div class="choose-item item-br">
                        <img src="assets/images/choose-img.png" alt="Why Choose 4" />
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
            <img src="assets/images/cartier.png" alt="Store Front" />
        </div>
        <div class="store-content">
            <h2>Store Front</h2>
            <p>Sub Text</p>
            <a href="#" class="find-store-btn">Find Store</a>
        </div>
    </div>
@endsection