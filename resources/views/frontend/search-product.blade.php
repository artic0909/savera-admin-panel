@extends('frontend.layouts.app')

@section('title', 'Search Results')

@section('content')
    <section class="search-section" style="padding: 50px 0;">
        <div class="wrapper">
            <div class="search-container" style="display: flex; justify-content: center; margin-bottom: 30px;">
                <form action="{{ route('search-product') }}" method="GET"
                    style="display: flex; gap: 10px; width: 100%; max-width: 600px;">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search for products..."
                        style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    <button type="submit"
                        style="padding: 10px 20px; background-color: #333; color: #fff; border: none; border-radius: 5px; cursor: pointer;">Search</button>
                </form>
            </div>

            <div class="product-list-container">
                <div class="product-list"
                    style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
                    @include('frontend.partials.product_loop', ['products' => $products])
                </div>
            </div>
        </div>
    </section>
@endsection