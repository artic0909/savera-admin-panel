@extends('frontend.layouts.app')

@section('title', 'Home')

@section('content')
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

    <script>
        function loadCategory(event, element) {
            event.preventDefault();

            // Remove active class from all
            document.querySelectorAll('.cat-item').forEach(el => el.classList.remove('active'));
            // Add to clicked
            element.classList.add('active');

            const categoryId = element.getAttribute('data-id');
            const container = document.getElementById('product-container');
            const loader = document.getElementById('product-loader');
            const exploreBtn = document.getElementById('explore-more-btn');

            container.style.opacity = '0.5';
            loader.style.display = 'block';

            fetch(`{{ route('ajax.products') }}?category_id=${categoryId}`)
                .then(response => response.json())
                .then(data => {
                    container.innerHTML = data.html;
                    container.style.opacity = '1';
                    loader.style.display = 'none';

                    // Update Explore More Link
                    if (data.category_slug && data.category_slug !== '#') {
                        // We need the base URL structure.
                        // Simplest way: construct it or pass full url from backend.
                        // Let's use a JS variable for route pattern if this was complex, 
                        // but here we can just append if we know the root.
                        // Better: Use the category_slug from response.
                        exploreBtn.href = "{{ url('/category') }}/" + data.category_slug;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    container.style.opacity = '1';
                    loader.style.display = 'none';
                });
        }
    </script>

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
    <script>
        function playVideo(card) {
            const video = card.querySelector("video");
            card.classList.add("playing");
            video.play();
        }
    </script>
    <style>
        .media-card {
            position: relative;
            cursor: pointer;
        }

        .media-card video {
            display: none;
            width: 100%;
            height: 100%;
        }

        .play-btn {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: #fff;
            background: rgba(0, 0, 0, 0.4);
        }

        .media-card.playing img,
        .media-card.playing .play-btn {
            display: none;
        }

        .media-card.playing video {
            display: block;
        }
    </style>
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
            <h2>WHY CHOOSE SAVERA
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
@endsection
