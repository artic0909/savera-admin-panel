@extends('frontend.layouts.app')

@section('title', 'Home')

@section('content')
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
                            <div class="big-img">
                                <!-- Main Image as first tab -->
                                <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->product_name }}"
                                    id="tab1" class="tabcontent" style="display: block;">

                                @if (is_array($product->additional_images))
                                    @foreach ($product->additional_images as $index => $image)
                                        <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->product_name }}"
                                            id="tab{{ $index + 2 }}" class="tabcontent">
                                    @endforeach
                                @endif
                            </div>
                            <div class="rating-div">
                                <p>
                                    <img src="{{ asset('assets/images/star.png') }}" alt="...">
                                    4.2 | 12
                                </p>
                            </div>
                            <div class="img-tab-btn tab">
                                <div class="swiper product-tab-slider">
                                    <div class="swiper-wrapper">
                                        <!-- Main thumbnail -->
                                        <div class="swiper-slide">
                                            <img src="{{ asset('storage/' . $product->main_image) }}"
                                                alt="{{ $product->product_name }}" class="tablinks"
                                                onclick="openCity(event, 'tab1')">
                                        </div>

                                        @if (is_array($product->additional_images))
                                            @foreach ($product->additional_images as $index => $image)
                                                <div class="swiper-slide">
                                                    <img src="{{ asset('storage/' . $image) }}"
                                                        alt="{{ $product->product_name }}" class="tablinks"
                                                        onclick="openCity(event, 'tab{{ $index + 2 }}')">
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <!-- <div class="swiper-button-next"></div>
                                                                                                                                                                                                                                                                                                                                                                                                                                <div     class="swiper-button-prev"></div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-12 mt-4 mt-lg-0">
                        <div class="product-single-right">
                            <h2 class="d-flex">
                                {{ $product->product_name }}
                                <div style="position: relative;">

                                    <i class="fi {{ $wishlistItem ? 'fi-sr-heart' : 'fi-rr-heart' }}" id="wishlist-icon"
                                        style="font-size: 25px; cursor: pointer; transition: color 0.3s; color: {{ $wishlistItem ? 'red' : 'inherit' }};"
                                        onclick="toggleWishlist(this)"></i>
                                </div>
                            </h2>
                            <h5 id="dynamic-price">
                                {{ $product->display_price }}
                            </h5>
                            <p class="p1">
                                Price exclusive of taxes. See the full <a href="#">Price Breakup</a>
                            </p>
                            <p class="p2">
                                <a href="#">Special Offer for you</a>
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
                                                // Resolve color object from the passed $colors collection
                                                $colorObj = $colors->find($colorId);
                                                $colorVal = $colorObj ? $colorObj->color_name : $colorId;

                                                // Custom Color Map for names not standard in CSS or missing Hex
                                                $colorMap = [
                                                    'rose gold' => '#B76E79',
                                                    'gold' => '#FFD700',
                                                    'silver' => '#C0C0C0',
                                                ];

                                                $lowerVal = strtolower($colorVal);
                                                $mappedHex = $colorMap[$lowerVal] ?? null;

                                                // Check if it looks like a hex code OR is in our custom map OR is a standard color
                                                $isHex =
                                                    preg_match('/^#[a-f0-9]{6}$/i', $colorVal) ||
                                                    preg_match('/^#[a-f0-9]{3}$/i', $colorVal) ||
                                                    $mappedHex ||
                                                    in_array(strtolower($colorVal), [
                                                        'red',
                                                        'blue',
                                                        'green',
                                                        'yellow',
                                                        'black',
                                                        'white',
                                                        'pink',
                                                    ]);

                                                // Determine the background style value
                                                $bgStyle = $mappedHex ? $mappedHex : $colorVal;
                                            @endphp

                                            @if ($isHex)
                                                <button class="color-btn {{ $index === 0 ? 'active' : '' }}"
                                                    style="background-color: {{ $bgStyle }}; position: relative;"
                                                    data-color-id="{{ $colorId }}"
                                                    data-color-name="{{ $colorObj ? $colorObj->color_name : $colorVal }}"
                                                    onclick="selectColor(this)"
                                                    title="{{ $colorObj ? $colorObj->color_name : $colorVal }}">
                                                    <span class="checkmark"
                                                        style="display: {{ $index === 0 ? 'block' : 'none' }};">✓</span>
                                                </button>
                                            @else
                                                <button class="text-btn color-btn {{ $index === 0 ? 'active' : '' }}"
                                                    title="{{ $colorVal }}" data-color-id="{{ $colorId }}"
                                                    data-color-name="{{ $colorVal }}" onclick="selectColor(this)"
                                                    style="width: auto; padding: 0 10px; font-size: 12px; position: relative;">
                                                    {{ $colorVal }}
                                                    <span class="checkmark"
                                                        style="display: {{ $index === 0 ? 'inline' : 'none' }}; margin-left: 5px;">✓</span>
                                                </button>
                                            @endif
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
                                    Ring Size
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
                            </div>
                            <p class="p2">
                                <a href="#" class="dc">Delivery & Cancellation</a>
                            </p>
                            <p class="p2">
                                <a href="#" class="dc">Estimated delivery by </a>
                            </p>
                            <div class="pincode">
                                <input type="text" id="pincode-input" placeholder="Enter Pincode" maxlength="10"
                                    style="padding: 10px; border: 1px solid #ddd; border-radius: 5px; width: calc(100% - 80px);">
                                <button type="button" onclick="checkPincode()"
                                    style="padding: 10px 15px; cursor: pointer; background: #000; color: white; border: none; border-radius: 5px;">Check</button>
                            </div>
                            <div id="pincode-message" style="margin-top: 10px; font-weight: bold;"></div>

                            <p class="cat">Category: <span>{{ $product->category->name }}</span></p>
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
                                            Gross Weight(Product): <span id="gross-weight">{{ $grossWeight }}</span> g
                                            <br>
                                            Net Weight(gold): <span id="net-weight">{{ $netWeight }}</span> g
                                        </p>
                                    </div>
                                    <div class="weight">
                                        <h6>
                                            Purity
                                        </h6>
                                        <p id="purity-display">
                                            {{ $matName }}
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
                                                            id="diamond-total-wt">{{ $diamondTotalWt }}</span> g</b>
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
                                                            <td>{{ $info['total_weight'] ?? '--' }} g</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>


                                <!-- Price Breakup -->
                                <h6 class="heading-1">
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
                                        $diamondCost = $diamondTotalWt * $diamondPricePerCarat;

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
                                                    Gold <span style="font-size: 10px;"
                                                        id="gold-breakdown-text">({{ number_format($netWt, 3) }}g x
                                                        {{ $materialPrice }})</span>
                                                </td>
                                                <td id="gold-cost-display">
                                                    ₹ {{ number_format($materialCost, 2) }}
                                                </td>
                                            </tr>
                                            <tr id="diamond-row" style="display: {{ $hasDiamonds ? '' : 'none' }};">
                                                <td>
                                                    Diamond <span style="font-size: 10px;"
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
                                        Easy 15days return
                                    </li>
                                    <li>
                                        <img src="{{ asset('assets/images/i4.png') }}" alt="...">
                                        Free Shipping & insurance
                                    </li>
                                    <li>
                                        <img src="{{ asset('assets/images/i5.png') }}" alt="...">
                                        Hallmarked gold
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
                                    <p>
                                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                        Ipsum has been the industry
                                    </p>
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



    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="{{ asset('assets/script.js') }}"></script>
    <script src="{{ asset('assets/jquery-1.12.0.min.js') }}"></script>

    <script>
        var swiper = new Swiper(".product-tab-slider", {
            slidesPerView: 4,
            spaceBetween: 30,
            loop: true,
            autoplay: true
        });
    </script>

    <script>
        function openCity(evt, cityName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
        }
    </script>

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

            document.getElementById('gross-weight').textContent = grossWt;
            document.getElementById('net-weight').textContent = netWt;
            document.getElementById('purity-display').textContent = matName;

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
                    <td>${info.total_weight || '--'} g</td>
                </tr>`;
                    diamondTableBody.innerHTML += row;
                });
            }

            document.getElementById('diamond-total-wt').textContent = formatNumber(diamondTotalWt, 3);
            document.getElementById('diamond-total-count').textContent = totalDiamondCount;
            diamondWrapper.style.display = hasDiamonds ? 'block' : 'none';

            // Price Calculations
            let materialCost = netWt * matPrice;
            let diamondCost = diamondTotalWt * diamondRate;
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

            // Main Price Display
            document.getElementById('dynamic-price').textContent = formatCurrency(finalPrice);
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
    </script>
    <style>
        .metal-btn.active {
            background-color: #3C3550 !important;
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
            display: grid;
            grid-template-columns: 1fr 1fr;
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




        @media (max-width: 768px) {
            .row {
                grid-template-columns: 1fr;
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
