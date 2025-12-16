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
                    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <!-- Basic Fields -->
                            <div class="mb-3 col-md-6">
                                <label for="product_name" class="form-label">Product Name</label>
                                <input class="form-control" type="text" id="product_name" name="product_name" value="{{ old('product_name', $product->product_name) }}" required />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="category_id" class="form-label">Category</label>
                                <select id="category_id" name="category_id" class="form-select" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="delivery_time" class="form-label">Delivery Time (in days)</label>
                                <input class="form-control" type="text" id="delivery_time" name="delivery_time" value="{{ old('delivery_time', $product->delivery_time) }}" required />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="available_pincodes" class="form-label">Available Pincodes</label>
                                @php $selectedPincodes = $product->available_pincodes ?? []; @endphp
                                <select id="available_pincodes" name="available_pincodes[]" class="form-select" multiple>
                                    @foreach($pincodes as $pincode)
                                    <option value="{{ $pincode->id }}" {{ in_array($pincode->id, $selectedPincodes) ? 'selected' : '' }}>{{ $pincode->code }}</option>
                                    @endforeach
                                </select>
                                 <small>Hold Ctrl (Windows) or Command (Mac) to select multiple.</small>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="colors" class="form-label">Colors</label>
                                @php $selectedColors = $product->colors ?? []; @endphp
                                <select id="colors" name="colors[]" class="form-select" multiple>
                                    @foreach($colors as $color)
                                    <option value="{{ $color->id }}" {{ in_array($color->id, $selectedColors) ? 'selected' : '' }}>{{ $color->color_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="main_image" class="form-label">Main Image</label>
                                <input class="form-control" type="file" id="main_image" name="main_image" />
                                <div class="mt-2">
                                     <img src="{{ asset('storage/' . $product->main_image) }}" alt="Current Image" width="100">
                                </div>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="additional_images" class="form-label">Additional Images</label>
                                <input class="form-control" type="file" id="additional_images" name="additional_images[]" multiple />
                                @if($product->additional_images)
                                <div class="mt-2 d-flex gap-2">
                                    @foreach($product->additional_images as $img)
                                    <img src="{{ asset('storage/' . $img) }}" alt="Additional Image" width="50">
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>

                        <hr>

                        <!-- Metal Configuration -->
                        <h5 class="mb-3">Metal Configuration</h5>
                        @php $metalConfigs = $product->metal_configurations ?? []; @endphp
                        <div id="metal-container">
                            @foreach($metals as $metal)
                            <div class="card mb-3 border border-secondary metal-section" data-metal-id="{{ $metal->id }}">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">{{ $metal->metal_name }} ({{ $metal->metal_purity }})</h6>
                                </div>
                                <div class="card-body pt-3">
                                    <div class="metal-configs-wrapper" id="metal-configs-{{ $metal->id }}">
                                        @if(isset($metalConfigs[$metal->id]))
                                            @foreach($metalConfigs[$metal->id] as $index => $config)
                                            <div class="mb-3 metal-config-row border-bottom pb-3">
                                                <div class="row align-items-end mb-2">
                                                    <!-- Material Selection -->
                                                    <div class="col-md-3">
                                                        <label class="form-label">Material Type</label>
                                                        <select name="metal_configurations[{{ $metal->id }}][{{ $index }}][material_id]" class="form-select material-select" required>
                                                            <option value="">Select Material</option>
                                                            @foreach($materials as $material)
                                                            <option value="{{ $material->id }}" {{ isset($config['material_id']) && $config['material_id'] == $material->id ? 'selected' : '' }}>{{ $material->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label class="form-label">Size</label>
                                                        <select name="metal_configurations[{{ $metal->id }}][{{ $index }}][size_id]" class="form-select" required>
                                                            <option value="">Select</option>
                                                            @foreach($sizes as $size)
                                                            <option value="{{ $size->id }}" {{ $config['size_id'] == $size->id ? 'selected' : '' }}>{{ $size->size_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="form-label">Net Wt (gm)</label>
                                                        <input type="text" name="metal_configurations[{{ $metal->id }}][{{ $index }}][net_weight_gold]" class="form-control" value="{{ $config['net_weight_gold'] }}" required>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="form-label">Gross Wt (gm)</label>
                                                        <input type="text" name="metal_configurations[{{ $metal->id }}][{{ $index }}][gross_weight_product]" class="form-control" value="{{ $config['gross_weight_product'] }}" required>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="form-label">Purity</label>
                                                        <input type="text" name="metal_configurations[{{ $metal->id }}][{{ $index }}][purity]" class="form-control" value="{{ $config['purity'] }}" required>
                                                    </div>
                                                </div>
                                                <div class="row align-items-end mb-2">
                                                    <div class="col-md-2">
                                                        <label class="form-label">Making Charge</label>
                                                        <input type="text" name="metal_configurations[{{ $metal->id }}][{{ $index }}][making_charge]" class="form-control" value="{{ $config['making_charge'] }}" required>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="form-label">GST %</label>
                                                        <input type="text" name="metal_configurations[{{ $metal->id }}][{{ $index }}][gst_percentage]" class="form-control" value="{{ $config['gst_percentage'] }}" required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check mt-4">
                                                            <input class="form-check-input use-diamond-check" type="checkbox" id="use_diamond_{{ $metal->id }}_{{ $index }}" name="metal_configurations[{{ $metal->id }}][{{ $index }}][is_diamond_used]" value="1" {{ isset($config['is_diamond_used']) && $config['is_diamond_used'] ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="use_diamond_{{ $metal->id }}_{{ $index }}">Used Diamond?</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button type="button" class="btn btn-danger btn-sm remove-row">X</button>
                                                    </div>
                                                </div>

                                                <!-- Nested Diamond Section -->
                                                <div class="nested-diamond-section mt-3 p-3 bg-lighter rounded" style="display: {{ isset($config['is_diamond_used']) && $config['is_diamond_used'] ? 'block' : 'none' }};">
                                                    <h6 class="text-info fw-bold mb-2">Diamond Details</h6>
                                                    <div class="diamond-rows-container">
                                                        @if(isset($config['diamond_info']))
                                                            @foreach($config['diamond_info'] as $dIndex => $dInfo)
                                                            <div class="row align-items-end mb-2 diamond-row border-bottom pb-2">
                                                                <div class="col-md-2">
                                                                    <label class="form-label text-xs">Size</label>
                                                                    <input type="text" name="metal_configurations[{{ $metal->id }}][{{ $index }}][diamond_info][{{ $dIndex }}][size]" class="form-control form-control-sm" value="{{ $dInfo['size'] }}" required>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label class="form-label text-xs">Color</label>
                                                                    <input type="text" name="metal_configurations[{{ $metal->id }}][{{ $index }}][diamond_info][{{ $dIndex }}][color]" class="form-control form-control-sm" value="{{ $dInfo['color'] }}" required>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label class="form-label text-xs">Clarity</label>
                                                                    <input type="text" name="metal_configurations[{{ $metal->id }}][{{ $index }}][diamond_info][{{ $dIndex }}][clarity]" class="form-control form-control-sm" value="{{ $dInfo['clarity'] }}" required>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label class="form-label text-xs">Cut</label>
                                                                    <input type="text" name="metal_configurations[{{ $metal->id }}][{{ $index }}][diamond_info][{{ $dIndex }}][shape]" class="form-control form-control-sm" value="{{ $dInfo['shape'] }}" required>
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <label class="form-label text-xs">No.</label>
                                                                    <input type="number" name="metal_configurations[{{ $metal->id }}][{{ $index }}][diamond_info][{{ $dIndex }}][number_of_diamonds]" class="form-control form-control-sm" value="{{ $dInfo['number_of_diamonds'] }}" required>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label class="form-label text-xs">Total Wt(gm)</label>
                                                                    <input type="text" name="metal_configurations[{{ $metal->id }}][{{ $index }}][diamond_info][{{ $dIndex }}][total_weight]" class="form-control form-control-sm" value="{{ $dInfo['total_weight'] }}" required>
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <button type="button" class="btn btn-danger btn-xs remove-diamond-row">x</button>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                    <button type="button" class="btn btn-xs btn-outline-info add-diamond-nested mt-2" data-metal-id="{{ $metal->id }}" data-metal-index="{{ $index }}">
                                                        + Add Diamond Info
                                                    </button>
                                                </div>
                                            </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary mt-2 add-metal-config" data-metal-id="{{ $metal->id }}">
                                        + Add Configuration for {{ $metal->metal_name }}
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <hr>



                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Update Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Template for Metal Config -->
<template id="metal-config-template">
    <div class="mb-3 metal-config-row border-bottom pb-3">
        <div class="row align-items-end mb-2">
            <!-- Material Selection -->
            <div class="col-md-3">
                <label class="form-label">Material Type</label>
                <select name="metal_configurations[METAL_ID][INDEX][material_id]" class="form-select material-select" required>
                    <option value="">Select Material</option>
                    @foreach($materials as $material)
                    <option value="{{ $material->id }}">{{ $material->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Existing Fields -->
            <div class="col-md-2">
                <label class="form-label">Size</label>
                <select name="metal_configurations[METAL_ID][INDEX][size_id]" class="form-select" required>
                    <option value="">Select</option>
                    @foreach($sizes as $size)
                    <option value="{{ $size->id }}">{{ $size->size_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Net Wt (gm)</label>
                <input type="text" name="metal_configurations[METAL_ID][INDEX][net_weight_gold]" class="form-control" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Gross Wt (gm)</label>
                <input type="text" name="metal_configurations[METAL_ID][INDEX][gross_weight_product]" class="form-control" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Purity</label>
                <input type="text" name="metal_configurations[METAL_ID][INDEX][purity]" class="form-control" required>
            </div>
        </div>
        <div class="row align-items-end mb-2">
             <div class="col-md-2">
                <label class="form-label">Making Charge</label>
                <input type="text" name="metal_configurations[METAL_ID][INDEX][making_charge]" class="form-control" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">GST %</label>
                <input type="text" name="metal_configurations[METAL_ID][INDEX][gst_percentage]" class="form-control" required>
            </div>
             <div class="col-md-3">
                <div class="form-check mt-4">
                    <input class="form-check-input use-diamond-check" type="checkbox" id="use_diamond_METAL_ID_INDEX" name="metal_configurations[METAL_ID][INDEX][is_diamond_used]" value="1">
                    <label class="form-check-label" for="use_diamond_METAL_ID_INDEX">Used Diamond?</label>
                </div>
            </div>
            <div class="col-md-1">
                 <button type="button" class="btn btn-danger btn-sm remove-row">X</button>
            </div>
        </div>

        <!-- Nested Diamond Section -->
        <div class="nested-diamond-section mt-3 p-3 bg-lighter rounded" style="display: none;">
            <h6 class="text-info fw-bold mb-2">Diamond Details</h6>
            <div class="diamond-rows-container">
                <!-- Diamond rows go here -->
            </div>
            <button type="button" class="btn btn-xs btn-outline-info add-diamond-nested mt-2" data-metal-id="METAL_ID" data-metal-index="INDEX">
                + Add Diamond Info
            </button>
        </div>
    </div>
</template>

<!-- Nested Diamond Row Template -->
<template id="nested-diamond-row-template">
    <div class="row align-items-end mb-2 diamond-row border-bottom pb-2">
        <div class="col-md-2">
            <label class="form-label text-xs">Size</label>
            <input type="text" name="metal_configurations[METAL_ID][METAL_INDEX][diamond_info][DIAMOND_INDEX][size]" class="form-control form-control-sm" required>
        </div>
        <div class="col-md-2">
            <label class="form-label text-xs">Color</label>
            <input type="text" name="metal_configurations[METAL_ID][METAL_INDEX][diamond_info][DIAMOND_INDEX][color]" class="form-control form-control-sm" required>
        </div>
        <div class="col-md-2">
            <label class="form-label text-xs">Clarity</label>
            <input type="text" name="metal_configurations[METAL_ID][METAL_INDEX][diamond_info][DIAMOND_INDEX][clarity]" class="form-control form-control-sm" required>
        </div>
        <div class="col-md-2">
            <label class="form-label text-xs">Cut</label>
            <input type="text" name="metal_configurations[METAL_ID][METAL_INDEX][diamond_info][DIAMOND_INDEX][shape]" class="form-control form-control-sm" required>
        </div>
        <div class="col-md-1">
            <label class="form-label text-xs">No.</label>
            <input type="number" name="metal_configurations[METAL_ID][METAL_INDEX][diamond_info][DIAMOND_INDEX][number_of_diamonds]" class="form-control form-control-sm" required>
        </div>
        <div class="col-md-2">
            <label class="form-label text-xs">Total Wt(gm)</label>
            <input type="text" name="metal_configurations[METAL_ID][METAL_INDEX][diamond_info][DIAMOND_INDEX][total_weight]" class="form-control form-control-sm" required>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-danger btn-xs remove-diamond-row">x</button>
        </div>
    </div>
</template>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Metal Configuration
    const metalTemplate = document.getElementById('metal-config-template').innerHTML;
    const diamondRowTemplate = document.getElementById('nested-diamond-row-template').innerHTML;

    // Initialize existing rows (Remove buttons and Diamond functionality)
    document.querySelectorAll('.metal-config-row').forEach(row => {
        // Remove button
        const removeBtn = row.querySelector('.remove-row');
        if(removeBtn) {
            removeBtn.addEventListener('click', function() {
                 if(confirm('Are you sure you want to remove this configuration?')) {
                     row.remove();
                 }
            });
        }

        // Diamond Toggle
        const diamondCheck = row.querySelector('.use-diamond-check');
        const diamondSection = row.querySelector('.nested-diamond-section');
        if(diamondCheck && diamondSection) {
            diamondCheck.addEventListener('change', function() {
                diamondSection.style.display = this.checked ? 'block' : 'none';
            });
        }

        // Add Diamond Nested Btn (for existing rows)
        const addDiamondBtn = row.querySelector('.add-diamond-nested');
        if(addDiamondBtn) {
            addDiamondBtn.addEventListener('click', function() {
                const metalId = this.getAttribute('data-metal-id');
                const metalIndex = this.getAttribute('data-metal-index'); 
                
                const diamondContainer = row.querySelector('.diamond-rows-container');
                // Use a chaotic safety buffer for index
                const diamondIndex = diamondContainer.querySelectorAll('.diamond-row').length + Math.floor(Math.random() * 10000); 

                let diamondHtml = diamondRowTemplate
                    .replace(/METAL_ID/g, metalId)
                    .replace(/METAL_INDEX/g, metalIndex)
                    .replace(/DIAMOND_INDEX/g, diamondIndex);
                
                const dTempDiv = document.createElement('div');
                dTempDiv.innerHTML = diamondHtml;
                const newDiamondRow = dTempDiv.firstElementChild;

                newDiamondRow.querySelector('.remove-diamond-row').addEventListener('click', function() {
                    newDiamondRow.remove();
                });

                diamondContainer.appendChild(newDiamondRow);
            });
        }

        // Remove existing diamond rows
        row.querySelectorAll('.remove-diamond-row').forEach(dBtn => {
            dBtn.addEventListener('click', function() {
                this.closest('.diamond-row').remove();
            });
        });
    });

    // Add NEW Metal Configuration
    document.querySelectorAll('.add-metal-config').forEach(button => {
        button.addEventListener('click', function() {
            const metalId = this.getAttribute('data-metal-id');
            const container = document.getElementById('metal-configs-' + metalId);
            const index = container.querySelectorAll('.metal-config-row').length + Math.floor(Math.random() * 10000); 

            let html = metalTemplate.replace(/METAL_ID/g, metalId).replace(/INDEX/g, index);
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = html;
            
            const newRow = tempDiv.firstElementChild;

            // Handle Remove Metal Row
            newRow.querySelector('.remove-row').addEventListener('click', function() {
                newRow.remove();
            });

            // Handle Diamond Checkbox
            const diamondCheck = newRow.querySelector('.use-diamond-check');
            const diamondSection = newRow.querySelector('.nested-diamond-section');
            diamondCheck.addEventListener('change', function() {
                diamondSection.style.display = this.checked ? 'block' : 'none';
            });

            // Handle Add Diamond Row (Nested)
            const addDiamondBtn = newRow.querySelector('.add-diamond-nested');
            
            // We need to set data attributes on the newly added button for consistency
            addDiamondBtn.setAttribute('data-metal-id', metalId);
            addDiamondBtn.setAttribute('data-metal-index', index);

            addDiamondBtn.addEventListener('click', function() {
                const diamondContainer = newRow.querySelector('.diamond-rows-container');
                const diamondIndex = diamondContainer.querySelectorAll('.diamond-row').length + Math.floor(Math.random() * 10000);

                let diamondHtml = diamondRowTemplate
                    .replace(/METAL_ID/g, metalId)
                    .replace(/METAL_INDEX/g, index)
                    .replace(/DIAMOND_INDEX/g, diamondIndex);
                
                const dTempDiv = document.createElement('div');
                dTempDiv.innerHTML = diamondHtml;
                const newDiamondRow = dTempDiv.firstElementChild;

                // Handle Remove Diamond Row
                newDiamondRow.querySelector('.remove-diamond-row').addEventListener('click', function() {
                    newDiamondRow.remove();
                });

                diamondContainer.appendChild(newDiamondRow);
            });

            container.appendChild(newRow);
        });
    });
});
</script>
@endpush