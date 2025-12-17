@extends('frontend.layouts.app')

@section('title', 'Home')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/bootstrap.min.css') }}" />

    <script>
        window.productConfigs = @json($product->metal_configurations);
        window.materials = @json($materials->keyBy('id'));
        window.sizes = @json($sizes->keyBy('id'));
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
                                        <div class="swiper-button-prev"></div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="product-single-right">
                            <h2>
                                {{ $product->product_name }}
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
                            <div class="apply-coupon-div">
                                <h6>
                                    GET IT FOR 29047
                                </h6>
                                <p>
                                    Use BLACKFRIDAY
                                </p>
                                <button>
                                    Apply
                                </button>
                            </div>
                            <div class="color">
                                <p>
                                    COLOR
                                </p>
                                <div class="color-option">
                                    @if (is_array($product->colors))
                                        @foreach ($product->colors as $color)
                                            <!-- Assuming color is a hex code or name. If it's a code, use style background. -->
                                            <button style="background-color: {{ $color }};"
                                                title="{{ $color }}"></button>
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
                                <button>ADD TO CART</button>
                                <button>BUY NOW</button>
                            </div>
                            <p class="p2">
                                <a href="#">Delivery & Cancellation</a>
                            </p>
                            <p class="p2">
                                <a href="#">Estimated delivery by </a>
                            </p>
                            <form>
                                <div class="pincode">
                                    <input type="number" placeholder="Enter Pincode">
                                    <button type="submit">Check</button>
                                </div>
                            </form>
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
                                            Gross Weight(Product): <span
                                                id="gross-weight">{{ $product->gross_weight }}</span> g <br>
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
                                                    Rs. {{ number_format($materialCost, 2) }}
                                                </td>
                                            </tr>
                                            <tr id="diamond-row"
                                                style="display: {{ $hasDiamonds ? 'table-row' : 'none' }};">
                                                <td>
                                                    Diamond <span style="font-size: 10px;"
                                                        id="diamond-breakdown-text">({{ number_format($diamondTotalWt, 3) }}g
                                                        x {{ $diamondPricePerCarat }})</span>
                                                </td>
                                                <td id="diamond-cost-display">
                                                    Rs. {{ number_format($diamondCost, 2) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Making Charge
                                                </td>
                                                <td id="making-charge-display">
                                                    Rs. {{ number_format($makingCharge, 2) }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    GST (<span id="gst-percent-display">{{ $gstPercentage }}</span>%)
                                                </td>
                                                <td id="gst-amount-display">
                                                    Rs. {{ number_format($gstAmount, 2) }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <b>Total</b>
                                                </td>
                                                <td>
                                                    <b id="final-total-display">Rs.
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
                    <div class="row">
                        @if ($similarProducts->count() > 0)
                            @foreach ($similarProducts as $sProduct)
                                <div class="col-lg-3 col-md-3 col-sm-4 col-6">
                                    <div class="similar-product-item text-center">
                                        <a href="{{ route('product.show', $sProduct->id) }}"
                                            style="text-decoration: none; color: inherit;">
                                            <img src="{{ asset('storage/' . $sProduct->main_image) }}"
                                                alt="{{ $sProduct->product_name }}">
                                            <p>
                                                {{ $sProduct->product_name }}
                                            </p>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12 text-center">
                                <p>No similar products found.</p>
                            </div>
                        @endif
                    </div>
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
            return 'Rs. ' + parseFloat(amount).toLocaleString('en-IN', {
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
            diamondRow.style.display = hasDiamonds ? 'table-row' : 'none';
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
    </script>
    <style>
        .metal-btn.active {
            border: 2px solid #000;
            /* Example active style */
            font-weight: bold;
        }
    </style>
@endpush
