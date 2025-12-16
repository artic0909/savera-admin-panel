<div class="row g-4">
    <!-- Left Column: Media -->
    <div class="col-md-5">
        <div class="card shadow-none border bg-light">
            <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner rounded">
                    <div class="carousel-item active">
                        <img src="{{ asset('storage/' . $product->main_image) }}" class="d-block w-100 object-fit-contain" style="max-height: 400px;" alt="Main Image">
                    </div>
                    @if($product->additional_images)
                        @foreach($product->additional_images as $image)
                        <div class="carousel-item">
                            <img src="{{ asset('storage/' . $image) }}" class="d-block w-100 object-fit-contain" style="max-height: 400px;" alt="Additional Image">
                        </div>
                        @endforeach
                    @endif
                </div>
                @if($product->additional_images)
                <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon bg-dark rounded-circle" aria-hidden="true" style="width: 30px; height: 30px; background-size: 50%;"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon bg-dark rounded-circle" aria-hidden="true" style="width: 30px; height: 30px; background-size: 50%;"></span>
                    <span class="visually-hidden">Next</span>
                </button>
                @endif
            </div>
        </div>
        
        <!-- Thumbnail Grid if multiple images -->
        @if($product->additional_images)
        <div class="d-flex gap-2 mt-2 overflow-auto py-1">
             <img src="{{ asset('storage/' . $product->main_image) }}" class="rounded cursor-pointer border border-primary" style="width: 60px; height: 60px; object-fit: cover; opacity: 1;" onclick="document.querySelector('#productCarousel .carousel-item.active').classList.remove('active'); document.querySelector('#productCarousel .carousel-inner').children[0].classList.add('active');">
             @foreach($product->additional_images as $index => $image)
             <img src="{{ asset('storage/' . $image) }}" class="rounded cursor-pointer border" style="width: 60px; height: 60px; object-fit: cover;" onclick="/* Add simple JS to switch slide index here if desired */">
             @endforeach
        </div>
        @endif
    </div>

    <!-- Right Column: Details -->
    <div class="col-md-7">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h4 class="fw-bold text-dark mb-1">{{ $product->product_name }}</h4>
                <div class="text-muted small">Product ID: {{ $product->id }}</div>
            </div>
            <span class="badge bg-label-primary fs-6">{{ $product->category->name ?? 'Uncategorized' }}</span>
        </div>

        <div class="row mt-4 g-3">
            <div class="col-6 col-sm-4">
                <div class="d-flex align-items-center mb-1 text-muted small"><i class="bx bx-time me-1"></i> Delivery</div>
                <div class="fw-medium">{{ $product->delivery_time }} Days</div>
            </div>
            <div class="col-6 col-sm-8">
                 <div class="d-flex align-items-center mb-1 text-muted small"><i class="bx bx-palette me-1"></i> Colors</div>
                 <div class="d-flex flex-wrap gap-1">
                    @foreach($productColors as $color)
                        <span class="badge bg-label-secondary">{{ $color->color_name }}</span>
                    @endforeach
                 </div>
            </div>
        </div>
        
        @if($productPincodes->isNotEmpty())
        <div class="mt-3">
             <div class="d-flex align-items-center mb-1 text-muted small"><i class="bx bx-map me-1"></i> Available Pincodes</div>
             <div class="d-flex flex-wrap gap-1" style="max-height: 80px; overflow-y: auto;">
                @foreach($productPincodes as $pincode)
                    <span class="badge bg-label-info text-xs">{{ $pincode->code }}</span>
                @endforeach
             </div>
        </div>
        @endif

        <hr class="my-4">

        <h5 class="fw-bold mb-3"><i class="bx bx-cog me-2"></i>Configuration & Pricing</h5>
        
        <!-- Metal Configuration Tabs -->
        <ul class="nav nav-tabs nav-fill" id="metalTabs" role="tablist">
             @foreach($metals as $index => $metal)
                @if(isset($product->metal_configurations[$metal->id]))
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $index == 0 ? 'active' : '' }}" id="metal-tab-{{ $metal->id }}" data-bs-toggle="tab" data-bs-target="#metal-content-{{ $metal->id }}" type="button" role="tab">
                            {{ $metal->metal_name }} <span class="badge bg-primary rounded-pill ms-1">{{ $metal->metal_purity }}</span>
                        </button>
                    </li>
                @endif
            @endforeach
        </ul>

        <div class="tab-content pt-3" id="metalTabsContent">
            @foreach($metals as $metalIndex => $metal)
                @if(isset($product->metal_configurations[$metal->id]))
                <div class="tab-pane fade {{ $metalIndex == 0 ? 'show active' : '' }}" id="metal-content-{{ $metal->id }}" role="tabpanel">
                     <div class="table-responsive border rounded">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="30%">Configuration</th>
                                    <th>Price Breakdown</th>
                                    <th class="text-end text-nowrap" width="20%">Total Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product->metal_configurations[$metal->id] ?? [] as $config)
                                @php
                                    // 1. Fetch Material details
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
                                    $diamondPricePerCarat = $diamondMaterial ? $diamondMaterial->price : 0; 
                                    
                                    // Calculations
                                    $netWt = floatval($config['net_weight_gold']);
                                    $materialCost = $netWt * $materialPrice;
                                    
                                    $diamondTotalWt = 0;
                                    // Calculate diamond total weight
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
                                    <td class="align-top">
                                        <div class="d-flex flex-column gap-1">
                                            <span class="badge bg-label-dark align-self-start">{{ $sizeName }}</span>
                                            <small class="fw-bold text-dark">{{ $materialName }}</small>
                                            <div class="text-muted text-xs">
                                                <div>Net Wt: {{ $config['net_weight_gold'] }}g</div>
                                                <div>Gross Wt: {{ $config['gross_weight_product'] }}g</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-top">
                                        <!-- Mini Table for perfect alignment -->
                                        <table class="table table-sm table-borderless table-xs mb-0">
                                            <tr>
                                                <td class="text-muted ps-0">Material <span class="text-xs">({{ number_format($netWt, 3) }}g x {{ $materialPrice }})</span></td>
                                                <td class="text-end pe-0">₹{{ number_format($materialCost, 2) }}</td>
                                            </tr>
                                            @if($diamondTotalWt > 0)
                                            <tr>
                                                <td class="text-muted ps-0">Diamond <span class="text-xs">({{ number_format($diamondTotalWt, 3) }}g x {{ $diamondPricePerCarat }})</span></td>
                                                <td class="text-end pe-0">₹{{ number_format($diamondCost, 2) }}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td class="text-muted ps-0">Making Charge</td>
                                                <td class="text-end pe-0">₹{{ number_format($makingCharge, 2) }}</td>
                                            </tr>
                                            <tr class="border-top">
                                                <td class="text-muted ps-0 pt-2">Subtotal</td>
                                                <td class="text-end pe-0 pt-2 fw-medium">₹{{ number_format($basePrice, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted ps-0">GST ({{ $config['gst_percentage'] }}%)</td>
                                                <td class="text-end pe-0 text-danger">+ ₹{{ number_format($gstAmount, 2) }}</td>
                                            </tr>
                                        </table>

                                        @if($diamondTotalWt > 0)
                                            <div class="mt-2 pt-2 border-top">
                                                <a class="text-primary text-xs text-decoration-none dropdown-toggle" data-bs-toggle="collapse" href="#diamondDetails{{ $metal->id }}{{ $loop->index }}" role="button" aria-expanded="false">
                                                    View Diamond Details
                                                </a>
                                                <div class="collapse mt-2" id="diamondDetails{{ $metal->id }}{{ $loop->index }}">
                                                    <div class="card card-body p-0 border-0">
                                                        <table class="table table-sm table-bordered table-striped mb-0 text-xs">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th class="px-2 py-1">Cut</th>
                                                                    <th class="px-2 py-1">Size</th>
                                                                    <th class="px-2 py-1">Col/Clar</th>
                                                                    <th class="px-2 py-1 text-center">Qty</th>
                                                                    <th class="px-2 py-1 text-end">Wt(g)</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($config['diamond_info'] ?? [] as $d)
                                                                    <tr>
                                                                        <td class="px-2 py-1">{{ $d['shape'] }}</td>
                                                                        <td class="px-2 py-1">{{ $d['size'] }}</td>
                                                                        <td class="px-2 py-1">{{ $d['color'] }}/{{ $d['clarity'] }}</td>
                                                                        <td class="px-2 py-1 text-center">{{ $d['number_of_diamonds'] }}</td>
                                                                        <td class="px-2 py-1 text-end">{{ $d['total_weight'] }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-end align-top">
                                        <h5 class="text-primary mb-0">₹{{ number_format($finalPrice, 2) }}</h5>
                                        <small class="text-muted">incl. all taxes</small>
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
