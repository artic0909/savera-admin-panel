@extends('frontend.layouts.app')

@section('title', 'My Wishlist')

@section('content')
    <section style="padding: 60px 0; background: #f8f8f8;">
        <div class="wrapper">
            <h2 style="margin-bottom: 30px; text-align: center;">My Wishlist</h2>

            @if ($wishlistItems->isEmpty())
                <div style="background: white; padding: 60px; text-align: center; border-radius: 10px;">
                    <h3>Your wishlist is empty</h3>
                    <p>Save your favorite products here!</p>
                    <a href="{{ route('home') }}" class="btn"
                        style="display: inline-block; margin-top: 20px; padding: 12px 30px; background: #000; color: white; text-decoration: none; border-radius: 5px;">Browse
                        Products</a>
                </div>
            @else
                <div class="row">
                    @foreach ($wishlistItems as $item)
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12" id="wishlist-item-{{ $item->id }}">
                            <div class="product-card"
                                style="background: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; text-align: center; position: relative;">
                                <button onclick="removeFromWishlist({{ $item->id }})"
                                    style="position: absolute; top: 10px; right: 10px; background: #ff4444; color: white; border: none; width: 30px; height: 30px; border-radius: 50%; cursor: pointer; font-size: 18px;">×</button>

                                <a href="{{ route('product.show', $item->product_id) }}">
                                    <img src="{{ asset('storage/' . $item->product->main_image) }}"
                                        alt="{{ $item->product->product_name }}"
                                        style="width: 100%; height: 200px; object-fit: cover; border-radius: 5px; margin-bottom: 15px;">
                                </a>

                                <h5 style="margin-bottom: 10px; min-height: 40px;">
                                    <a href="{{ route('product.show', $item->product_id) }}"
                                        style="color: #000; text-decoration: none;">
                                        {{ $item->product->product_name }}
                                    </a>
                                </h5>

                                <p style="font-weight: bold; font-size: 18px; margin-bottom: 15px;">
                                    {{ $item->product->display_price }}</p>

                                <button onclick="moveToCart({{ $item->id }})"
                                    style="width: 100%; padding: 12px; background: #000; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">Move
                                    to Cart</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <script>
        function removeFromWishlist(wishlistId) {
            if (!confirm('Remove this item from wishlist?')) return;

            fetch(`/wishlist/remove/${wishlistId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`wishlist-item-${wishlistId}`).remove();
                        location.reload();
                    }
                });
        }

        function moveToCart(wishlistId) {
            fetch(`/wishlist/move-to-cart/${wishlistId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Product moved to cart!');
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                });
        }
    </script>
@endsection
