@extends('admin.layouts.app')

@section('title', 'Add Product')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Product /</span> Add Product</h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Product Details</h5>
                <div class="card-body">
                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <!-- Basic Fields -->
                            <div class="mb-3 col-md-6">
                                <label for="product_name" class="form-label">Product Name</label>
                                <input class="form-control" type="text" id="product_name" name="product_name" value="{{ old('product_name') }}" required />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="category_id" class="form-label">Category</label>
                                <select id="category_id" name="category_id" class="form-select" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="delivery_time" class="form-label">Delivery Time (in days)</label>
                                <input class="form-control" type="text" id="delivery_time" name="delivery_time" value="{{ old('delivery_time') }}" required />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="available_pincodes" class="form-label">Available Pincodes</label>
                                <select id="available_pincodes" name="available_pincodes[]" class="form-select" multiple>
                                    @foreach($pincodes as $pincode)
                                    <option value="{{ $pincode->id }}">{{ $pincode->code }}</option>
                                    @endforeach
                                </select>
                                <small>Hold Ctrl (Windows) or Command (Mac) to select multiple.</small>
                            </div>
                             <div class="mb-3 col-md-6">
                                <label for="colors" class="form-label">Colors</label>
                                <select id="colors" name="colors[]" class="form-select" multiple>
                                    @foreach($colors as $color)
                                    <option value="{{ $color->id }}">{{ $color->color_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="main_image" class="form-label">Main Image</label>
                                <input class="form-control" type="file" id="main_image" name="main_image" required />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="additional_images" class="form-label">Additional Images</label>
                                <input class="form-control" type="file" id="additional_images" name="additional_images[]" multiple />
                            </div>
                        </div>

                        <hr>

                        <!-- Metal Configuration -->
                        <h5 class="mb-3">Metal Configuration</h5>
                        <div id="metal-container">
                            @foreach($metals as $metal)
                            <div class="card mb-3 border border-secondary metal-section" data-metal-id="{{ $metal->id }}">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">{{ $metal->metal_name }} ({{ $metal->metal_purity }})</h6>
                                </div>
                                <div class="card-body pt-3">
                                    <div class="metal-configs-wrapper" id="metal-configs-{{ $metal->id }}">
                                        <!-- Configs will be added here -->
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
                            <button type="submit" class="btn btn-primary">Create Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

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

<!-- Template for Diamond Row -->
<template id="diamond-row-template">
    <div class="row align-items-end mb-3 diamond-row border-bottom pb-3">
        <div class="col-md-2">
            <label class="form-label">Size</label>
            <input type="text" name="diamond_gemstone_info[INDEX][size]" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label class="form-label">Color</label>
            <input type="text" name="diamond_gemstone_info[INDEX][color]" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label class="form-label">Clarity</label>
            <input type="text" name="diamond_gemstone_info[INDEX][clarity]" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label class="form-label">Cut</label>
            <input type="text" name="diamond_gemstone_info[INDEX][shape]" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label class="form-label">No. of Diamonds</label>
            <input type="number" name="diamond_gemstone_info[INDEX][number_of_diamonds]" class="form-control" required>
        </div>
        <div class="col-md-1">
            <label class="form-label">Total Wt (gm)</label>
            <input type="text" name="diamond_gemstone_info[INDEX][total_weight]" class="form-control" required>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-danger btn-sm remove-row">X</button>
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

    document.querySelectorAll('.add-metal-config').forEach(button => {
        button.addEventListener('click', function() {
            const metalId = this.getAttribute('data-metal-id');
            const container = document.getElementById('metal-configs-' + metalId);
            const index = container.children.length; // Metal Config Index
            
            let html = metalTemplate.replace(/METAL_ID/g, metalId).replace(/INDEX/g, index);
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = html;
            
            const newRow = tempDiv.firstElementChild;

            // Handle Remove Metal Row
            newRow.querySelector('.remove-row').addEventListener('click', function() {
                // Confirm or just remove? Just remove for now.
                newRow.remove();
            });

            // Handle Diamond Checkbox
            const diamondCheck = newRow.querySelector('.use-diamond-check');
            const diamondSection = newRow.querySelector('.nested-diamond-section');
            diamondCheck.addEventListener('change', function() {
                diamondSection.style.display = this.checked ? 'block' : 'none';
                // If unchecked, maybe clear inputs? For now, we leave them.
                // But we should probably disable required attribute if hidden to avoid validation error.
                // Simpler approach: user ensures data is correct.
            });

            // Handle Add Diamond Row (Nested)
            const addDiamondBtn = newRow.querySelector('.add-diamond-nested');
            const diamondContainer = newRow.querySelector('.diamond-rows-container');

            addDiamondBtn.addEventListener('click', function() {
                const metalIndex = index; // The index of this metal config
                const diamondIndex = diamondContainer.children.length;

                let diamondHtml = diamondRowTemplate
                    .replace(/METAL_ID/g, metalId)
                    .replace(/METAL_INDEX/g, metalIndex)
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
