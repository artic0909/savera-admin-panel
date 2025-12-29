@extends('frontend.layouts.app')

@section('title', 'My Wishlist')

@section('content')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/css/pages/wishlist.css') }}">
    @endpush

    @push('scripts')
        <script src="{{ asset('assets/js/pages/wishlist.js') }}"></script>
    @endpush

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

                                <button onclick="moveToCart({{ $item->id }})" class="btn-move-cart">Move to
                                    Cart</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- Custom Confirmation Modal -->
    <div id="deleteModal" class="confirm-modal">
        <div class="modal-content">
            <div class="modal-icon">
                <i class="fi fi-rr-trash"></i>
            </div>
            <h3 class="modal-title">Remove Item?</h3>
            <p class="modal-text">Are you sure you want to remove this product from your wishlist?</p>
            <div class="modal-actions">
                <button onclick="closeModal()" class="btn-cancel">Cancel</button>
                <button id="confirmDeleteBtn" class="btn-confirm">Yes, Remove</button>
            </div>
        </div>
    </div>

@endsection
