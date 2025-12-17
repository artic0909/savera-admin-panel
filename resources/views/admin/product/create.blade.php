@extends('admin.layouts.app')

@section('title', 'Add Product')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Product /</span> Add Product
    </h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Product Details</h5>

                <div class="card-body">
                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- ================= BASIC DETAILS ================= --}}
                        <div class="row">

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Product Name</label>
                                <input type="text" name="product_name" class="form-control" required>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Category</label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Delivery Time (Days)</label>
                                <input type="text" name="delivery_time" class="form-control">
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Available Pincodes</label>
                                <select name="available_pincodes[]" class="form-select" multiple>
                                    @foreach($pincodes as $pincode)
                                    <option value="{{ $pincode->id }}">{{ $pincode->code }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Colors</label>
                                <select name="colors[]" class="form-select" multiple>
                                    @foreach($colors as $color)
                                    <option value="{{ $color->id }}">{{ $color->color_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Main Image</label>
                                <input type="file" name="main_image" class="form-control" required>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Additional Images</label>
                                <input type="file" name="additional_images[]" class="form-control" multiple>
                            </div>

                        </div>

                        <hr>

                        {{-- ================= METAL CONFIGURATION ================= --}}
                        <h5 class="mb-3">Metal Configuration</h5>

                        <div id="metal-configs"></div>

                        <button type="button" class="btn btn-outline-primary mb-3" id="add-metal-config">
                            + Add Metal Configuration
                        </button>

                        <hr>

                        <button type="submit" class="btn btn-primary">Create Product</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ================= TEMPLATES ================= --}}

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
                        @foreach($materials as $material)
                        <option value="{{ $material->id }}">{{ $material->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Size</label>
                    <select name="metal_configurations[INDEX][size_id]" class="form-select" required>
                        @foreach($sizes as $size)
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
                    <input type="text" name="metal_configurations[INDEX][gross_weight_product]" class="form-control">
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
            <input type="text" name="metal_configurations[METAL][diamond_info][DIAMOND][size]" placeholder="Size" class="form-control form-control-sm">
        </div>
        <div class="col-md-2">
            <input type="text" name="metal_configurations[METAL][diamond_info][DIAMOND][color]" placeholder="Color" class="form-control form-control-sm">
        </div>
        <div class="col-md-2">
            <input type="text" name="metal_configurations[METAL][diamond_info][DIAMOND][clarity]" placeholder="Clarity" class="form-control form-control-sm">
        </div>
        <div class="col-md-2">
            <input type="text" name="metal_configurations[METAL][diamond_info][DIAMOND][shape]" placeholder="Cut" class="form-control form-control-sm">
        </div>
        <div class="col-md-2">
            <input type="number" name="metal_configurations[METAL][diamond_info][DIAMOND][number_of_diamonds]" placeholder="No." class="form-control form-control-sm">
        </div>
        <div class="col-md-2">
             <input type="text" name="metal_configurations[METAL][diamond_info][DIAMOND][total_weight]" placeholder="Total Wt(g)" class="form-control form-control-sm">
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-danger btn-sm remove-diamond">x</button>
        </div>
    </div>
</template>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const metalContainer = document.getElementById('metal-configs');
        const metalTemplate = document.getElementById('metal-config-template').innerHTML;
        const diamondTemplate = document.getElementById('diamond-template').innerHTML;

        document.getElementById('add-metal-config').addEventListener('click', function() {

            const index = metalContainer.children.length;
            const newId = 'mc_' + Date.now();
            let html = metalTemplate.replace(/INDEX/g, index).replace(/NEW_ID/g, newId);

            const temp = document.createElement('div');
            temp.innerHTML = html;
            const metalRow = temp.firstElementChild;

            metalRow.querySelector('.remove-metal').onclick = () => metalRow.remove();

            const checkbox = metalRow.querySelector('.use-diamond');
            const diamondSection = metalRow.querySelector('.diamond-section');
            const diamondContainer = metalRow.querySelector('.diamond-rows');

            checkbox.onchange = () => {
                diamondSection.style.display = checkbox.checked ? 'block' : 'none';
            };

            metalRow.querySelector('.add-diamond').onclick = () => {
                const dIndex = diamondContainer.children.length;
                let dHtml = diamondTemplate
                    .replace(/METAL/g, index)
                    .replace(/DIAMOND/g, dIndex);

                const dTemp = document.createElement('div');
                dTemp.innerHTML = dHtml;

                const dRow = dTemp.firstElementChild;
                dRow.querySelector('.remove-diamond').onclick = () => dRow.remove();

                diamondContainer.appendChild(dRow);
            };

            metalContainer.appendChild(metalRow);
        });

    });
</script>
@endpush