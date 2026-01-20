@extends('frontend.layouts.app')

@section('title', $collection->name)

@section('content')

    <!-- inner banner -->
    <section>
        <div class="inner-banner-sec"
            style="background-image: url({{ $collection->banner_image ? asset('storage/' . $collection->banner_image) : asset('assets/images/bg-carrasol-1.webp') }})">

        </div>
    </section>
    <!-- /inner banner -->

    <!-- cetagory sec -->
    <section>
        <div class="cetagory-sec">
            <div class="wrapper" style="margin-top: 50px;">
                <div class="product-list" id="product-list-container">
                    @include('frontend.partials.product_loop', [
                        'products' => $products,
                        'itemClass' => 'col-lg-3 col-md-3 col-sm-4 col-6 cetagory-item product-item',
                        'showName' => true,
                    ])
                </div>
                <div id="pagination-container">
                    {{ $products->links('frontend.partials.custom_pagination') }}
                </div>
            </div>
        </div>
    </section>
    <!-- /cetagory sec -->
@endsection
