@extends('frontend.layouts.app')

@section('title', 'Shopping Cart')

@section('content')
    <section class="cart-section">
        <div class="wrapper">
            <h2 class="cart-title">Shopping Cart</h2>

            @if ($cartItems->isEmpty())
                <div class="empty-cart">
                    <img src="{{ asset('assets/images/empty-cart.png') }}" alt="Empty Cart"
                        onerror="this.style.display='none'">
                    <h3>Your cart is empty</h3>
                    <p>Add some products to get started!</p>
                    <a href="{{ route('home') }}" class="btn-dark">Continue Shopping</a>
                </div>
            @else
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-12">
                        <div class="cart-items">
                            @foreach ($cartItems as $item)
                                <div class="cart-item" id="cart-item-{{ $item->id }}">
                                    <img src="{{ asset('storage/' . $item->product->main_image) }}"
                                        alt="{{ $item->product->product_name }}">

                                    <div class="cart-details">
                                        <h5>
                                            <a href="{{ route('product.show', $item->product->slug) }}">
                                                {{ $item->product->product_name }}
                                            </a>
                                        </h5>

                                        <p class="cart-meta">
                                            @if ($item->metal_configuration)
                                                Material: {{ $item->metal_configuration['material_name'] ?? 'N/A' }} |
                                                Size: {{ $item->metal_configuration['size_name'] ?? 'N/A' }}
                                                @if (isset($item->metal_configuration['color_name']))
                                                    | Color: {{ $item->metal_configuration['color_name'] }}
                                                @endif
                                            @endif
                                        </p>

                                        <p class="cart-price">
                                            Rs. {{ number_format($item->price_at_addition, 2) }}
                                        </p>
                                    </div>

                                    <div class="qty-box">
                                        <button class="qty-btn"
                                            onclick="updateQuantity({{ $item->id }}, -1)">-</button>
                                        <input type="number" id="quantity-{{ $item->id }}" class="qty-input"
                                            value="{{ $item->quantity }}" min="1" readonly>
                                        <button class="qty-btn" onclick="updateQuantity({{ $item->id }}, 1)">+</button>
                                    </div>

                                    <div class="cart-actions">
                                        <p class="cart-subtotal" id="subtotal-{{ $item->id }}">
                                            Rs. {{ number_format($item->subtotal, 2) }}
                                        </p>
                                        <button class="remove-btn" onclick="removeItem({{ $item->id }})">
                                            Remove
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-12">
                        <div class="cart-summary">
                            <h4>Order Summary</h4>

                            <div class="summary-row">
                                <span>Subtotal:</span>
                                <span id="cart-subtotal">Rs. {{ number_format($subtotal, 2) }}</span>
                            </div>

                            <div class="summary-row">
                                <span>Tax (3%):</span>
                                <span id="cart-tax">Rs. {{ number_format($tax, 2) }}</span>
                            </div>

                            <div class="summary-row">
                                <span>Shipping:</span>
                                <span class="free" id="cart-shipping">FREE</span>
                            </div>

                            <div class="summary-total">
                                <strong>Total:</strong>
                                <strong id="cart-total">
                                    Rs. {{ number_format($total, 2) }}
                                </strong>
                            </div>

                            <a href="{{ route('checkout.index') }}" class="btn-dark">
                                Proceed to Checkout
                            </a>

                            <a href="{{ route('home') }}" class="btn-outline">
                                Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <script>
        function updateQuantity(cartId, change) {
            const input = document.getElementById(`quantity-${cartId}`);
            let newQuantity = parseInt(input.value) + change;

            if (newQuantity < 1) return;

            input.value = newQuantity;

            fetch(`/cart/update/${cartId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        quantity: newQuantity
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) location.reload();
                });
        }

        function removeItem(cartId) {
            if (!confirm('Are you sure you want to remove this item?')) return;

            fetch(`/cart/remove/${cartId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) location.reload();
                });
        }
    </script>
@endsection
