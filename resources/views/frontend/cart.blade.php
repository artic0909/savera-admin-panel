@extends('frontend.layouts.app')

@section('title', 'Shopping Cart')

@section('content')
    <section style="padding: 60px 0; ">
        <div class="wrapper">
            <h2 style="margin-bottom: 30px; text-align: center;">Shopping Cart</h2>

            @if ($cartItems->isEmpty())
                <div style="background: white; padding: 60px; text-align: center; border-radius: 10px;">
                    <img src="{{ asset('assets/images/empty-cart.png') }}" alt="Empty Cart"
                        style="max-width: 200px; margin-bottom: 20px;" onerror="this.style.display='none'">
                    <h3>Your cart is empty</h3>
                    <p>Add some products to get started!</p>
                    <a href="{{ route('home') }}" class="btn"
                        style="display: inline-block; margin-top: 20px; padding: 12px 30px; background: #000; color: white; text-decoration: none; border-radius: 5px;">Continue
                        Shopping</a>
                </div>
            @else
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-12">
                        <div class="cart-items" style="background: white; padding: 20px; border-radius: 10px;">
                            @foreach ($cartItems as $item)
                                <div class="cart-item" id="cart-item-{{ $item->id }}"
                                    style="display: flex; align-items: center; gap: 20px; padding: 20px; border-bottom: 1px solid #eee; position: relative;">
                                    <img src="{{ asset('storage/' . $item->product->main_image) }}"
                                        alt="{{ $item->product->product_name }}"
                                        style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px;">

                                    <div style="flex: 1;">
                                        <h5 style="margin-bottom: 10px;">
                                            <a href="{{ route('product.show', $item->product_id) }}"
                                                style="color: #000; text-decoration: none;">
                                                {{ $item->product->product_name }}
                                            </a>
                                        </h5>
                                        <p style="color: #666; font-size: 14px; margin-bottom: 5px;">
                                            @if ($item->metal_configuration)
                                                Material: {{ $item->metal_configuration['material_name'] ?? 'N/A' }} |
                                                Size: {{ $item->metal_configuration['size_name'] ?? 'N/A' }}
                                                @if (isset($item->metal_configuration['color_name']))
                                                    | Color: {{ $item->metal_configuration['color_name'] }}
                                                @endif
                                            @endif
                                        </p>
                                        <p style="font-weight: bold; font-size: 18px; color: #000;">Rs.
                                            {{ number_format($item->price_at_addition, 2) }}</p>
                                    </div>

                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <button onclick="updateQuantity({{ $item->id }}, -1)"
                                            style="width: 30px; height: 30px; border: 1px solid #ddd; background: white; cursor: pointer; border-radius: 3px;">-</button>
                                        <input type="number" id="quantity-{{ $item->id }}"
                                            value="{{ $item->quantity }}" min="1" readonly
                                            style="width: 50px; text-align: center; border: 1px solid #ddd; padding: 5px; border-radius: 3px;">
                                        <button onclick="updateQuantity({{ $item->id }}, 1)"
                                            style="width: 30px; height: 30px; border: 1px solid #ddd; background: white; cursor: pointer; border-radius: 3px;">+</button>
                                    </div>

                                    <div style="text-align: right;">
                                        <p style="font-weight: bold; font-size: 18px; margin-bottom: 10px;"
                                            id="subtotal-{{ $item->id }}">Rs. {{ number_format($item->subtotal, 2) }}
                                        </p>
                                        <button onclick="removeItem({{ $item->id }})"
                                            style="background: #ff4444; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer;">Remove</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-12">
                        <div class="cart-summary"
                            style="background: white; padding: 30px; border-radius: 10px; position: sticky; top: 20px;">
                            <h4 style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #eee;">Order
                                Summary</h4>

                            <div style="margin-bottom: 15px; display: flex; justify-content: space-between;">
                                <span>Subtotal:</span>
                                <span id="cart-subtotal">Rs. {{ number_format($subtotal, 2) }}</span>
                            </div>

                            <div style="margin-bottom: 15px; display: flex; justify-content: space-between;">
                                <span>Tax (3%):</span>
                                <span id="cart-tax">Rs. {{ number_format($tax, 2) }}</span>
                            </div>

                            <div style="margin-bottom: 20px; display: flex; justify-content: space-between;">
                                <span>Shipping:</span>
                                <span style="color: green;" id="cart-shipping">FREE</span>
                            </div>

                            <div
                                style="padding-top: 15px; border-top: 2px solid #eee; margin-bottom: 20px; display: flex; justify-content: space-between;">
                                <strong style="font-size: 18px;">Total:</strong>
                                <strong style="font-size: 18px;" id="cart-total">Rs.
                                    {{ number_format($total, 2) }}</strong>
                            </div>

                            <a href="{{ route('checkout.index') }}"
                                style="display: block; width: 100%; padding: 15px; background: #000; color: white; text-align: center; text-decoration: none; border-radius: 5px; font-weight: bold; margin-bottom: 10px;">Proceed
                                to Checkout</a>

                            <a href="{{ route('home') }}"
                                style="display: block; width: 100%; padding: 15px; background: white; color: #000; text-align: center; text-decoration: none; border-radius: 5px; border: 1px solid #ddd;">Continue
                                Shopping</a>
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
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`subtotal-${cartId}`).textContent = 'Rs. ' + parseFloat(data.subtotal)
                            .toLocaleString('en-IN', {
                                minimumFractionDigits: 2
                            });
                        location.reload(); // Reload to update totals
                    }
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
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
        }
    </script>
@endsection
