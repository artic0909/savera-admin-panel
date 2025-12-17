@extends('frontend.layouts.app')

@section('title', 'Checkout')

@section('content')
    <section style="padding: 60px 0; background: #f8f8f8;">
        <div class="wrapper">
            <h2 style="margin-bottom: 30px; text-align: center;">Checkout</h2>

            <form action="{{ route('checkout.placeOrder') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-lg-7 col-md-7 col-12">
                        <div class="checkout-form"
                            style="background: white; padding: 30px; border-radius: 10px; margin-bottom: 20px;">
                            <h4 style="margin-bottom: 20px; border-bottom: 2px solid #eee; padding-bottom: 10px;">Shipping
                                Address</h4>

                            <div class="row">
                                <div class="col-md-6" style="margin-bottom: 15px;">
                                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Full Name
                                        *</label>
                                    <input type="text" name="shipping_full_name"
                                        value="{{ old('shipping_full_name', $customer->name ?? '') }}" required
                                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                                    @error('shipping_full_name')
                                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6" style="margin-bottom: 15px;">
                                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Phone *</label>
                                    <input type="text" name="shipping_phone"
                                        value="{{ old('shipping_phone', $customer->phone ?? '') }}" required
                                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                                    @error('shipping_phone')
                                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Address Line 1
                                    *</label>
                                <input type="text" name="shipping_address_line1"
                                    value="{{ old('shipping_address_line1') }}" required
                                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                                @error('shipping_address_line1')
                                    <span style="color: red; font-size: 12px;">{{ $message }}</span>
                                @enderror
                            </div>

                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Address Line 2</label>
                                <input type="text" name="shipping_address_line2"
                                    value="{{ old('shipping_address_line2') }}"
                                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                            </div>

                            <div class="row">
                                <div class="col-md-6" style="margin-bottom: 15px;">
                                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">City *</label>
                                    <input type="text" name="shipping_city" value="{{ old('shipping_city') }}" required
                                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                                    @error('shipping_city')
                                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6" style="margin-bottom: 15px;">
                                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">State *</label>
                                    <input type="text" name="shipping_state" value="{{ old('shipping_state') }}"
                                        required
                                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                                    @error('shipping_state')
                                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6" style="margin-bottom: 15px;">
                                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Postal Code
                                        *</label>
                                    <input type="text" name="shipping_postal_code"
                                        value="{{ old('shipping_postal_code') }}" required
                                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                                    @error('shipping_postal_code')
                                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6" style="margin-bottom: 15px;">
                                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Country *</label>
                                    <input type="text" name="shipping_country"
                                        value="{{ old('shipping_country', 'India') }}" required
                                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                                    @error('shipping_country')
                                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div style="margin-bottom: 15px;">
                                <label style="display: flex; align-items: center; gap: 10px;">
                                    <input type="checkbox" name="save_address" value="1"
                                        {{ old('save_address') ? 'checked' : '' }}>
                                    <span>Save this address for future orders</span>
                                </label>
                            </div>

                            <h4 style="margin: 30px 0 20px; border-bottom: 2px solid #eee; padding-bottom: 10px;">Payment
                                Method</h4>

                            <div style="margin-bottom: 15px;">
                                <label
                                    style="display: flex; align-items: center; gap: 10px; padding: 15px; border: 2px solid #ddd; border-radius: 5px; cursor: pointer;">
                                    <input type="radio" name="payment_method" value="cod" checked required>
                                    <span><strong>Cash on Delivery</strong></span>
                                </label>
                            </div>

                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Order Notes
                                    (Optional)</label>
                                <textarea name="notes" rows="4" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">{{ old('notes') }}</textarea>
                            </div>

                            <input type="hidden" name="billing_same_as_shipping" value="1">
                        </div>
                    </div>

                    <div class="col-lg-5 col-md-5 col-12">
                        <div class="order-summary"
                            style="background: white; padding: 30px; border-radius: 10px; position: sticky; top: 20px;">
                            <h4 style="margin-bottom: 20px; border-bottom: 2px solid #eee; padding-bottom: 10px;">Order
                                Summary</h4>

                            <div class="order-items" style="margin-bottom: 20px; max-height: 300px; overflow-y: auto;">
                                @foreach ($cartItems as $item)
                                    <div
                                        style="display: flex; gap: 15px; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
                                        <img src="{{ asset('storage/' . $item->product->main_image) }}"
                                            alt="{{ $item->product->product_name }}"
                                            style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
                                        <div style="flex: 1;">
                                            <p style="font-weight: bold; margin-bottom: 5px; font-size: 14px;">
                                                {{ $item->product->product_name }}</p>
                                            <p style="font-size: 12px; color: #666;">Qty: {{ $item->quantity }}</p>
                                            <p style="font-weight: bold;">Rs. {{ number_format($item->subtotal, 2) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div style="margin-bottom: 15px; display: flex; justify-content: space-between;">
                                <span>Subtotal:</span>
                                <span>Rs. {{ number_format($subtotal, 2) }}</span>
                            </div>

                            <div style="margin-bottom: 15px; display: flex; justify-content: space-between;">
                                <span>Tax (3%):</span>
                                <span>Rs. {{ number_format($tax, 2) }}</span>
                            </div>

                            <div style="margin-bottom: 20px; display: flex; justify-content: space-between;">
                                <span>Shipping:</span>
                                <span style="color: green;">FREE</span>
                            </div>

                            <div
                                style="padding-top: 15px; border-top: 2px solid #eee; margin-bottom: 20px; display: flex; justify-content: space-between;">
                                <strong style="font-size: 18px;">Total:</strong>
                                <strong style="font-size: 18px;">Rs. {{ number_format($total, 2) }}</strong>
                            </div>

                            <button type="submit"
                                style="width: 100%; padding: 15px; background: #000; color: white; border: none; border-radius: 5px; font-weight: bold; font-size: 16px; cursor: pointer;">Place
                                Order</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
