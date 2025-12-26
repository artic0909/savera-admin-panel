@extends('admin.layouts.app')

@section('title', 'Edit Product')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Product /</span> Edit Product</h4>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">Product Details</h5>
                    <div class="card-body">
                        <form action="{{ route('admin.products.update', $product->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <!-- Basic Fields -->
                                <div class="mb-3 col-md-4">
                                    <label for="product_name" class="form-label">Product Name</label>
                                    <input class="form-control" type="text" id="product_name" name="product_name"
                                        value="{{ old('product_name', $product->product_name) }}" required />
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="sku" class="form-label">SKU (Unique)</label>
                                    <input class="form-control" type="text" id="sku" name="sku"
                                        value="{{ old('sku', $product->sku) }}" required />
                                    <div id="sku-feedback" class="form-text"></div>
                                </div>
                                <div class="mb-3 col-md-12">
                                    <label for="description" class="form-label">Product Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="category_id" class="form-label">Category</label>
                                    <select id="category_id" name="category_id" class="form-select" required>
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="delivery_time" class="form-label">Delivery Time (in days)</label>
                                    <input class="form-control" type="text" id="delivery_time" name="delivery_time"
                                        value="{{ old('delivery_time', $product->delivery_time) }}" required />
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="stock_quantity" class="form-label">Stock Quantity</label>
                                    <input class="form-control" type="number" id="stock_quantity" name="stock_quantity"
                                        value="{{ old('stock_quantity', $product->stock_quantity) }}" min="0"
                                        required />
                                </div>
                                <div class="mb-3 col-md-6 d-flex align-items-center mt-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                            {{ $product->is_active ? 'checked' : '' }} value="1">
                                        <label class="form-check-label fw-bold" for="is_active">Product Active</label>
                                    </div>
                                </div>
                                <div class="mb-3 col-md-12">
                                    <label class="form-label">Colors</label>
                                    @php $selectedColors = $product->colors ?? []; @endphp
                                    <div class="d-flex flex-wrap gap-3 p-3 border rounded bg-light">
                                        @foreach ($colors as $color)
                                            <div class="form-check custom-color-plate">
                                                <input class="form-check-input" type="checkbox" name="colors[]"
                                                    value="{{ $color->id }}" id="color_{{ $color->id }}"
                                                    {{ in_array($color->id, $selectedColors) ? 'checked' : '' }}>
                                                <label class="form-check-label fw-semibold"
                                                    for="color_{{ $color->id }}">
                                                    <span class="color-circle"
                                                        style="background-color: {{ $color->color_code }};"></span>
                                                    {{ $color->color_name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="main_image" class="form-label">Main Image(1:1 Aspect Ratio)</label>
                                    <input class="form-control" type="file" id="main_image" name="main_image" />
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $product->main_image) }}" alt="Current Image"
                                            width="100">
                                    </div>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="additional_images" class="form-label">Additional Media
                                        (Images/Videos - 1:1 Aspect Ratio)</label>
                                    <input class="form-control" type="file" id="additional_images"
                                        name="additional_images[]" multiple accept="image/*,video/*" />
                                    @if ($product->additional_images)
                                        <div class="mt-3 d-flex flex-wrap gap-3" id="additional-media-container">
                                            @foreach ($product->additional_images as $index => $file)
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
                                                <div class="position-relative media-item" style="width: 100px;">
                                                    @if ($isVideo)
                                                        <video width="100" height="100"
                                                            style="object-fit: cover; border-radius: 5px;">
                                                            <source src="{{ asset('storage/' . $file) }}"
                                                                type="video/{{ $extension }}">
                                                        </video>
                                                    @else
                                                        <img src="{{ asset('storage/' . $file) }}" alt="Additional Image"
                                                            width="100" height="100"
                                                            style="object-fit: cover; border-radius: 5px;">
                                                    @endif
                                                    <button type="button"
                                                        class="btn btn-danger btn-xs position-absolute top-0 end-0 remove-saved-media"
                                                        data-path="{{ $file }}"
                                                        style="padding: 2px 5px; font-size: 10px;">
                                                        <i class="bx bx-x"></i>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                        <!-- Hidden container for deleted items -->
                                        <div id="deleted-media-inputs"></div>
                                    @endif
                                </div>
                            </div>

                            <hr>

                            {{-- ================= METAL CONFIGURATION ================= --}}
                            <h5 class="mb-3">Metal Configuration</h5>

                            <div id="metal-configs">
                                @php
                                    $flatConfigs = [];
                                    if (
                                        isset($product->metal_configurations) &&
                                        is_array($product->metal_configurations)
                                    ) {
                                        $configs = $product->metal_configurations;
                                        // Helper function logic to flatten or detect config
                                        // If the first element has 'material_id', assume it's a flat list of configs
    $firstKey = array_key_first($configs);
    if (!is_null($firstKey) && isset($configs[$firstKey]['material_id'])) {
        $flatConfigs = $configs;
    } else {
        // Otherwise assume it's grouped by metal ID or some other key
                                            foreach ($configs as $group) {
                                                if (is_array($group)) {
                                                    // Check if this group is actually a config itself (edge case where key is not numeric but content is config)
                                                    if (isset($group['material_id'])) {
                                                        $flatConfigs[] = $group;
                                                    } else {
                                                        // Iterate deeper
                                                        foreach ($group as $c) {
                                                            if (is_array($c) && isset($c['material_id'])) {
                                                                $flatConfigs[] = $c;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                @endphp

                                @foreach ($flatConfigs as $index => $config)
                                    <div class="card mb-3 metal-config-row">
                                        <div class="card-body border">
                                            <!-- Add ID field, generate one if missing (though backend usually handles generation, preserving it here is key) -->
                                            <input type="hidden" name="metal_configurations[{{ $index }}][id]"
                                                value="{{ $config['id'] ?? uniqid('mc_') }}">

                                            <div class="row mb-2">
                                                <div class="col-md-3">
                                                    <label class="form-label">Material</label>
                                                    <select name="metal_configurations[{{ $index }}][material_id]"
                                                        class="form-select" required>
                                                        <option value="">Select</option>
                                                        @foreach ($materials as $material)
                                                            <option value="{{ $material->id }}"
                                                                {{ isset($config['material_id']) && $config['material_id'] == $material->id ? 'selected' : '' }}>
                                                                {{ $material->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label">Size</label>
                                                    <select name="metal_configurations[{{ $index }}][size_id]"
                                                        class="form-select" required>
                                                        @foreach ($sizes as $size)
                                                            <option value="{{ $size->id }}"
                                                                {{ isset($config['size_id']) && $config['size_id'] == $size->id ? 'selected' : '' }}>
                                                                {{ $size->size_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label">Net Wt (gm)</label>
                                                    <input type="text"
                                                        name="metal_configurations[{{ $index }}][net_weight_gold]"
                                                        class="form-control"
                                                        value="{{ $config['net_weight_gold'] ?? '' }}">
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label">Gross Wt (gm)</label>
                                                    <input type="text"
                                                        name="metal_configurations[{{ $index }}][gross_weight_product]"
                                                        class="form-control"
                                                        value="{{ $config['gross_weight_product'] ?? '' }}">
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label">Purity</label>
                                                    <input type="text"
                                                        name="metal_configurations[{{ $index }}][purity]"
                                                        class="form-control" value="{{ $config['purity'] ?? '' }}">
                                                </div>

                                                <div class="col-md-1">
                                                    <button type="button"
                                                        class="btn btn-danger mt-4 remove-metal">X</button>
                                                </div>
                                            </div>

                                            <div class="row mb-2">
                                                <div class="col-md-2">
                                                    <label class="form-label">Making Charge</label>
                                                    <input type="text"
                                                        name="metal_configurations[{{ $index }}][making_charge]"
                                                        class="form-control"
                                                        value="{{ $config['making_charge'] ?? '' }}">
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label">GST %</label>
                                                    <input type="text"
                                                        name="metal_configurations[{{ $index }}][gst_percentage]"
                                                        class="form-control"
                                                        value="{{ $config['gst_percentage'] ?? '' }}">
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label">MRP</label>
                                                    <input type="text"
                                                        name="metal_configurations[{{ $index }}][mrp]"
                                                        class="form-control" value="{{ $config['mrp'] ?? '' }}">
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label">Total Diamond Price</label>
                                                    <input type="text"
                                                        name="metal_configurations[{{ $index }}][total_diamond_price]"
                                                        class="form-control"
                                                        value="{{ $config['total_diamond_price'] ?? '' }}">
                                                </div>

                                                <div class="col-md-3 mt-4">
                                                    <!-- Check if diamond_info exists and is not empty to set checked state -->
                                                    @php
                                                        $hasDiamond = !empty($config['diamond_info']);
                                                    @endphp
                                                    <input type="checkbox" class="use-diamond"
                                                        {{ $hasDiamond ? 'checked' : '' }}>
                                                    <label class="ms-1">Used Diamond?</label>
                                                </div>
                                            </div>

                                            {{-- DIAMOND SECTION --}}
                                            <div class="diamond-section mt-3 p-3 bg-light rounded"
                                                style="display: {{ $hasDiamond ? 'block' : 'none' }};">
                                                <h6 class="text-info fw-bold">Diamond Details</h6>
                                                <div class="diamond-rows">
                                                    @if (isset($config['diamond_info']) && is_array($config['diamond_info']))
                                                        @foreach ($config['diamond_info'] as $dIndex => $dInfo)
                                                            <div class="row mb-2 diamond-row align-items-end">
                                                                <div class="col-md-2">
                                                                    <input type="text"
                                                                        name="metal_configurations[{{ $index }}][diamond_info][{{ $dIndex }}][size]"
                                                                        value="{{ $dInfo['size'] ?? '' }}"
                                                                        placeholder="Size"
                                                                        class="form-control form-control-sm">
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <input type="text"
                                                                        name="metal_configurations[{{ $index }}][diamond_info][{{ $dIndex }}][color]"
                                                                        value="{{ $dInfo['color'] ?? '' }}"
                                                                        placeholder="Color"
                                                                        class="form-control form-control-sm">
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <input type="text"
                                                                        name="metal_configurations[{{ $index }}][diamond_info][{{ $dIndex }}][clarity]"
                                                                        value="{{ $dInfo['clarity'] ?? '' }}"
                                                                        placeholder="Clarity"
                                                                        class="form-control form-control-sm">
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <input type="text"
                                                                        name="metal_configurations[{{ $index }}][diamond_info][{{ $dIndex }}][shape]"
                                                                        value="{{ $dInfo['shape'] ?? '' }}"
                                                                        placeholder="Cut"
                                                                        class="form-control form-control-sm">
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <input type="number"
                                                                        name="metal_configurations[{{ $index }}][diamond_info][{{ $dIndex }}][number_of_diamonds]"
                                                                        value="{{ $dInfo['number_of_diamonds'] ?? '' }}"
                                                                        placeholder="No."
                                                                        class="form-control form-control-sm">
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <input type="text"
                                                                        name="metal_configurations[{{ $index }}][diamond_info][{{ $dIndex }}][total_weight]"
                                                                        value="{{ $dInfo['total_weight'] ?? '' }}"
                                                                        placeholder="Total Wt(g)"
                                                                        class="form-control form-control-sm">
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <button type="button"
                                                                        class="btn btn-danger btn-sm remove-diamond">x</button>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>

                                                <button type="button" class="btn btn-sm btn-outline-info add-diamond">
                                                    + Add Diamond
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <button type="button" class="btn btn-outline-primary mb-3" id="add-metal-config">
                                + Add Metal Configuration
                            </button>

                            <hr>



                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary" id="submitBtn">Update Product</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- METAL TEMPLATE --}}
    <template id="metal-config-template">
        <div class="card mb-3 metal-config-row">
            <div class="card-body border">
                <input type="hidden" name="metal_configurations[INDEX][id]" value="NEW_ID">

                <div class="row mb-2">
                    <div class="col-md-3">
                        <label class="form-label">Material</label>
                        <select name="metal_configurations[INDEX][material_id]" class="form-select" required>
                            <option value="">Select</option>
                            @foreach ($materials as $material)
                                <option value="{{ $material->id }}">{{ $material->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Size</label>
                        <select name="metal_configurations[INDEX][size_id]" class="form-select" required>
                            @foreach ($sizes as $size)
                                <option value="{{ $size->id }}">{{ $size->size_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Net Wt (gm)</label>
                        <input type="text" name="metal_configurations[INDEX][net_weight_gold]" class="form-control">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Gross Wt (gm)</label>
                        <input type="text" name="metal_configurations[INDEX][gross_weight_product]"
                            class="form-control">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Purity</label>
                        <input type="text" name="metal_configurations[INDEX][purity]" class="form-control">
                    </div>

                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger mt-4 remove-metal">X</button>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-2">
                        <label class="form-label">Making Charge</label>
                        <input type="text" name="metal_configurations[INDEX][making_charge]" class="form-control">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">GST %</label>
                        <input type="text" name="metal_configurations[INDEX][gst_percentage]" class="form-control">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">MRP</label>
                        <input type="text" name="metal_configurations[INDEX][mrp]" class="form-control">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Total Diamond Price</label>
                        <input type="text" name="metal_configurations[INDEX][total_diamond_price]"
                            class="form-control">
                    </div>

                    <div class="col-md-3 mt-4">
                        <input type="checkbox" class="use-diamond">
                        <label class="ms-1">Used Diamond?</label>
                    </div>
                </div>

                {{-- DIAMOND SECTION --}}
                <div class="diamond-section mt-3 p-3 bg-light rounded" style="display:none;">
                    <h6 class="text-info fw-bold">Diamond Details</h6>
                    <div class="diamond-rows"></div>

                    <button type="button" class="btn btn-sm btn-outline-info add-diamond">
                        + Add Diamond
                    </button>
                </div>

            </div>
        </div>
    </template>

    {{-- DIAMOND TEMPLATE --}}
    <template id="diamond-template">
        <div class="row mb-2 diamond-row align-items-end">
            <div class="col-md-2">
                <input type="text" name="metal_configurations[METAL][diamond_info][DIAMOND][size]" placeholder="Size"
                    class="form-control form-control-sm">
            </div>
            <div class="col-md-2">
                <input type="text" name="metal_configurations[METAL][diamond_info][DIAMOND][color]"
                    placeholder="Color" class="form-control form-control-sm">
            </div>
            <div class="col-md-2">
                <input type="text" name="metal_configurations[METAL][diamond_info][DIAMOND][clarity]"
                    placeholder="Clarity" class="form-control form-control-sm">
            </div>
            <div class="col-md-2">
                <input type="text" name="metal_configurations[METAL][diamond_info][DIAMOND][shape]" placeholder="Cut"
                    class="form-control form-control-sm">
            </div>
            <div class="col-md-2">
                <input type="number" name="metal_configurations[METAL][diamond_info][DIAMOND][number_of_diamonds]"
                    placeholder="No." class="form-control form-control-sm">
            </div>
            <div class="col-md-2">
                <input type="text" name="metal_configurations[METAL][diamond_info][DIAMOND][total_weight]"
                    placeholder="Total Wt" class="form-control form-control-sm">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm remove-diamond">x</button>
            </div>
        </div>
    </template>

@endsection

@push('styles')
    <style>
        .custom-color-plate {
            display: inline-block;
            cursor: pointer;
            margin-bottom: 0;
            user-select: none;
        }

        .custom-color-plate .form-check-input {
            display: none;
        }

        .custom-color-plate .form-check-label {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px 16px;
            border: 2px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            transition: all 0.3s ease;
            cursor: pointer;
            min-width: 100px;
            gap: 10px;
            text-align: center;
            font-size: 0.9rem;
            color: #555;
        }

        .color-circle {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 1px solid #ddd;
            display: inline-block;
        }

        .custom-color-plate .form-check-input:checked+.form-check-label {
            border-color: #696cff;
            background-color: #696cff;
            color: #fff;
            box-shadow: 0 4px 8px rgba(105, 108, 255, 0.2);
        }

        .custom-color-plate .form-check-input:checked+.form-check-label .color-circle {
            border-color: #fff;
        }

        .custom-color-plate .form-check-label:hover {
            border-color: #696cff;
            background-color: #f8f9ff;
        }

        #sku-feedback {
            font-weight: 600;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const metalContainer = document.getElementById('metal-configs');
            const metalTemplate = document.getElementById('metal-config-template').innerHTML;
            const diamondTemplate = document.getElementById('diamond-template').innerHTML;

            // Initialize existing rows
            const existingRows = metalContainer.querySelectorAll('.metal-config-row');
            existingRows.forEach((row, index) => {
                setupMetalRowResults(row, index);
            });

            document.getElementById('add-metal-config').addEventListener('click', function() {
                const index = metalContainer.children.length + Math.floor(Math.random() *
                    1000); // Unique index
                const newId = 'mc_' + Date.now() + '_' + Math.floor(Math.random() *
                    1000); // Robust client-side unique ID
                let html = metalTemplate.replace(/INDEX/g, index).replace(/NEW_ID/g, newId);

                const temp = document.createElement('div');
                temp.innerHTML = html;
                const metalRow = temp.firstElementChild;

                setupMetalRowResults(metalRow, index);
                metalContainer.appendChild(metalRow);
            });

            function setupMetalRowResults(metalRow, index) {
                // Remove Metal
                const removeMetalBtn = metalRow.querySelector('.remove-metal');
                if (removeMetalBtn) {
                    removeMetalBtn.onclick = () => metalRow.remove();
                }

                // Diamond Checkbox
                const checkbox = metalRow.querySelector('.use-diamond');
                const diamondSection = metalRow.querySelector('.diamond-section');
                const diamondContainer = metalRow.querySelector('.diamond-rows');

                if (checkbox && diamondSection) {
                    checkbox.onchange = () => {
                        diamondSection.style.display = checkbox.checked ? 'block' : 'none';
                    };
                }

                // Add Diamond
                const addDiamondBtn = metalRow.querySelector('.add-diamond');
                if (addDiamondBtn) {
                    addDiamondBtn.onclick = () => {
                        const dIndex = diamondContainer.children.length + Math.floor(Math.random() * 1000);
                        let dHtml = diamondTemplate
                            .replace(/METAL/g, index) // Note: METAL placeholder in template is for metal index
                            .replace(/DIAMOND/g, dIndex);

                        const dTemp = document.createElement('div');
                        dTemp.innerHTML = dHtml;

                        const dRow = dTemp.firstElementChild;
                        const removeDBtn = dRow.querySelector('.remove-diamond');
                        if (removeDBtn) {
                            removeDBtn.onclick = () => dRow.remove();
                        }

                        diamondContainer.appendChild(dRow);
                    };
                }

                // Initialize existing diamond rows removal
                if (diamondContainer) {
                    const existingDiamonds = diamondContainer.querySelectorAll('.diamond-row');
                    existingDiamonds.forEach(dRow => {
                        const removeDBtn = dRow.querySelector('.remove-diamond');
                        if (removeDBtn) {
                            removeDBtn.onclick = () => dRow.remove();
                        }
                    });
                }
                // Handle removal of saved media
                document.querySelectorAll('.remove-saved-media').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const path = this.getAttribute('data-path');
                        const container = document.getElementById('deleted-media-inputs');

                        // Create hidden input
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'deleted_additional_images[]';
                        input.value = path;
                        container.appendChild(input);

                        // Remove preview
                        this.closest('.media-item').remove();
                    });
                });

            }

            // SKU Uniqueness Check
            const skuInput = document.getElementById('sku');
            const skuFeedback = document.getElementById('sku-feedback');
            const submitBtn = document.getElementById('submitBtn');
            const productId = "{{ $product->id }}";
            let debounceTimer;

            skuInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                const sku = this.value.trim();

                if (sku.length < 1) {
                    skuFeedback.innerHTML = '';
                    skuInput.classList.remove('is-invalid', 'is-valid');
                    submitBtn.disabled = false;
                    return;
                }

                skuFeedback.innerHTML = '<span class="text-info">Checking...</span>';

                debounceTimer = setTimeout(() => {
                    fetch(
                            `{{ route('admin.products.checkSKU') }}?sku=${sku}&product_id=${productId}`
                        )
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.exists) {
                                skuFeedback.innerHTML =
                                    `<span class="text-danger"><i class="bx bx-error-circle"></i> This SKU already taken, use different</span>`;
                                skuInput.classList.add('is-invalid');
                                skuInput.classList.remove('is-valid');
                                submitBtn.disabled = true;
                            } else {
                                skuFeedback.innerHTML =
                                    `<span class="text-success"><i class="bx bx-check-circle"></i> SKU is available.</span>`;
                                skuInput.classList.add('is-valid');
                                skuInput.classList.remove('is-invalid');
                                submitBtn.disabled = false;
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            skuFeedback.innerHTML =
                                '<span class="text-danger">Error checking SKU. Please try again.</span>';
                        });
                }, 300);
            });
        });
    </script>
@endpush
