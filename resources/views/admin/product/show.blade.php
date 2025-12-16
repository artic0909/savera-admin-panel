<div class="row">
    <div class="col-md-5">
        <!-- Image Carousel -->
        <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ asset('storage/' . $product->main_image) }}" class="d-block w-100 rounded" alt="Main Image">
                </div>
                @if($product->additional_images)
                    @foreach($product->additional_images as $image)
                    <div class="carousel-item">
                        <img src="{{ asset('storage/' . $image) }}" class="d-block w-100 rounded" alt="Additional Image">
                    </div>
                    @endforeach
                @endif
            </div>
            @if($product->additional_images)
            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
            @endif
        </div>
        
        <div class="mt-4">
            <h6 class="fw-bold">Product Details</h6>
            <table class="table table-sm table-borderless">
                <tr>
                    <td class="text-muted">Category:</td>
                    <td class="fw-bold">{{ $product->category->name ?? 'N/A' }}</td>
                </tr>
                 <tr>
                    <td class="text-muted">Delivery:</td>
                    <td class="fw-bold">{{ $product->delivery_time }} days</td>
                </tr>
                <tr>
                    <td class="text-muted">Pincodes:</td>
                    <td>
                        @foreach($productPincodes as $pincode)
                            <span class="badge bg-label-primary">{{ $pincode->code }}</span>
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <td class="text-muted">Colors:</td>
                     <td>
                        @foreach($productColors as $color)
                            <span class="badge bg-label-secondary">{{ $color->color_name }}</span>
                        @endforeach
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="col-md-7">
        <h4 class="fw-bold">{{ $product->product_name }}</h4>
        <p class="text-muted">SKU: #{{ $product->id }}</p>

        <hr>

        <h6 class="fw-bold">Configuration & Pricing</h6>
        
        <!-- Metal Configuration Tabs -->
        <ul class="nav nav-tabs" id="metalTabs" role="tablist">
             @foreach($metals as $index => $metal)
                @if(isset($product->metal_configurations[$metal->id]))
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $index == 0 ? 'active' : '' }}" id="metal-tab-{{ $metal->id }}" data-bs-toggle="tab" data-bs-target="#metal-content-{{ $metal->id }}" type="button" role="tab">{{ $metal->metal_name }} ({{ $metal->metal_purity }})</button>
                    </li>
                @endif
            @endforeach
        </ul>

        <div class="tab-content pt-3" id="metalTabsContent">
            @foreach($metals as $metalIndex => $metal)
                @if(isset($product->metal_configurations[$metal->id]))
                <div class="tab-pane fade {{ $metalIndex == 0 ? 'show active' : '' }}" id="metal-content-{{ $metal->id }}" role="tabpanel">
                     <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Config (Size)</th>
                                    <th>Calculation Breakdown</th>
                                    <th class="text-end">Final Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product->metal_configurations[$metal->id] ?? [] as $config)
                                @php
                                    // 1. Fetch Material Price
                                    $materialPrice = 0;
                                    $materialName = 'Unknown Material';
                                    if(isset($config['material_id'])) {
                                        $mat = $materials->firstWhere('id', $config['material_id']);
                                        if($mat) {
                                            $materialPrice = $mat->price;
                                            $materialName = $mat->name;
                                        }
                                    }

                                    // 2. Fetch Diamond Price
                                    $diamondMaterial = $materials->firstWhere('name', 'Diamond');
                                    // Default to 5000 if not found, just for safety, but usually should be 0 or handled. 
                                    // User logic implies there is a price. Let's use 0 if missing.
                                    $diamondPricePerCarat = $diamondMaterial ? $diamondMaterial->price : 0; 
                                    
                                    // Calculations
                                    $netWt = floatval($config['net_weight_gold']);
                                    $materialCost = $netWt * $materialPrice;
                                    
                                    $diamondTotalWt = 0;
                                    if(isset($config['is_diamond_used']) && $config['is_diamond_used'] && isset($config['diamond_info'])) {
                                        foreach($config['diamond_info'] ?? [] as $dInfo) {
                                            $diamondTotalWt += floatval($dInfo['total_weight']);
                                        }
                                    }
                                    $diamondCost = $diamondTotalWt * $diamondPricePerCarat;
                                    
                                    $makingCharge = floatval($config['making_charge']);
                                    
                                    $basePrice = $materialCost + $diamondCost + $makingCharge;
                                    $gstAmount = ($basePrice * floatval($config['gst_percentage'])) / 100;
                                    $finalPrice = $basePrice + $gstAmount;
                                    
                                    $sizeName = 'N/A';
                                    $sObj = $sizes->firstWhere('id', $config['size_id']);
                                    if($sObj) $sizeName = $sObj->size_name;
                                @endphp
                                <tr>
                                    <td>
                                        <strong>{{ $sizeName }}</strong><br>
                                        <small class="text-muted">{{ $materialName }}</small>
                                    </td>
                                    <td>
                                        <ul class="list-unstyled mb-0 text-xs">
                                            <li>Material: {{ number_format($netWt, 3) }}g x ₹{{ $materialPrice }} = ₹{{ number_format($materialCost, 2) }}</li>
                                            @if($diamondTotalWt > 0)
                                            <li>Diamond: {{ number_format($diamondTotalWt, 3) }} x ₹{{ $diamondPricePerCarat }} = ₹{{ number_format($diamondCost, 2) }}</li>
                                            @endif
                                            <li>Making: ₹{{ number_format($makingCharge, 2) }}</li>
                                            <li>GST ({{ $config['gst_percentage'] }}%): ₹{{ number_format($gstAmount, 2) }}</li>
                                        </ul>
                                        @if($diamondTotalWt > 0)
                                            <div class="mt-1 p-2 bg-light rounded border">
                                                <small class="fw-bold d-block mb-1">Diamond Details:</small>
                                                @foreach($config['diamond_info'] ?? [] as $d)
                                                    <div class="d-flex justify-content-between text-xs text-muted">
                                                        <span>{{ $d['number_of_diamonds'] }}x {{ $d['shape'] }}</span>
                                                        <span>{{ $d['size'] }}, {{ $d['color'] }}/{{ $d['clarity'] }} ({{ $d['total_weight'] }}g)</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold text-primary">
                                        ₹{{ number_format($finalPrice, 2) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                     </div>
                </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
