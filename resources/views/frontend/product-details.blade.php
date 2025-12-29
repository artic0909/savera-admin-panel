@extends('frontend.layouts.app')

@section('title', 'Home')

@section('content')
    <style>
        .product-single-left {
            padding: 20px;
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        /* Main Slider */
        .product-main-slider {
            width: 100%;
            border-radius: 20px;
            overflow: hidden;
            background: #f8f8f8;
            margin-bottom: 15px;
        }

        .product-main-slider .swiper-slide {
            aspect-ratio: 1 / 1;
            /* Slightly shorter than square to reduce vertical space */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-main-slider img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 20px;
        }

        .product-main-slider video {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 20px;
        }


        /* Thumbnails Slider */
        .product-thumb-slider {
            width: 100%;
            padding: 5px 0;
        }

        .product-thumb-slider .swiper-slide {
            aspect-ratio: 1/1;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            opacity: 0.6;
            background: #f0f0f0;
        }

        .product-thumb-slider .swiper-slide-thumb-active {
            border-color: #312110;
            opacity: 1;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .product-thumb-slider img,
        .product-thumb-slider video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }


        .video-thumbnail-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, 0.4);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(2px);
        }

        /* Swiper Navigation Custom Styling */
        .product-main-slider .swiper-button-next,
        .product-main-slider .swiper-button-prev {
            background: rgba(255, 255, 255, 0.9);
            width: 25px;
            height: 25px;
            border-radius: 50%;
            color: #312110 !important;
            backdrop-filter: blur(8px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .product-main-slider .swiper-button-next:after,
        .product-main-slider .swiper-button-prev:after {
            font-size: 13px;
            font-weight: 800;
        }

        .product-main-slider .swiper-button-next:hover,
        .product-main-slider .swiper-button-prev:hover {
            background: #312110;
            color: #fff !important;
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(49, 33, 16, 0.3);
        }

        .product-main-slider .swiper-button-disabled {
            opacity: 0;
            pointer-events: none;
        }

        @media (max-width: 768px) {
            .product-single-left {
                padding: 10px;
                border-radius: 15px;
            }

            .product-thumb-slider .swiper-slide {
                width: 80px;
                height: 80px;
            }

            .product-main-slider .swiper-button-next,
            .product-main-slider .swiper-button-prev {
                width: 35px;
                height: 35px;
                background: rgba(255, 255, 255, 0.7);
            }

            .product-main-slider .swiper-button-next:after,
            .product-main-slider .swiper-button-prev:after {
                font-size: 14px;
            }
        }

        /* Product Photo Zoom effect */
        @media (hover: hover) {
            .product-main-slider .swiper-slide {
                overflow: hidden;
            }

            .product-main-slider .swiper-slide img {
                transition: transform 0.3s cubic-bezier(0.2, 0, 0.2, 1);
            }

            .product-main-slider .swiper-slide:hover img {
                transform: scale(2);
                cursor: crosshair;
            }
        }
    </style>
    {{-- <link rel="stylesheet" href="{{ asset('assets/bootstrap.min.css') }}" /> --}}

    <script>
        window.productConfigs = @json($product->metal_configurations);
        window.materials = @json($materials->keyBy('id'));
        window.sizes = @json($sizes->keyBy('id'));
        window.wishlistId = {{ $wishlistItem ? $wishlistItem->id : 'null' }};
        // Find diamond material price rate
        @php
            $dMat = $materials->first(fn($m) => strcasecmp($m->name, 'Diamond') === 0);
            $dRate = $dMat ? $dMat->price : 0;
        @endphp
        window.diamondRate = {{ $dRate }};
    </script>

    <!-- inner banner -->
    <section>
        <div class="product-single-sec">
            <div class="wrapper">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="product-single-left">
                            {{-- Main Slider --}}
                            <div class="swiper product-main-slider">
                                <div class="swiper-wrapper">
                                    {{-- Main Image --}}
                                    <div class="swiper-slide">
                                        <img src="{{ asset('storage/' . $product->main_image) }}"
                                            alt="{{ $product->product_name }}">
                                    </div>

                                    {{-- Additional Media --}}
                                    @if (is_array($product->additional_images))
                                        @foreach ($product->additional_images as $file)
                                            @php
                                                $extension = pathinfo($file, PATHINFO_EXTENSION);
                                                $isVideo = in_array(strtolower($extension), [
                                                    'mp4',
                                                    'mov',
                                                    'ogg',
                                                    'qt',
                                                    'webm',
                                                ]);
                                            @endphp
                                            <div class="swiper-slide">
                                                @if ($isVideo)
                                                    <video controls class="w-100 h-100">
                                                        <source src="{{ asset('storage/' . $file) }}"
                                                            type="video/{{ $extension }}">
                                                    </video>
                                                @else
                                                    <img src="{{ asset('storage/' . $file) }}"
                                                        alt="{{ $product->product_name }}">
                                                @endif
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                {{-- Navigation --}}
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                            </div>

                            {{-- Thumbnails Slider --}}
                            <div class="swiper product-thumb-slider">
                                <div class="swiper-wrapper">
                                    {{-- Main Thumbnail --}}
                                    <div class="swiper-slide">
                                        <img src="{{ asset('storage/' . $product->main_image) }}"
                                            alt="{{ $product->product_name }}">
                                    </div>

                                    {{-- Additional Thumbnails --}}
                                    @if (is_array($product->additional_images))
                                        @foreach ($product->additional_images as $file)
                                            @php
                                                $extension = pathinfo($file, PATHINFO_EXTENSION);
                                                $isVideo = in_array(strtolower($extension), [
                                                    'mp4',
                                                    'mov',
                                                    'ogg',
                                                    'qt',
                                                    'webm',
                                                ]);
                                            @endphp
                                            <div class="swiper-slide">
                                                @if ($isVideo)
                                                    <video muted class="w-100 h-100">
                                                        <source src="{{ asset('storage/' . $file) }}"
                                                            type="video/{{ $extension }}">
                                                    </video>
                                                    <div class="video-thumbnail-overlay">
                                                        <i class="fi fi-rr-play"></i>
                                                    </div>
                                                @else
                                                    <img src="{{ asset('storage/' . $file) }}"
                                                        alt="{{ $product->product_name }}">
                                                @endif
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-12 mt-4 mt-lg-0">
                        <div class="product-single-right">
                            <div class="title-pd">
                                <h2 class="d-flex">
                                    {{ $product->product_name }}

                                </h2>
                                <div style="position: relative;">

                                    <i class="fi {{ $wishlistItem ? 'fi-sr-heart' : 'fi-rr-heart' }}" id="wishlist-icon"
                                        style="font-size: 25px; cursor: pointer; transition: color 0.3s; color: {{ $wishlistItem ? 'red' : 'inherit' }};"
                                        onclick="toggleWishlist(this)"></i>
                                </div>
                            </div>
                            <div class="price-container" style="margin: 15px 0;">
                                <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                    <h5 id="dynamic-price"
                                        style="margin: 0; font-weight: 700; color: #312110; letter-spacing: 3px;">
                                        @php
                                            // Get the first config MRP for initial display
                                            $configs = $product->metal_configurations;
                                            $firstConfig =
                                                is_array($configs) && count($configs) > 0 ? reset($configs) : [];
                                            $initialMRP = floatval($firstConfig['mrp'] ?? 0);
                                            $displayPrice = floatval($product->display_price ?? 0);

                                            // Calculate discount percentage
                                            $discountPercentage = 0;
                                            if ($initialMRP > 0 && $displayPrice > 0) {
                                                $discountPercentage = round(
                                                    (($initialMRP - $displayPrice) / $initialMRP) * 100,
                                                );
                                            }
                                        @endphp
                                        {{ $displayPrice }}
                                    </h5>
                                    @if ($initialMRP > 0 && $initialMRP != $displayPrice)
                                        MRP :<span id="mrp"
                                            style="text-decoration: line-through; color: #999; font-size: 18px; font-weight: 500;">
                                            ₹{{ number_format($initialMRP, 2) }}
                                        </span>
                                    @endif
                                    @if ($discountPercentage > 0)
                                        <span id="discount-badge"
                                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 6px 12px; border-radius: 20px; font-size: 14px; font-weight: 600; display: inline-block;">
                                            {{ $discountPercentage }}% OFF
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <p class="p1">
                                Price inclusive of taxes. See the full <a href="#price-breakup">Price Breakup</a>
                            </p>
                            <p class="p2">
                                {{-- <a href="#">Special Offer for you</a> --}}
                            </p>
                            <div class="apply-coupon-div" id="coupon-rotator"
                                style="display: none; background: #fff5f5; border: 1px dashed #ff9999; padding: 15px; border-radius: 8px; margin-top: 15px; position: relative; transition: all 0.3s ease;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <h6 id="coupon-offer-text"
                                            style="color: #d32f2f; font-weight: 700; margin: 0; font-size: 16px;">
                                            Loading Offers...
                                        </h6>
                                        <p id="coupon-code-text" style="margin: 5px 0 0; font-size: 14px; color: #555;">
                                            Finding best coupons...
                                        </p>
                                    </div>
                                    <button onclick="copyCouponCode()" id="copy-btn"
                                        style="background: white; border: 1px solid #d32f2f; color: #d32f2f; padding: 5px 15px; border-radius: 20px; cursor: pointer; font-size: 12px; font-weight: bold; transition: all 0.2s;">
                                        COPY
                                    </button>
                                </div>
                                <input type="hidden" id="current-coupon-code">
                            </div>

                            @push('scripts')
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const coupons = @json($coupons);
                                        const rotatorDiv = document.getElementById('coupon-rotator');
                                        const offerText = document.getElementById('coupon-offer-text');
                                        const codeText = document.getElementById('coupon-code-text');
                                        const hiddenInput = document.getElementById('current-coupon-code');
                                        const priceElement = document.getElementById('dynamic-price');

                                        if (coupons.length > 0) {
                                            rotatorDiv.style.display = 'block';
                                            let currentIndex = 0;

                                            function getProductPrice() {
                                                if (!priceElement) return 0;
                                                // Robust price parsing: remove currency symbols, keep numbers and dots
                                                let priceText = priceElement.innerText || priceElement.textContent;
                                                let cleaned = priceText.replace(/[^0-9.]/g, '');
                                                let price = parseFloat(cleaned);
                                                return isNaN(price) ? 0 : price;
                                            }

                                            function updateCoupon() {
                                                const coupon = coupons[currentIndex];
                                                let currentPrice = getProductPrice();

                                                if (currentPrice === 0) {
                                                    currentPrice = getProductPrice();
                                                }

                                                if (currentPrice === 0) {
                                                    offerText.textContent = `Save with ${coupon.code}`;
                                                } else {
                                                    let discount = 0;
                                                    if (coupon.type === 'fixed') {
                                                        discount = parseFloat(coupon.value);
                                                    } else {
                                                        discount = (currentPrice * parseFloat(coupon.value)) / 100;
                                                        if (coupon.max_discount_amount) {
                                                            discount = Math.min(discount, parseFloat(coupon.max_discount_amount));
                                                        }
                                                    }

                                                    let finalPrice = Math.max(0, currentPrice - discount);
                                                    offerText.textContent = `GET IT FOR ₹${Math.round(finalPrice).toLocaleString('en-IN')}`;
                                                }

                                                codeText.innerHTML = `Use <strong style="color: #333;">${coupon.code}</strong>`;
                                                hiddenInput.value = coupon.code;

                                                currentIndex = (currentIndex + 1) % coupons.length;
                                            }

                                            setTimeout(updateCoupon, 100);
                                            setInterval(updateCoupon, 5000);
                                            window.updateCouponDisplay = updateCoupon;
                                        }
                                    });

                                    function copyCouponCode() {
                                        const code = document.getElementById('current-coupon-code').value;
                                        // Need to find button relative to the rotator or by ID if we add ID later (next step)
                                        // For now, let's look for the button inside the rotator div
                                        const btn = document.querySelector('#coupon-rotator button');

                                        if (!code) return;

                                        if (navigator.clipboard && window.isSecureContext) {
                                            navigator.clipboard.writeText(code).then(showCopied).catch(fallbackCopy);
                                        } else {
                                            fallbackCopy();
                                        }

                                        function fallbackCopy() {
                                            const type = document.createElement("input");
                                            type.value = code;
                                            document.body.appendChild(type);
                                            type.select();
                                            try {
                                                document.execCommand("copy");
                                                showCopied();
                                            } catch (err) {
                                                console.error('Fallback copy failed', err);
                                            }
                                            document.body.removeChild(type);
                                        }

                                        function showCopied() {
                                            const originalText = btn.textContent;
                                            btn.textContent = 'COPIED!';

                                            setTimeout(() => {
                                                btn.textContent = originalText;
                                            }, 2000);
                                        }
                                    }
                                </script>
                            @endpush
                            <div class="color">
                                <p>
                                    COLOR
                                </p>
                                <div class="color-option" id="color-options-container">
                                    @if (is_array($product->colors) && count($product->colors) > 0)
                                        @foreach ($product->colors as $index => $colorId)
                                            @php
                                                $colorObj = $colors->find($colorId);
                                                $colorName = $colorObj ? $colorObj->color_name : 'Unknown';
                                                $bgStyle = $colorObj ? $colorObj->color_code ?? '#000000' : '#000000';
                                            @endphp

                                            <button class="color-btn {{ $index === 0 ? 'active' : '' }}"
                                                style="background-color: {{ $bgStyle }}; position: relative;"
                                                data-color-id="{{ $colorId }}" data-color-name="{{ $colorName }}"
                                                onclick="selectColor(this)" title="{{ $colorName }}">
                                                <span class="checkmark"
                                                    style="display: {{ $index === 0 ? 'block' : 'none' }}; color: {{ $bgStyle === '#ffffff' || $bgStyle === '#fff' ? '#000' : '#fff' }};">✓</span>
                                            </button>
                                        @endforeach
                                    @else
                                        <span>No colors available</span>
                                    @endif
                                </div>
                            </div>
                            <div class="metal">
                                <p>
                                    Metal
                                </p>
                                <div class="metal-option" id="metal-options-container">
                                    <!-- Populated by JS or Initial State -->
                                    @php
                                        // Initial render matches JS logic: Group configs by Material
                                        $uniqueMaterials = [];
                                        if (is_array($product->metal_configurations)) {
                                            foreach ($product->metal_configurations as $key => $conf) {
                                                $mId = $conf['material_id'] ?? 0;
                                                if (!isset($uniqueMaterials[$mId])) {
                                                    $mObj = $materials->find($mId);
                                                    if ($mObj) {
                                                        $uniqueMaterials[$mId] = $mObj->name;
                                                    }
                                                }
                                            }
                                        }
                                    @endphp
                                    @foreach ($uniqueMaterials as $mId => $mName)
                                        <button type="button" class="metal-btn" data-material-id="{{ $mId }}"
                                            onclick="selectMaterial('{{ $mId }}')">{{ $mName }}</button>
                                    @endforeach
                                </div>
                            </div>
                            <div class="ring-size">
                                <p>
                                    Size
                                </p>
                                <div class="ring-size-option">
                                    <select id="size-selector" onchange="selectSize(this.value)">
                                        <option value="">Select Size</option>
                                        <!-- Populated via JS based on selected metal -->
                                        @foreach ($sizes as $size)
                                            <option value="{{ $size->id }}">{{ $size->size_name ?? $size->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="btns">
                                @if ($product->stock_quantity > 0)
                                    @if (Auth::guard('customer')->check())
                                        <button type="button" onclick="addToCart()" style="cursor: pointer;">ADD TO
                                            CART</button>

                                        <button type="button" onclick="buyNow()"
                                            style="cursor: pointer; background: white; color: #000; border: 2px solid #000;">BUY
                                            NOW</button>
                                    @else
                                        <a href="{{ route('login') }}"
                                            style="display: block; text-align: center; text-decoration: none; color: inherit;">
                                            <button type="button">LOGIN TO ADD TO CART</button>
                                        </a>
                                    @endif
                                @else
                                    @if ($alreadyRequested)
                                        <button type="button" disabled
                                            style="cursor: default; background: #607d8b; color: white; border: none; width: 100%; border-radius: 5px; font-weight: bold; padding: 15px;">
                                            <i class="fi fi-rr-check" style="margin-right: 8px;"></i> ALREADY REQUESTED
                                        </button>
                                    @else
                                        <button type="button" onclick="showNotifyModal()" id="notify-me-btn"
                                            style="cursor: pointer; background: #ff9800; color: white; border: none; width: 100%; border-radius: 5px; font-weight: bold; padding: 15px;">
                                            <i class="fi fi-rr-bell" style="margin-right: 8px;"></i> NOTIFY ME WHEN
                                            AVAILABLE
                                        </button>
                                    @endif
                                @endif
                            </div>
                            {{-- <p class="p2">
                                <a href="#" class="dc">Delivery & Cancellation</a>
                            </p> --}}
                            <p class="p2">
                                <a href="#" class="dc">Estimated delivery by
                                    {{ now()->addDays((int) $product->delivery_time)->format('d F') }}</a>
                            </p>
                            <div class="pincode">
                                <input type="text" id="pincode-input" placeholder="Enter Pincode" maxlength="10"
                                    style="padding: 10px; border: 1px solid #ddd; border-radius: 5px; width: calc(100% - 80px);">
                                <button type="button" onclick="checkPincode()"
                                    style="padding: 10px 15px; cursor: pointer; background: #000; color: white; border: none; border-radius: 5px;">Check</button>
                            </div>
                            <div id="pincode-message" style="margin-top: 10px; font-weight: bold;"></div>

                            <p class="cat">Category: <span>{{ $product->category->name }}</span></p>

                            {{-- Share Buttons --}}
                            <div class="share-product mt-4">
                                <p style="font-weight: 600; margin-bottom: 10px; font-size: 14px; color: #555;">SHARE THIS
                                    PRODUCT</p>
                                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                    <button type="button" onclick="copyProductLink()" id="copy-link-btn"
                                        style="background: #f5f5f5; border: 1px solid #ddd; padding: 8px 15px; border-radius: 50px; cursor: pointer; display: flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 600; color: #333; transition: all 0.3s ease;">
                                        <i class="fi fi-rr-copy"></i> <span id="copy-text">Copy Link</span>
                                    </button>

                                    <a href="https://wa.me/?text={{ urlencode($product->product_name . ' - ' . route('product.show', $product->slug)) }}"
                                        target="_blank"
                                        style="background: #25D366; color: white; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: transform 0.2s;"
                                        onmouseover="this.style.transform='scale(1.1)'"
                                        onmouseout="this.style.transform='scale(1)'">
                                        <i class="fi fi-brands-whatsapp" style="font-size: 18px; margin-top: 4px;"></i>
                                    </a>

                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('product.show', $product->slug)) }}"
                                        target="_blank"
                                        style="background: #1877F2; color: white; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: transform 0.2s;"
                                        onmouseover="this.style.transform='scale(1.1)'"
                                        onmouseout="this.style.transform='scale(1)'">
                                        <i class="fi fi-brands-facebook" style="font-size: 18px; margin-top: 4px;"></i>
                                    </a>

                                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($product->product_name) }}&url={{ urlencode(route('product.show', $product->slug)) }}"
                                        target="_blank"
                                        style="background: #000; color: white; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: transform 0.2s;"
                                        onmouseover="this.style.transform='scale(1.1)'"
                                        onmouseout="this.style.transform='scale(1)'">
                                        <i class="fi fi-brands-twitter" style="font-size: 18px; margin-top: 4px;"></i>
                                    </a>
                                </div>
                            </div>

                            <script>
                                function copyProductLink() {
                                    const url = window.location.href;
                                    const btn = document.getElementById('copy-link-btn');
                                    const textSpan = document.getElementById('copy-text');
                                    const icon = btn.querySelector('i');

                                    if (navigator.clipboard) {
                                        navigator.clipboard.writeText(url).then(() => {
                                            const originalHTML = btn.innerHTML;
                                            btn.innerHTML =
                                                '<i class="fi fi-rr-check" style="color: #28a745;"></i> <span style="color: #28a745;">Copied!</span>';
                                            btn.style.borderColor = '#28a745';

                                            setTimeout(() => {
                                                btn.innerHTML = '<i class="fi fi-rr-copy"></i> <span>Copy Link</span>';
                                                btn.style.borderColor = '#ddd';
                                            }, 2000);
                                        }).catch(err => {
                                            console.error('Failed to copy', err);
                                            fallbackCopyText(url);
                                        });
                                    } else {
                                        fallbackCopyText(url);
                                    }
                                }

                                function fallbackCopyText(text) {
                                    const textArea = document.createElement("textarea");
                                    textArea.value = text;
                                    // Prevent scrolling to bottom
                                    textArea.style.position = "fixed";
                                    textArea.style.left = "-9999px";
                                    textArea.style.top = "0";
                                    document.body.appendChild(textArea);
                                    textArea.focus();
                                    textArea.select();
                                    try {
                                        document.execCommand('copy');
                                        const btn = document.getElementById('copy-link-btn');
                                        btn.innerHTML =
                                            '<i class="fi fi-rr-check" style="color: #28a745;"></i> <span style="color: #28a745;">Copied!</span>';
                                        setTimeout(() => {
                                            btn.innerHTML = '<i class="fi fi-rr-copy"></i> <span>Copy Link</span>';
                                        }, 2000);
                                    } catch (err) {
                                        console.error('Fallback: Oops, unable to copy', err);
                                    }
                                    document.body.removeChild(textArea);
                                }
                            </script>
                        </div>
                    </div>
                </div>
                <div class="additional-info">
                    <h2>Additional Information</h2>
                    <hr class="mt-0">
                    <br>
                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-12">
                            <div class="product-details">
                                <h6 class="heading-1">
                                    Product Details
                                </h6>
                                <div class="div-1">
                                    @php
                                        // Use the last config to match the price display logic, or maybe show a range?
                                        // For simplicity, let's pick the last one as "Default".
