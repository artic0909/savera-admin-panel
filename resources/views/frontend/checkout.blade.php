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

                            @if (isset($addresses) && $addresses->count() > 0)
                                <div
                                    style="margin-bottom: 25px; background: #f9f9f9; padding: 15px; border-radius: 5px; border: 1px solid #eee;">
                                    <label
                                        style="display: block; margin-bottom: 10px; font-weight: bold; color: #333;">Select
                                        Saved Address</label>
                                    <select id="saved-address-selector"
                                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; cursor: pointer;">
                                        <option value="">-- Choose a saved address --</option>
                                        @foreach ($addresses as $addr)
                                            <option value="{{ $addr->id }}" data-fullname="{{ $addr->full_name }}"
                                                data-phone="{{ $addr->phone }}" data-line1="{{ $addr->address_line1 }}"
                                                data-line2="{{ $addr->address_line2 }}" data-city="{{ $addr->city }}"
                                                data-state="{{ $addr->state }}" data-postal="{{ $addr->postal_code }}"
                                                data-country="{{ $addr->country }}">
                                                {{ $addr->full_name }} - {{ $addr->address_line1 }}, {{ $addr->city }}
                                                ({{ $addr->postal_code }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const addressSelector = document.getElementById('saved-address-selector');
                                        if (addressSelector) {
                                            addressSelector.addEventListener('change', function() {
                                                const selectedOption = this.options[this.selectedIndex];
                                                if (selectedOption.value) {
                                                    // Populate fields
                                                    document.querySelector('input[name="shipping_full_name"]').value = selectedOption
                                                        .dataset.fullname;
                                                    document.querySelector('input[name="shipping_phone"]').value = selectedOption
                                                        .dataset.phone;
                                                    document.querySelector('input[name="shipping_address_line1"]').value =
                                                        selectedOption.dataset.line1;
                                                    document.querySelector('input[name="shipping_address_line2"]').value =
                                                        selectedOption.dataset.line2 || '';
                                                    document.querySelector('input[name="shipping_city"]').value = selectedOption.dataset
                                                        .city;
                                                    document.querySelector('input[name="shipping_state"]').value = selectedOption
                                                        .dataset.state;
                                                    document.querySelector('input[name="shipping_postal_code"]').value = selectedOption
                                                        .dataset.postal;
                                                    document.querySelector('input[name="shipping_country"]').value = selectedOption
                                                        .dataset.country;

                                                    // Trigger input events in case there are other listeners (like validation)
                                                    const fields = ['shipping_full_name', 'shipping_phone', 'shipping_address_line1',
                                                        'shipping_city', 'shipping_state', 'shipping_postal_code',
                                                        'shipping_country'
                                                    ];
                                                    fields.forEach(name => {
                                                        const event = new Event('input', {
                                                            bubbles: true
                                                        });
                                                        document.querySelector(`input[name="${name}"]`).dispatchEvent(event);
                                                    });
                                                }
                                            });
                                        }
                                    });
                                </script>
                            @endif
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
                                <textarea name="notes" rows="4"
                                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">{{ old('notes') }}</textarea>
                            </div>

                            <input type="hidden" name="billing_same_as_shipping" value="1">
                            <input type="hidden" name="checkout_mode" value="{{ $checkoutMode ?? 'regular' }}">
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

                            <div style="margin-bottom: 20px;">
                                <div class="input-group" style="display: flex; gap: 10px;">
                                    <input type="text" id="coupon-code-input" class="form-control"
                                        placeholder="Coupon Code"
                                        style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                                    <button type="button" id="apply-coupon-btn"
                                        style="padding: 10px 15px; background: #333; color: white; border: none; border-radius: 5px; cursor: pointer;">Apply</button>
                                </div>
                                <div id="coupon-message" style="margin-top: 5px; font-size: 13px;"></div>
                                <input type="hidden" name="coupon_code" id="coupon-code-hidden">
                            </div>

                            <div style="margin-bottom: 15px; display: flex; justify-content: space-between;">
                                <span>Subtotal:</span>
                                <span>Rs. {{ number_format($subtotal, 2) }}</span>
                            </div>

                            <!-- <div style="margin-bottom: 15px; display: flex; justify-content: space-between;">
                                    <span>Tax (3%):</span>
                                    <span>Rs. {{ number_format($tax, 2) }}</span>
                                </div> -->

                            <!-- <div style="margin-bottom: 15px; display: flex; justify-content: space-between;">
                                    <span>Shipping:</span>
                                    <span style="color: green;">FREE</span>
                                </div> -->

                            <div id="discount-row"
                                style="margin-bottom: 15px; display: none; justify-content: space-between; color: green;">
                                <span>Discount:</span>
                                <span id="discount-amount">- Rs. 0.00</span>
                            </div>

                            <div
                                style="padding-top: 15px; border-top: 2px solid #eee; margin-bottom: 20px; display: flex; justify-content: space-between;">
                                <strong style="font-size: 18px;">Total:</strong>
                                <strong style="font-size: 18px;" id="grand-total"
                                    data-original-total="{{ $total }}">Rs. {{ number_format($total, 2) }}</strong>
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

    @push('scripts')
        <script>
            // Coupon Code Logic
            const applyCouponBtn = document.getElementById('apply-coupon-btn');
            const couponInput = document.getElementById('coupon-code-input');
            const couponMessage = document.getElementById('coupon-message');
            const couponHidden = document.getElementById('coupon-code-hidden');
            const discountRow = document.getElementById('discount-row');
            const discountAmountSpan = document.getElementById('discount-amount');
            const grandTotalSpan = document.getElementById('grand-total');

            if (applyCouponBtn) {
                applyCouponBtn.addEventListener('click', function() {
                    const code = couponInput.value.trim();
                    if (!code) {
                        couponMessage.innerHTML = '<span style="color: orange;">Please enter a code</span>';
                        return;
                    }

                    couponMessage.innerHTML = '<span style="color: #666;">Applyng...</span>';

                    // Allow button click only once per request
                    applyCouponBtn.disabled = true;

                    fetch('{{ route('api.checkCoupon') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                code: code,
                                amount: {{ $subtotal }} // Send subtotal for validation
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            applyCouponBtn.disabled = false;

                            if (data.valid) {
                                couponMessage.innerHTML = '<span style="color: green;">' + data.message + '</span>';

                                // Show discount row
                                discountRow.style.display = 'flex';
                                discountAmountSpan.textContent = '- Rs. ' + parseFloat(data.discount).toFixed(2);

                                // Update hidden input
                                couponHidden.value = data.code;

                                // Update Total
                                let originalTotal = parseFloat(grandTotalSpan.getAttribute('data-original-total'));
                                // Correct logic: Total is calculated as (subtotal + tax + shipping - discount)
                                // But here $total passed from backend already includes tax. 
                                // So we just subtract discount.
                                let newTotal = originalTotal - parseFloat(data.discount);
                                if (newTotal < 0) newTotal = 0;

                                grandTotalSpan.textContent = 'Rs. ' + newTotal.toFixed(2);

                            } else {
                                couponMessage.innerHTML = '<span style="color: red;">' + data.message + '</span>';
                                // Reset if invalid
                                discountRow.style.display = 'none';
                                couponHidden.value = '';
                                grandTotalSpan.textContent = 'Rs. ' + parseFloat(grandTotalSpan.getAttribute(
                                    'data-original-total')).toFixed(2);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            applyCouponBtn.disabled = false;
                            couponMessage.innerHTML = '<span style="color: red;">Error applying coupon</span>';
                        });
                });
            }

            // Real-time pincode validation on checkout
            const pincodeInput = document.querySelector('input[name="shipping_postal_code"]');
            let pincodeValid = false;

            if (pincodeInput) {
                // Create message container
                const messageDiv = document.createElement('div');
                messageDiv.id = 'pincode-validation-message';
                messageDiv.style.marginTop = '5px';
                messageDiv.style.fontSize = '13px';
                messageDiv.style.fontWeight = 'bold';
                pincodeInput.parentNode.appendChild(messageDiv);

                pincodeInput.addEventListener('blur', function() {
                    const pincode = this.value.trim();
                    const messageDiv = document.getElementById('pincode-validation-message');

                    if (pincode.length === 0) {
                        messageDiv.innerHTML = '';
                        pincodeValid = false;
                        return;
                    }

                    messageDiv.innerHTML = '<span style="color: #666;">⏳ Checking...</span>';

                    fetch('{{ route('api.checkPincode') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                pincode: pincode
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.available) {
                                messageDiv.innerHTML = '<span style="color: green;">✅ Delivery available</span>';
                                pincodeValid = true;
                            } else {
                                messageDiv.innerHTML =
                                    '<span style="color: red;">❌ Delivery not available in this area</span>';
                                pincodeValid = false;
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            messageDiv.innerHTML = '<span style="color: red;">❌ Error checking pincode</span>';
                            pincodeValid = false;
                        });
                });
            }

            // Prevent form submission if pincode is invalid
            const checkoutForm = document.querySelector('form[action="{{ route('checkout.placeOrder') }}"]');
            if (checkoutForm) {
                checkoutForm.addEventListener('submit', function(e) {
                    const pincode = pincodeInput ? pincodeInput.value.trim() : '';
                    if (pincode && !pincodeValid) {
                        e.preventDefault();
                        alert('Please enter a valid pincode where delivery is available.');
                        pincodeInput.focus();
                        return false;
                    }
                });
            }
        </script>
    @endpush
@endsection
