@extends('frontend.layouts.app')

@section('title', 'My Wishlist')

@section('content')
    <style>
        .wishlist-section {
            padding: 60px 0;
            background-color: #f9f9f9;
            min-height: 60vh;
        }

        .wishlist-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .wishlist-header h2 {
            font-size: 2.5rem;
            color: #333;
            font-weight: 600;
        }

        .empty-wishlist {
            background: white;
            padding: 60px;
            text-align: center;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            max-width: 600px;
            margin: 0 auto;
        }

        .empty-wishlist h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #333;
        }

        .empty-wishlist p {
            color: #777;
            margin-bottom: 25px;
        }

        .btn-browse {
            display: inline-block;
            padding: 12px 30px;
            background: #000;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: background 0.3s;
        }

        .btn-browse:hover {
            background: #333;
            color: #fff;
        }

        /* Responsive Grid Layout */
        .wishlist-grid {
            display: grid;
            /* Default: 1 column on very small screens */
            grid-template-columns: 1fr;
            gap: 25px;
        }

        /* Small devices (landscape phones, 576px and up) */
        @media (min-width: 576px) {
            .wishlist-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* Medium devices (tablets, 768px and up) */
        @media (min-width: 768px) {
            .wishlist-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        /* Large devices (desktops, 992px and up) */
        @media (min-width: 992px) {
            .wishlist-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        .wishlist-card {
            background: white;
            padding: 15px;
            border-radius: 12px;
            text-align: center;
            position: relative;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .wishlist-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .btn-remove {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(255, 68, 68, 0.1);
            color: #ff4444;
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: all 0.2s;
            z-index: 2;
        }

        .btn-remove:hover {
            background: #ff4444;
            color: white;
        }

        .product-img-link {
            display: block;
            margin-bottom: 15px;
            border-radius: 8px;
            overflow: hidden;
            /* Aspect ratio container if needed, but object-fit helps */
        }

        .product-img {
            width: 100%;
            height: 250px;
            /* Fixed height for consistency */
            object-fit: cover;
            border-radius: 8px;
            transition: transform 0.3s;
        }

        .product-img:hover {
            transform: scale(1.05);
        }

        .product-title {
            font-size: 1.1rem;
            margin-bottom: 10px;
            min-height: 50px;
            /* Ensure alignment even with different title lengths */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-title a {
            color: #333;
            text-decoration: none;
            transition: color 0.2s;
        }

        .product-title a:hover {
            color: #ebb417;
            /* Savera gold accent? or just standard hover */
        }

        .product-price {
            font-weight: 700;
            font-size: 1.2rem;
            color: #000;
            margin-bottom: 15px;
            flex-grow: 1;
            /* Pushes button to bottom */
        }

        .btn-move-cart {
            width: 100%;
            padding: 12px;
            background: #000;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s;
            margin-top: auto;
        }

        .btn-move-cart:hover {
            background: #333;
        }
    </style>

    <section class="wishlist-section">
        <div class="wrapper">
            <div class="wishlist-header">
                <h2>My Wishlist</h2>
            </div>

            @if ($wishlistItems->isEmpty())
                <div class="empty-wishlist">
                    <h3>Your wishlist is empty</h3>
                    <p>Save your favorite products here to review them anytime!</p>
                    <a href="{{ route('home') }}" class="btn-browse">Browse Products</a>
                </div>
            @else
                <div class="wishlist-grid">
                    @foreach ($wishlistItems as $item)
                        <div class="wishlist-item" id="wishlist-item-{{ $item->id }}">
                            <div class="wishlist-card">
                                <button onclick="removeFromWishlist({{ $item->id }})" class="btn-remove"
                                    title="Remove from Wishlist">&times;</button>

                                <a href="{{ route('product.show', $item->product->slug) }}" class="product-img-link">
                                    <img src="{{ asset('storage/' . $item->product->main_image) }}"
                                        alt="{{ $item->product->product_name }}" class="product-img">
                                </a>

                                <h5 class="product-title">
                                    <a href="{{ route('product.show', $item->product->slug) }}">
                                        {{ $item->product->product_name }}
                                    </a>
                                </h5>

                                <p class="product-price">
                                    {{ $item->product->display_price }}
                                </p>

                                <button onclick="moveToCart({{ $item->id }})" class="btn-move-cart">Move to Cart</button>
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
                        const item = document.getElementById(`wishlist-item-${wishlistId}`);
                        // Fade out effect properly
                        item.style.transition = 'all 0.3s ease';
                        item.style.opacity = '0';
                        item.style.transform = 'scale(0.9)';

                        setTimeout(() => {
                            item.remove();
                            // If grid is empty, reload to show empty state
                            const grid = document.querySelector('.wishlist-grid');
                            if (!grid || grid.children.length === 0) {
                                location.reload();
                            }
                        }, 300);
                    }
                })
                .catch(err => console.error(err));
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
                        // Optional: Show a nicer toast instead of alert
                        alert('Product moved to cart!');
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(err => console.error(err));
        }
    </script>
@endsection