$configs = $product->metal_configurations;
$defaultConfig = is_array($configs) ? end($configs) : [];
$diamondInfo = $product->diamond_gemstone_info; // This might be top-level or inside config?

// Migration says table->json('diamond_gemstone_info')->nullable();
// But logic in Model uses $config['diamond_info'].
// VALIDATION: Let's check if 'diamond_gemstone_info' column is used or 'metal_configurations' nested info.
                                        // The model getDisplayPriceAttribute uses: $config['diamond_info']
                                        // But migration has a separate column. I will assume the separate column might be a fallback or the main source if config is empty.
                                        // Let's check $config['diamond_info'] first as it is tied to the specific metal config.

$shownDiamondInfo =
    $defaultConfig['diamond_info'] ?? ($product->diamond_gemstone_info ?? []);
$netWeight = $defaultConfig['net_weight_gold'] ?? '--';
$purity = $defaultConfig['purity'] ?? '--';
$grossWeight = $defaultConfig['gross_weight_product'] ?? '--';

$matId = $defaultConfig['material_id'] ?? null;
$matName = $materials->where('id', $matId)->first()->name ?? 'Unknown';

// Calculate diamond totals early for display
$diamondTotalWt = 0;
$totalDiamondCount = 0;
$hasDiamonds = false;
if (!empty($shownDiamondInfo) && is_array($shownDiamondInfo)) {
    $hasDiamonds = true;
    foreach ($shownDiamondInfo as $dInfo) {
        $diamondTotalWt += floatval($dInfo['total_weight'] ?? 0);
        $totalDiamondCount += intval($dInfo['number_of_diamonds'] ?? 0);
                                            }
                                        }
                                    @endphp

                                    <div class="weight">
                                        <h6>
                                            Weight
                                        </h6>
                                        <p>
                                            Gross Weight(Product): <span id="gross-weight"></span>
                                            g
                                            <br>
                                            Net Weight({{ $matName }}): <span id="net-weight"></span> g
                                        </p>
                                    </div>
                                    <div class="weight">
                                        <h6>
                                            Purity
                                        </h6>
                                        <p id="purity-display">
                                            {{ $purity }}
                                        </p>
                                    </div>
                                </div>
                                <div class="div-1 d-block">
                                    <div id="diamond-details-wrapper"
                                        style="display: {{ !empty($shownDiamondInfo) && is_array($shownDiamondInfo) ? 'block' : 'none' }};">
                                        <div class="weight w-100">
                                            <h6>
                                                Diamond
                                            </h6>
                                            <p class="d-flex justify-content-between">
                                                <span>
                                                    <b>Total Weight <span
                                                            id="diamond-total-wt">{{ $diamondTotalWt }}</span> ct</b>
                                                </span>
                                                <span>
                                                    <b>Diamonds No. of <span
                                                            id="diamond-total-count">{{ $totalDiamondCount }}</span></b>
                                                </span>
                                            </p>
                                            <table id="diamond-details-table">
                                                <thead>
                                                    <tr>
                                                        <td>Size</td>
                                                        <td>Color</td>
                                                        <td>Clarity</td>
                                                        <td>Cut</td>
                                                        <td>No. of diamond</td>
                                                        <td>Total Weight</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($shownDiamondInfo as $info)
                                                        <tr>
                                                            <td>{{ $info['size'] ?? 'Diamond' }}</td>
                                                            <td>{{ $info['color'] ?? '--' }}</td>
                                                            <td>{{ $info['clarity'] ?? '--' }}</td>
                                                            <td>{{ $info['shape'] ?? '--' }}</td>
                                                            <td>{{ $info['number_of_diamonds'] ?? '--' }}</td>
                                                            <td>{{ $info['total_weight'] ?? '--' }} ct</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>


                                <!-- Price Breakup -->
                                <h6 class="heading-1" id="price-breakup">
                                    Price Breakup
                                </h6>
                                <div class="div-1 d-block">
                                    @php
                                        // 1. Fetch Material Price
                                        $materialPrice = 0;
                                        $materialName = 'Unknown Material';
                                        if (isset($defaultConfig['material_id'])) {
                                            $mat = $materials->firstWhere('id', $defaultConfig['material_id']);
                                            if ($mat) {
                                                $materialPrice = $mat->price;
                                                $materialName = $mat->name;
                                            }
                                        }

                                        // 2. Fetch Diamond Price
                                        // Case-insensitive check for 'Diamond'
                                        $diamondMaterial = $materials->first(function ($item) {
                                            return strcasecmp($item->name, 'Diamond') === 0;
                                        });
                                        $diamondPricePerCarat = $diamondMaterial ? $diamondMaterial->price : 0;

                                        // Calculations
                                        $netWt = floatval($defaultConfig['net_weight_gold'] ?? 0);
                                        $materialCost = $netWt * $materialPrice;

                                        // Diamond cost using previously calculated total weight
                                        $diamondCost = floatval($defaultConfig['total_diamond_price'] ?? 0);

                                        $makingCharge = floatval($defaultConfig['making_charge'] ?? 0);

                                        $basePrice = $materialCost + $diamondCost + $makingCharge;
                                        $gstPercentage = floatval($defaultConfig['gst_percentage'] ?? 0);
                                        $gstAmount = ($basePrice * $gstPercentage) / 100;
                                        $finalPrice = $basePrice + $gstAmount;
                                    @endphp

                                    <div class="weight p-0 w-100" style="border: 0;">
                                        <table id="price-breakup-table">
                                            <tr>
                                                <td>
                                                    {{ $matName }} <span style="font-size: 10px;"
                                                        id="gold-breakdown-text">({{ number_format($netWt, 3) }}g x
                                                        {{ $materialPrice }})</span>
                                                </td>
                                                <td id="gold-cost-display">
                                                    ₹ {{ number_format($materialCost, 2) }}
                                                </td>
                                            </tr>
                                            <tr id="diamond-row" style="display: {{ $hasDiamonds ? '' : 'none' }};">
                                                <td>
                                                    Diamond Price<span style="font-size: 10px; visibility: hidden;"
                                                        id="diamond-breakdown-text">({{ number_format($diamondTotalWt, 3) }}g
                                                        x {{ $diamondPricePerCarat }})</span>
                                                </td>
                                                <td id="diamond-cost-display">
                                                    ₹ {{ number_format($diamondCost, 2) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Making Charge
                                                </td>
                                                <td id="making-charge-display">
                                                    ₹ {{ number_format($makingCharge, 2) }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    GST (<span id="gst-percent-display">{{ $gstPercentage }}</span>%)
                                                </td>
                                                <td id="gst-amount-display">
                                                    ₹ {{ number_format($gstAmount, 2) }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <b>Total</b>
                                                </td>
                                                <td>
                                                    <b id="final-total-display">₹
                                                        {{ number_format($finalPrice, 2) }}</b>
                                                </td>
                                            </tr>
                                        </table>
                                        <p class="mt-3">
                                            <b>
                                                This is an estimated price, actual price may differ as per actual weights.
                                            </b>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-12">
                            <div class="our-promise">
                                <h6 class="heading-1">
                                    Our Promise
                                </h6>
                                <ul>
                                    <li>
                                        <img src="{{ asset('assets/images/i1.png') }}" alt="...">
                                        80% Buyback
                                    </li>
                                    <li>
                                        <img src="{{ asset('assets/images/i2.png') }}" alt="...">
                                        100% Exchange
                                    </li>
                                    <li>
                                        <img src="{{ asset('assets/images/i3.png') }}" alt="...">
                                        Easy 7 days return
                                    </li>
                                    <li>
                                        <img src="{{ asset('assets/images/i4.png') }}" alt="...">
                                        Free Shipping & insurance
                                    </li>
                                    <li>
                                        <img src="{{ asset('assets/images/i5.png') }}" alt="...">
                                        Hallmarked {{ $matName }}
                                    </li>
                                    <li>
                                        <img src="{{ asset('assets/images/i6.png') }}" alt="...">
                                        Certified Jewellery
                                    </li>
                                </ul>
                                <div class="certificate text-center">
                                    <h6>
                                        certificate of authenticity
                                    </h6>
                                    <p>Each jewellery piece is independently certified by internationally trusted
                                        laboratories like IGI and SGL.<br>
                                        Our certification guarantees the authenticity, quality, and transparency of every
                                        product—because fine jewellery should always come with complete trust.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="similar-product">
            <div class="wrapper">
                <h2>
                    Similar Product
                </h2>
                <div class="similar-product-grid">

                    @if ($similarProducts->count() > 0)
                        <div class="product-list" id="product-container">
                            @include('frontend.partials.product_loop', [
                                'products' => $similarProducts,
                            ])
                        </div>
                        <!-- Loader (kept once, outside loop) -->
                    @else
                        <div class="col-12 text-center">
                            <p>No similar products found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <!-- /inner banner -->



    <script src="{{ asset('assets/script.js') }}"></script>
    <script src="{{ asset('assets/jquery-1.12.0.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Swiper for Thumbnails
            var thumbSwiper = new Swiper(".product-thumb-slider", {
                spaceBetween: 10,
                slidesPerView: 4,
                freeMode: true,
                watchSlidesProgress: true,
                breakpoints: {
                    320: {
                        slidesPerView: 3,
                        spaceBetween: 8
                    },
                    480: {
                        slidesPerView: 4,
                        spaceBetween: 10
                    }
                }
            });

            // Initialize Swiper for Main slider
            var mainSwiper = new Swiper(".product-main-slider", {
                spaceBetween: 10,
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                thumbs: {
                    swiper: thumbSwiper,
                },
                on: {
                    slideChangeTransitionStart: function() {
                        // Pause all videos when slide changes
                        document.querySelectorAll('.product-main-slider video').forEach(v => {
                            v.pause();
                            v.currentTime = 0;
                        });
                    }
                }
            });

            // Product Image Zoom Effect
            const mainSliderSlides = document.querySelectorAll('.product-main-slider .swiper-slide');
            mainSliderSlides.forEach(slide => {
                const img = slide.querySelector('img');
                if (!img) return;

                slide.addEventListener('mousemove', function(e) {
                    const rect = slide.getBoundingClientRect();
                    const x = ((e.clientX - rect.left) / rect.width) * 100;
                    const y = ((e.clientY - rect.top) / rect.height) * 100;
                    img.style.transformOrigin = `${x}% ${y}%`;
                });

                slide.addEventListener('mouseleave', function() {
                    img.style.transformOrigin = 'center center';
                });
            });
        });
    </script>

    <!-- Notify Me Modal -->
    <div id="notifyModal" class="notify-modal"
        style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); align-items: center; justify-content: center;">
        <div class="notify-modal-content"
            style="background: white; padding: 30px; border-radius: 20px; width: 90%; max-width: 450px; position: relative; box-shadow: 0 15px 35px rgba(0,0,0,0.2);">
            <span onclick="closeNotifyModal()"
                style="position: absolute; right: 20px; top: 15px; font-size: 24px; cursor: pointer; color: #999;">&times;</span>
            <div class="text-center mb-4">
                <div
                    style="background: #fff5e6; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                    <i class="fi fi-rr-bell" style="font-size: 30px; color: #ff9800;"></i>
                </div>
                <h4 style="font-weight: 700; margin-bottom: 10px; text-align: center;">Notify Me</h4>
                <p class="text-muted" style="font-size: 14px; text-align: center;">We'll message you on WhatsApp as soon
                    as <strong>{{ $product->product_name }}</strong> is back in stock.</p>
            </div>
            <form id="notifyForm">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="mb-4">
                    <label class="form-label"
                        style="font-weight: 600; font-size: 12px; text-transform: uppercase; color: #666; display: block; margin-bottom: 8px;">WhatsApp
                        Number</label>
                    <div style="position: relative;">
                        <input type="text" name="phone_number" class="form-control"
                            placeholder="Enter your WhatsApp number" required
                            style="width: 100%; height: 50px; border-radius: 12px; border: 1.5px solid #eee; padding: 0 15px;">
                    </div>
                </div>
                <button type="submit" id="notifySubmitBtn"
                    style="width: 100%; height: 50px; background: #312110; color: white; border: none; border-radius: 12px; font-weight: 600; transition: all 0.3s; cursor: pointer;">
                    SEND NOTIFICATION REQUEST
                </button>
            </form>
            <div id="notifySuccess" style="display: none; text-align: center; margin-top: 20px;">
                <div class="alert alert-success"
                    style="background: #e8f5e9; color: #2e7d32; border: none; border-radius: 12px; padding: 15px;">Request
                    saved successfully! We'll notify you soon.</div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Current State
        let currentConfigs = window.productConfigs || {};
        let materials = window.materials || {};
        let sizes = window.sizes || {};
        let diamondRate = window.diamondRate || 0;

        // Helper to format currency
        function formatCurrency(amount) {
            return '₹ ' + parseFloat(amount).toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        // Helper to format number
        function formatNumber(num, decimals = 2) {
            return parseFloat(num).toFixed(decimals);
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Find default config (last one as per PHP logic)
            let keys = Object.keys(currentConfigs);
            if (keys.length > 0) {
                let lastKey = keys[keys.length - 1];
                // Highlight initial material button
                let matId = currentConfigs[lastKey].material_id;
                selectMaterial(matId, currentConfigs[lastKey].size_id);
            }
        });

        // Select Material
        window.selectMaterial = function(materialId, preferredSizeId = null) {
            // Highlight Button
            document.querySelectorAll('.metal-btn').forEach(btn => {
                btn.classList.remove('active'); // Add active class CSS if needed
                if (btn.dataset.materialId == materialId) btn.classList.add('active');
            });

            // Filter Configs for this Material
            let availableSizes = [];
            let firstConfig = null;

            for (let key in currentConfigs) {
                let conf = currentConfigs[key];
                if (conf.material_id == materialId) {
                    if (!firstConfig) firstConfig = conf;
                    if (!availableSizes.includes(conf.size_id)) {
                        availableSizes.push(conf.size_id);
                    }
                }
            }

            // Update Size Dropdown
            let sizeSelect = document.getElementById('size-selector');
            sizeSelect.innerHTML = '<option value="">Select Size</option>';

            availableSizes.forEach(sId => {
                let sName = sizes[sId] ? (sizes[sId].size_name || sizes[sId].name) : 'Unknown';
                let opt = document.createElement('option');
                opt.value = sId;
                opt.textContent = sName;
                sizeSelect.appendChild(opt);
            });

            // Select Default Size
            if (availableSizes.length > 0) {
                let targetSize = preferredSizeId && availableSizes.includes(preferredSizeId) ? preferredSizeId :
                    availableSizes[0];
                sizeSelect.value = targetSize;
                selectSize(targetSize);
            } else if (firstConfig) {
                // Fallback if no sizes defined ???
                updateProductDetails(firstConfig);
            }
        };

        // Select Size
        window.selectSize = function(sizeId) {
            if (!sizeId) return;

            // Find config matching selected material (active btn) and size
            let activeBtn = document.querySelector('.metal-btn.active');
            if (!activeBtn) return;

            let matId = activeBtn.dataset.materialId;

            let targetConfig = null;
            for (let key in currentConfigs) {
                let conf = currentConfigs[key];
                if (conf.material_id == matId && conf.size_id == sizeId) {
                    targetConfig = conf;
                    break;
                }
            }

            if (targetConfig) {
                updateProductDetails(targetConfig);
            }
        };

        // Update Details
        function updateProductDetails(config) {
            // Material Info
            let mat = materials[config.material_id];
            let matPrice = mat ? parseFloat(mat.price) : 0;
            let matName = mat ? mat.name : 'Unknown';

            // Weights
            let netWt = parseFloat(config.net_weight_gold || 0);
            let grossWt = parseFloat(config.gross_weight_product || 0);

            document.getElementById('net-weight').textContent = netWt;
            document.getElementById('purity-display').textContent = config.purity || '--';

            // Diamond Info
            let diamondTotalWt = 0;
            let totalDiamondCount = 0;
            let diamondInfo = config.diamond_info || {};

            // Rebuild Diamond Table
            let diamondWrapper = document.getElementById('diamond-details-wrapper');
            let diamondTableBody = document.querySelector('#diamond-details-table tbody');
            diamondTableBody.innerHTML = ''; // Clear

            let hasDiamonds = false;

            // Check if diamondInfo is array or object and iterate
            let dKeys = Object.keys(diamondInfo);
            if (dKeys.length > 0) {
                hasDiamonds = true;
                dKeys.forEach(k => {
                    let info = diamondInfo[k];
                    let wt = parseFloat(info.total_weight || 0);
                    let count = parseInt(info.number_of_diamonds || 0);

                    diamondTotalWt += wt;
                    totalDiamondCount += count;

                    let row = `<tr>
                    <td>${info.size || 'Diamond'}</td>
                    <td>${info.color || '--'}</td>
                    <td>${info.clarity || '--'}</td>
                    <td>${info.shape || '--'}</td>
                    <td>${info.number_of_diamonds || '--'}</td>
                    <td>${info.total_weight || '--'} ct</td>
                </tr>`;
                    diamondTableBody.innerHTML += row;
                });
            }

            document.getElementById('diamond-total-wt').textContent = formatNumber(diamondTotalWt, 3);
            document.getElementById('diamond-total-count').textContent = totalDiamondCount;
            diamondWrapper.style.display = hasDiamonds ? 'block' : 'none';

            // Calculate and Update Gross Weight (Net + Diamond)
            let calculatedGrossWt = netWt + diamondTotalWt;
            document.getElementById('gross-weight').textContent = formatNumber(calculatedGrossWt, 3);

            // Price Calculations
            let materialCost = netWt * matPrice;
            let diamondCost = parseFloat(config.total_diamond_price || 0);
            let makingCharge = parseFloat(config.making_charge || 0);
            let basePrice = materialCost + diamondCost + makingCharge;
            let gstPercent = parseFloat(config.gst_percentage || 0);
            let gstAmount = (basePrice * gstPercent) / 100;
            let finalPrice = basePrice + gstAmount;

            // Update Price Breakup
            document.getElementById('gold-breakdown-text').textContent = `(${formatNumber(netWt, 3)}g x ${matPrice})`;
            document.getElementById('gold-cost-display').textContent = formatCurrency(materialCost);

            let diamondRow = document.getElementById('diamond-row');
            diamondRow.style.display = hasDiamonds ? '' : 'none';
            if (hasDiamonds) {
                document.getElementById('diamond-breakdown-text').textContent =
                    `(${formatNumber(diamondTotalWt, 3)}g x ${diamondRate})`;
                document.getElementById('diamond-cost-display').textContent = formatCurrency(diamondCost);
            }

            document.getElementById('making-charge-display').textContent = formatCurrency(makingCharge);
            document.getElementById('gst-percent-display').textContent = gstPercent;
            document.getElementById('gst-amount-display').textContent = formatCurrency(gstAmount);
            document.getElementById('final-total-display').textContent = formatCurrency(finalPrice);

            // Main Price Display with MRP and Discount
            document.getElementById('dynamic-price').textContent = formatCurrency(finalPrice);

            // Update MRP and Discount Badge
            let mrpValue = parseFloat(config.mrp || 0);
            let mrpElement = document.getElementById('mrp');
            let discountBadge = document.getElementById('discount-badge');

            if (mrpValue > 0 && mrpValue !== finalPrice) {
                if (mrpElement) {
                    mrpElement.textContent = formatCurrency(mrpValue);
                    mrpElement.style.display = 'inline-block';
                }

                // Calculate discount percentage
                let discountPercent = Math.round(((mrpValue - finalPrice) / mrpValue) * 100);

                if (discountPercent > 0 && discountBadge) {
                    discountBadge.textContent = discountPercent + '% OFF';
                    discountBadge.style.display = 'inline-block';
                } else if (discountBadge) {
                    discountBadge.style.display = 'none';
                }
            } else {
                if (mrpElement) mrpElement.style.display = 'none';
                if (discountBadge) discountBadge.style.display = 'none';
            }
        }

        // Add to Cart function
        window.addToCart = function() {
            let activeBtn = document.querySelector('.metal-btn.active');
            if (!activeBtn) {
                alert('Please select a metal type');
                return;
            }

            let materialId = activeBtn.dataset.materialId;
            let sizeSelector = document.getElementById('size-selector');
            let sizeId = sizeSelector.value;

            if (!sizeId) {
                alert('Please select a size');
                return;
            }

            let materialName = activeBtn.textContent;
            let sizeName = sizeSelector.options[sizeSelector.selectedIndex].text;
            let priceText = document.getElementById('dynamic-price').textContent;
            let price = parseFloat(priceText.replace('₹', '').replace(/,/g, '').trim());

            fetch('/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        product_id: {{ $product->id }},
                        quantity: 1,
                        metal_configuration: {
                            material_id: materialId,
                            material_name: materialName,
                            size_id: sizeId,
                            size_name: sizeName,
                            color_id: window.selectedColorId || null,
                            color_name: window.selectedColorName || null
                        },
                        price: price
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Product added to cart!');
                        if (document.getElementById('cart-count')) {
                            document.getElementById('cart-count').textContent = data.cart_count;
                        }
                    } else {
                        alert('Failed to add to cart.');
                    }
                });
        };

        // Add to Wishlist function
        window.addToWishlist = function() {
            fetch('/wishlist/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        product_id: {{ $product->id }}
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Product added to wishlist!');
                        if (document.getElementById('wishlist-count')) {
                            document.getElementById('wishlist-count').textContent = data.wishlist_count;
                        }
                    } else {
                        alert(data.message || 'Failed to add to wishlist.');
                    }
                });
        };

        // Toggle Wishlist (Silent + Icon Change)
        // Toggle Wishlist (Add / Remove)
        window.toggleWishlist = function(icon) {
            let csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            if (window.wishlistId) {
                // REMOVE from Wishlist
                fetch(`/wishlist/remove/${window.wishlistId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Reset Icon to Empty
                            icon.classList.remove('fi-sr-heart');
                            icon.classList.add('fi-rr-heart');
                            icon.style.color = 'inherit';
                            window.wishlistId = null;

                            // Update count if exists
                            // Note: Delete response might not return count, but we can assume -1 or fetch fresh count.
                            // Ideally the backend should return the new count or we fetch it.
                            // For now let's try to update if possible.
                            // Update count if exists
                            if (document.getElementById('wishlist-count') && data.wishlist_count !== undefined) {
                                document.getElementById('wishlist-count').textContent = data.wishlist_count;
                            }
                        } else {
                            alert(data.message || 'Failed to remove from wishlist.');
                        }
                    })
                    .catch(err => console.error('Wishlist Remove Error:', err));

            } else {
                // ADD to Wishlist
                fetch('/wishlist/add', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            product_id: {{ $product->id }}
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Change Icon to Filled
                            icon.classList.remove('fi-rr-heart');
                            icon.classList.add('fi-sr-heart');
                            icon.style.color = 'red';
                            window.wishlistId = data.wishlist_id;

                            if (document.getElementById('wishlist-count')) {
                                document.getElementById('wishlist-count').textContent = data.wishlist_count;
                            }
                        } else if (data.message && data.message.toLowerCase().includes('already')) {
                            // Just update visuals if backend says it's there
                            icon.classList.remove('fi-rr-heart');
                            icon.classList.add('fi-sr-heart');
                            icon.style.color = 'red';
                            alert('Product is already in your wishlist.');
                        } else {
                            // If user not logged in or other error
                            if (data.message === 'Unauthenticated.') {
                                window.location.href = "{{ route('login') }}";
                            } else {
                                alert(data.message || 'Failed to add to wishlist.');
                            }
                        }
                    })
                    .catch(err => {
                        // Handle 401 standard from Laravel
                        if (err.status === 401) {
                            window.location.href = "{{ route('login') }}";
                        }
                        console.error('Wishlist Add Error:', err);
                    });
            }
        };

        // Buy Now Function (Direct Checkout)
        window.buyNow = function() {
            let activeBtn = document.querySelector('.metal-btn.active');
            if (!activeBtn) {
                alert('Please select a metal type');
                return;
            }

            let materialId = activeBtn.dataset.materialId;
            let sizeSelector = document.getElementById('size-selector');
            let sizeId = sizeSelector.value;

            if (!sizeId) {
                alert('Please select a size');
                return;
            }

            let materialName = activeBtn.textContent;
            let sizeName = sizeSelector.options[sizeSelector.selectedIndex].text;
            let priceText = document.getElementById('dynamic-price').textContent;
            let price = parseFloat(priceText.replace('₹', '').replace(/,/g, '').trim());

            fetch('/checkout/direct', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        product_id: {{ $product->id }},
                        quantity: 1,
                        price: price, // Re-validate on backend!
                        metal_configuration: {
                            material_id: materialId,
                            material_name: materialName,
                            size_id: sizeId,
                            size_name: sizeName,
                            color_id: window.selectedColorId || null,
                            color_name: window.selectedColorName || null
                        }
                    })
                })
                .then(async response => {
                    if (!response.ok) {
                        if (response.status === 401) {
                            window.location.href = "{{ route('login') }}";
                            return;
                        }
                        if (response.status === 422) {
                            const data = await response.json();
                            alert(data.message || 'Validation failed.');
                            return;
                        }
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && data.success) {
                        window.location.href = data.redirect_url;
                    } else if (data) {
                        alert(data.message || 'Failed to initiate buy now.');
                    }
                })
                .catch(err => {
                    console.error('Buy Now Error:', err);
                    // Prevent alert if redirected
                    if (!err.status || err.status !== 401) {
                        alert('Something went wrong. Please try again.');
                    }
                });
        };

        // Color selection function
        window.selectColor = function(button) {
            // Remove active class from all color buttons
            document.querySelectorAll('.color-btn').forEach(btn => {
                btn.classList.remove('active');
                const checkmark = btn.querySelector('.checkmark');
                if (checkmark) checkmark.style.display = 'none';
            });

            // Add active to clicked button
            button.classList.add('active');
            const checkmark = button.querySelector('.checkmark');
            if (checkmark) {
                checkmark.style.display = button.classList.contains('text-btn') ? 'inline' : 'block';
            }

            // Store selected color
            window.selectedColorId = button.dataset.colorId;
            window.selectedColorName = button.dataset.colorName;
        };

        // Initialize first color as selected
        document.addEventListener('DOMContentLoaded', function() {
            const firstColorBtn = document.querySelector('.color-btn');
            if (firstColorBtn) {
                window.selectedColorId = firstColorBtn.dataset.colorId;
                window.selectedColorName = firstColorBtn.dataset.colorName;
            }
        });

        // Pincode Check Function
        window.checkPincode = function() {
            const pincodeInput = document.getElementById('pincode-input');
            const messageDiv = document.getElementById('pincode-message');
            const pincode = pincodeInput.value.trim();

            if (!pincode) {
                messageDiv.innerHTML = '<span style="color: orange;">⚠️ Please enter a pincode</span>';
                return;
            }

            // Show loading
            messageDiv.innerHTML = '<span style="color: #666;">⏳ Checking...</span>';

            fetch('{{ route('api.checkPincode') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        pincode: pincode
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.available) {
                        messageDiv.innerHTML = '<span style="color: green;">' + data.message + '</span>';
                    } else {
                        messageDiv.innerHTML = '<span style="color: red;">' + data.message + '</span>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    messageDiv.innerHTML = '<span style="color: red;">❌ Error checking pincode</span>';
                });
        };

        window.showNotifyModal = function() {
            document.getElementById('notifyModal').style.display = 'flex';
        };

        window.closeNotifyModal = function() {
            document.getElementById('notifyModal').style.display = 'none';
        };

        window.onclick = function(event) {
            let modal = document.getElementById('notifyModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        };

        document.getElementById('notifyForm').addEventListener('submit', function(e) {
            e.preventDefault();
            let btn = document.getElementById('notifySubmitBtn');
            let originalTxt = btn.textContent;
            btn.disabled = true;
            btn.textContent = 'SAVING...';

            let formData = new FormData(this);
            fetch('{{ route('product.notify') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('notifyForm').style.display = 'none';
                        document.getElementById('notifySuccess').style.display = 'block';

                        // Update the main button text and behavior
                        let mainBtn = document.getElementById('notify-me-btn');
                        if (mainBtn) {
                            mainBtn.innerHTML =
                                '<i class="fi fi-rr-check" style="margin-right: 8px;"></i> ALREADY REQUESTED';
                            mainBtn.onclick = null;
                            mainBtn.disabled = true;
                            mainBtn.style.cursor = 'default';
                            mainBtn.style.background = '#607d8b';
                        }

                        setTimeout(() => {
                            closeNotifyModal();
                        }, 3000);
                    } else {
                        if (data.already_exists) {
                            alert(data.message);
                            closeNotifyModal();
                            // Update button anyway if it exists
                            let mainBtn = document.getElementById('notify-me-btn');
                            if (mainBtn) {
                                mainBtn.innerHTML =
                                    '<i class="fi fi-rr-check" style="margin-right: 8px;"></i> ALREADY REQUESTED';
                                mainBtn.onclick = null;
                                mainBtn.disabled = true;
                                mainBtn.style.cursor = 'default';
                                mainBtn.style.background = '#607d8b';
                            }
                        } else {
                            alert('Error: ' + data.message);
                        }
                        btn.disabled = false;
                        btn.textContent = originalTxt;
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Something went wrong. Please try again.');
                    btn.disabled = false;
                    btn.textContent = originalTxt;
                });
        });
    </script>
    <style>
        .metal-btn.active {
            background-color: #312110 !important;
            color: white;
        }

        .color-btn {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .color-btn .checkmark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-weight: bold;
            font-size: 16px;
            text-shadow: 0 0 3px rgba(0, 0, 0, 0.5);
        }

        .color-btn.text-btn .checkmark {
            position: relative;
            top: auto;
            left: auto;
            transform: none;
            color: #4CAF50;
            text-shadow: none;
        }

        .color-btn:hover {
            transform: scale(1.1);
        }

        .row {
            display: flex;
            gap: 30px;
        }

        .d-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .mt-0 {
            margin-top: 0px;
        }

        .additional-info {
            .row {
                display: flex;
                gap: 30px;
                justify-content: space-between;
            }

            .col-lg-8 {
                width: 70%;
            }

            .col-lg-4 {
                width: 30%;
            }

            .d-block {
                display: block !important;
            }

            .mt-3 {
                margin-top: 1rem !important;
            }


            #diamond-details-table thead,
            #diamond-details-table tbody {
                display: block;
                width: 100%;
            }

            #diamond-details-table tr {
                display: grid;
                grid-template-columns: repeat(6, 1fr);
            }
        }

        #diamond-details-table {
            display: block;
            width: 100%;
        }


        #pincode-input,
        #pincode-input:focus,
        #pincode-input:active,
        #pincode-input:hover {
            border: none !important;
            outline: none !important;
            box-shadow: none !important;
        }

        .col-lg-6.col-md-6.col-12 {
            width: 50%;
        }


        @media (max-width: 768px) {
            .row {
                flex-direction: column;
                gap: 15px;
            }

            .col-lg-6.col-md-6.col-12 {
                width: 100%;
            }

            .additional-info {
                .row {
                    flex-direction: column;
                    gap: 0;
                }

                .col-lg-8 {
                    width: 100%;
                }

                .col-lg-4 {
                    width: 100%;
                }

                #diamond-details-table {
                    display: block;
                    overflow-x: auto;
                    -webkit-overflow-scrolling: touch;
                }

                #diamond-details-table tr {
                    min-width: 600px;
                    /* Force minimum width to prevent squashing */
                }
            }
        }
    </style>
@endpush
