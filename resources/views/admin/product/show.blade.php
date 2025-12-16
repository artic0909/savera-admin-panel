<div class="row">
    <div class="col-md-5">
        <!-- Main Image -->
        <div class="mb-3 text-center">
            <img src="{{ asset('storage/' . $product->main_image) }}" class="img-fluid rounded shadow-sm" style="max-height: 300px;" alt="{{ $product->product_name }}">
        </div>
        <!-- Additional Images -->
        @if($product->additional_images)
        <div class="d-flex gap-2 justify-content-center flex-wrap">
            @foreach($product->additional_images as $img)
            <img src="{{ asset('storage/' . $img) }}" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;" alt="Additional Image">
            @endforeach
        </div>
        @endif
    </div>
    
    <div class="col-md-7">
        <h4 class="fw-bold">{{ $product->product_name }}</h4>
        <p class="text-muted mb-2">Category: <span class="badge bg-label-primary">{{ $product->category->name }}</span></p>
        <p class="mb-2"><strong>Delivery Time (in days):</strong> {{ $product->delivery_time }}</p>
        
        <div class="mb-3">
             <strong>Available Pincodes:</strong>
             @if($product->available_pincodes)
                @foreach($product->available_pincodes as $pinId)
                    @php $pincode = \App\Models\Pincode::find($pinId); @endphp
                    @if($pincode)
                    <span class="badge bg-label-info">{{ $pincode->code }}</span>
                    @endif
                @endforeach
             @else
                <span class="text-muted">None</span>
             @endif
        </div>

        <div class="mb-3">
             <strong>Colors:</strong>
             @if($product->colors)
                @foreach($product->colors as $colorId)
                    @php $color = \App\Models\Color::find($colorId); @endphp
                    @if($color)
                    <span class="badge bg-light text-dark">{{ $color->color_name }}</span>
                    @endif
                @endforeach
             @else
                <span class="text-muted">None</span>
             @endif
        </div>
    </div>
</div>

<hr class="my-4">

<!-- Metal Configurations -->
<div class="mb-4">
    <h5 class="fw-bold text-primary mb-3"><i class="bx bx-diamond me-2"></i>Metal Configurations</h5>
    @if($product->metal_configurations)
        @foreach($product->metal_configurations as $metalId => $configs)
            @php $metal = \App\Models\Metal::find($metalId); @endphp
            @if($metal)
            <div class="card mb-3 border border-light shadow-sm">
                <div class="card-header bg-label-secondary py-2">
                    <h6 class="mb-0 fw-bold">{{ $metal->metal_name }} ({{ $metal->metal_purity }})</h6>
                </div>
                <div class="card-body pt-3">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Size</th>
                                    <th>Net Wt (gm)</th>
                                    <th>Gross Wt (gm)</th>
                                    <th>Purity</th>
                                    <th>Making Charge</th>
                                    <th>GST %</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($configs as $config)
                                @php $size = \App\Models\Size::find($config['size_id'] ?? null); @endphp
                                <tr>
                                    <td>{{ $size ? $size->size_name : '-' }}</td>
                                    <td>{{ $config['net_weight_gold'] ?? '-' }}</td>
                                    <td>{{ $config['gross_weight_product'] ?? '-' }}</td>
                                    <td>{{ $config['purity'] ?? '-' }}</td>
                                    <td>{{ $config['making_charge'] ?? '-' }}</td>
                                    <td>{{ $config['gst_percentage'] ?? '-' }}%</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        @endforeach
    @else
        <p class="text-muted">No metal configurations found.</p>
    @endif
</div>

<!-- Diamond Details -->
@if($product->is_diamond_used && $product->diamond_gemstone_info)
<div class="mb-4">
    <h5 class="fw-bold text-info mb-3"><i class="bx bx-diamond me-2"></i>Diamond & Gemstone Details</h5>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="bg-primary text-white">
                <tr>
                    <th class="text-white">Size</th>
                    <th class="text-white">Color</th>
                    <th class="text-white">Clarity</th>
                    <th class="text-white">Shape/Cut</th>
                    <th class="text-white">No. of Diamonds</th>
                    <th class="text-white">Total Wt (gm)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($product->diamond_gemstone_info as $diamond)
                <tr>
                    <td>{{ $diamond['size'] ?? '-' }}</td>
                    <td>{{ $diamond['color'] ?? '-' }}</td>
                    <td>{{ $diamond['clarity'] ?? '-' }}</td>
                    <td>{{ $diamond['shape'] ?? '-' }}</td>
                    <td>{{ $diamond['number_of_diamonds'] ?? '-' }}</td>
                    <td>{{ $diamond['total_weight'] ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
