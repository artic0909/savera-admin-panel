@extends('frontend.layouts.app')

@section('title', 'Home')

@section('content')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/css/pages/product-details.css') }}">
    @endpush

    @push('scripts')
        <script>
            window.pdConfig = {
                productConfigs: @json($product->metal_configurations),
                materials: @json($materials->keyBy('id')),
                sizes: @json($sizes->keyBy('id')),
                wishlistId: {{ $wishlistItem ? $wishlistItem->id : 'null' }},
                productId: {{ $product->id }},
                diamondRate: {{ $materials->first(fn($m) => strcasecmp($m->name, 'Diamond') === 0)->price ?? 0 }},
                coupons: @json($coupons),
                csrfToken: '{{ csrf_token() }}',
                loginUrl: '{{ route('login') }}',
                notifyUrl: '{{ route('product.notify') }}',
                pincodeUrl: '{{ route('api.checkPincode') }}'
            };
        </script>
        <script src="{{ asset('assets/js/pages/product-details.js') }}"></script>
    @endpush

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
