@extends('frontend.layouts.app')

@section('title', 'Category')

@section('content')

    <!-- inner banner -->
    <section>
        <div class="inner-banner-sec">
            <div class="wrapper">
                <div class="inner-banner-div">
                    <div class="inner-banner-left">
                        <h1>
                            Flat Rs. 50 OFF <br> on Earrings!
                        </h1>
                        <p>
                            Use Code: WLCM200
                        </p>
                    </div>
                    <div class="inner-banner-right">
                        <img src="{{ asset('assets/images/banner-3.webp') }}" alt="...">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /inner banner -->

    <!-- cetagory sec -->
    <section>
        <div class="cetagory-sec">
            <div class="cetagory-nav">
                <div class="wrapper dropdown">
                    <button class="dropbtn">
                        Sort by :
                    </button>
                    <div class="dropdown-content">
                        <ul class="dropbtn-ul-1">
                            {{-- Price Sort --}}
                            <li class="dropbtn-li-1 dropdown1">
                                <button class="dropbtn1">
                                    Price
                                </button>
                                <div class="dropdown-content1">
                                    <ul class="dropbtn-ul-2">
                                        <li class="dropbtn-li-2">
                                            <label class="d-flex align-items-center gap-2" style="cursor: pointer;">
                                                <input type="checkbox" class="filter-checkbox sort-checkbox"
                                                    data-type="sort" value="price_asc">
                                                Low to High
                                            </label>
                                        </li>
                                        <li class="dropbtn-li-2">
                                            <label class="d-flex align-items-center gap-2" style="cursor: pointer;">
                                                <input type="checkbox" class="filter-checkbox sort-checkbox"
                                                    data-type="sort" value="price_desc">
                                                High to Low
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            {{-- Metal Filter --}}
                            <li class="dropbtn-li-1 dropdown1">
                                <button class="dropbtn1">
                                    Metal
                                </button>
                                <div class="dropdown-content1">
                                    <ul class="dropbtn-ul-2">
                                        @foreach ($materials as $material)
                                            <li class="dropbtn-li-2">
                                                <label class="d-flex align-items-center gap-2" style="cursor: pointer;">
                                                    <input type="checkbox" class="filter-checkbox" data-type="metal"
                                                        value="{{ $material->id }}">
                                                    {{ $material->name }}
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </li>
                            {{-- Shape Filter --}}
                            <li class="dropbtn-li-1 dropdown1">
                                <button class="dropbtn1">
                                    Shape
                                </button>
                                <div class="dropdown-content1">
                                    <ul class="dropbtn-ul-2">
                                        @foreach ($shapes as $shape)
                                            <li class="dropbtn-li-2">
                                                <label class="d-flex align-items-center gap-2" style="cursor: pointer;">
                                                    <input type="checkbox" class="filter-checkbox" data-type="shape"
                                                        value="{{ $shape->shape_name }}">
                                                    {{ $shape->shape_name }}
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </li>
                            {{-- Style Filter --}}
                            <li class="dropbtn-li-1 dropdown1">
                                <button class="dropbtn1">
                                    Style
                                </button>
                                <div class="dropdown-content1">
                                    <ul class="dropbtn-ul-2">
                                        @foreach ($styles as $style)
                                            <li class="dropbtn-li-2">
                                                <label class="d-flex align-items-center gap-2" style="cursor: pointer;">
                                                    <input type="checkbox" class="filter-checkbox" data-type="style"
                                                        value="{{ $style->style_name }}">
                                                    {{ $style->style_name }}
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Hidden Inputs for Filters (Optional now since we read checkboxes directly, but keeping for logic structure) --}}
            <input type="hidden" id="category_id" value="{{ $category->id }}">

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const checkboxes = document.querySelectorAll('.filter-checkbox');

                    checkboxes.forEach(chk => {
                        chk.addEventListener('change', function() {
                            // Sort Logic: Mutually exclusive
                            if (this.classList.contains('sort-checkbox') && this.checked) {
                                document.querySelectorAll('.sort-checkbox').forEach(c => {
                                    if (c !== this) c.checked = false;
                                });
                            }

                            applyFilters();
                        });
                    });

                    function applyFilters() {
                        const categoryId = document.getElementById('category_id').value;

                        // Collect values
                        const getValues = (type) => {
                            return Array.from(document.querySelectorAll(
                                    `.filter-checkbox[data-type="${type}"]:checked`))
                                .map(cb => cb.value)
                                .join(',');
                        };

                        const sort = getValues('sort');
                        const metal = getValues('metal');
                        const shape = getValues('shape');
                        const style = getValues('style');

                        const container = document.getElementById('product-list-container');

                        if (container) {
                            container.style.opacity = '0.5';

                            const params = new URLSearchParams({
                                category_id: categoryId,
                                sort: sort,
                                metal: metal, // Now sends "1,2" etc
                                shape: shape,
                                style: style
                            });

                            fetch(`{{ route('ajax.products') }}?${params.toString()}`)
                                .then(response => response.json())
                                .then(data => {
                                    container.innerHTML = data.html;
                                    container.style.opacity = '1';
                                });
                        }
                    }
                });
            </script>
            <div class="wrapper" style="margin-top: 50px;">
                <div class="product-list" id="product-list-container">
                    @include('frontend.partials.product_loop', [
                        'products' => $products,
                        'itemClass' => 'col-lg-3 col-md-3 col-sm-4 col-6 cetagory-item product-item',
                        'showName' => true,
                    ])
                </div>
            </div>
        </div>
    </section>
    <!-- /cetagory sec -->
@endsection
