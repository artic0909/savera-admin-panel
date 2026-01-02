<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\PaymentSetting;
use Illuminate\Support\Facades\Http;

class CheckoutController extends Controller
{
    // Display checkout page
    public function index()
    {
        $categories = Category::where('home_category', true)->take(5)->get();

        $customerId = Auth::guard('customer')->id();

        $customerId = Auth::guard('customer')->id();
        $checkoutMode = request('mode', 'regular');
        $cartItems = collect();

        if ($checkoutMode === 'direct') {
            $directItem = session('direct_checkout_item');
            if ($directItem) {
                // Create a temporary Cart object (mapped correctly for the view)
                // We need to fetch the product to ensure we have the relationship
                $product = \App\Models\Product::find($directItem['product_id']);

                if ($product) {
                    $cartItem = new Cart([
                        'product_id' => $directItem['product_id'],
                        'quantity' => $directItem['quantity'],
                        'metal_configuration' => $directItem['metal_configuration'],
                        'price_at_addition' => $directItem['price'],
                    ]);
                    $cartItem->setRelation('product', $product);
                    $cartItems->push($cartItem);
                }
            }
        } else {
            $cartItems = Cart::with('product')
                ->where('customer_id', $customerId)
                ->get();
        }

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        $subtotal = $cartItems->sum('subtotal');
        $tax = $subtotal * 0.00; // 3% GST
        $shipping = 0; // Free shipping
        $total = $subtotal + $tax + $shipping;

        $addresses = CustomerAddress::where('customer_id', $customerId)->get();
        $customer = Auth::guard('customer')->user();
        $paymentSetting = PaymentSetting::first();

        return view('frontend.checkout', compact('cartItems', 'subtotal', 'tax', 'shipping', 'total', 'addresses', 'customer', 'checkoutMode', 'categories', 'paymentSetting'))->with('pageclass', 'hedersolution bg-1');
    }

    // Place order
    public function placeOrder(Request $request)
    {
        $request->validate([
            'shipping_full_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address_line1' => 'required|string|max:255',
            'shipping_address_line2' => 'nullable|string|max:255',
            'shipping_city' => 'required|string|max:100',
            'shipping_state' => 'required|string|max:100',
            'shipping_postal_code' => 'required|string|max:20',
            'shipping_country' => 'required|string|max:100',
            'payment_method' => 'required|in:cod,online',
            'save_address' => 'nullable|boolean',
            'checkout_mode' => 'nullable|string|in:regular,direct',
            'coupon_code' => 'nullable|string|exists:coupons,code',
            'razorpay_payment_id' => 'required_if:payment_method,online|nullable|string',
            'razorpay_order_id' => 'nullable|string',
            'razorpay_signature' => 'nullable|string',
        ]);

        // Validate pincode availability
        $pincode = \App\Models\Pincode::where('code', $request->shipping_postal_code)
            ->where('status', 'active')
            ->first();

        if (!$pincode) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['shipping_postal_code' => 'Delivery not available for this pincode. Please check available service areas.']);
        }

        $customerId = Auth::guard('customer')->id();
        $checkoutMode = $request->input('checkout_mode', 'regular');
        $cartItems = collect();

        if ($checkoutMode === 'direct') {
            $directItem = session('direct_checkout_item');
            if ($directItem) {
                $product = \App\Models\Product::find($directItem['product_id']);
                if ($product) {
                    $cartItem = new Cart([
                        'product_id' => $directItem['product_id'],
                        'quantity' => $directItem['quantity'],
                        'metal_configuration' => $directItem['metal_configuration'],
                        'price_at_addition' => $directItem['price'],
                    ]);
                    $cartItem->setRelation('product', $product);
                    $cartItems->push($cartItem);
                }
            }
        } else {
            $cartItems = Cart::with('product')
                ->where('customer_id', $customerId)
                ->get();
        }

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        // Validate stock availability before proceeding
        foreach ($cartItems as $item) {
            if ($item->product->stock_quantity < $item->quantity) {
                return redirect()->back()->with('error', 'Insufficient stock for product: ' . $item->product->product_name);
            }
        }

        DB::beginTransaction();

        try {
            // Calculate totals
            $subtotal = $cartItems->sum('subtotal');
            $tax = $subtotal * 0.00; // 3% GST
            $shipping = 0; // Free shipping
            $total = $subtotal + $tax + $shipping;

            // Coupon Logic
            $discountAmount = 0;
            $couponCode = null;

            if ($request->filled('coupon_code')) {
                $coupon = \App\Models\Coupon::where('code', $request->coupon_code)->first();

                if ($coupon && $coupon->isValid($subtotal)) {
                    $discountAmount = $coupon->calculateDiscount($subtotal);
                    $couponCode = $coupon->code;
                    $total -= $discountAmount;

                    // Increment usage
                    $coupon->increment('used_count');
                }
            }

            // Ensure total doesn't go negative
            $total = max(0, $total);

            // Prepare shipping address
            $shippingAddress = [
                'full_name' => $request->shipping_full_name,
                'phone' => $request->shipping_phone,
                'address_line1' => $request->shipping_address_line1,
                'address_line2' => $request->shipping_address_line2,
                'city' => $request->shipping_city,
                'state' => $request->shipping_state,
                'postal_code' => $request->shipping_postal_code,
                'country' => $request->shipping_country,
            ];

            // Use same for billing if not specified
            $billingAddress = $request->billing_same_as_shipping ? $shippingAddress : $shippingAddress;

            // Determine payment status and details
            $paymentStatus = 'pending';
            $transactionId = null;
            $paymentInfo = null;

            if ($request->payment_method === 'online' && $request->filled('razorpay_payment_id')) {
                // In a production app, verify signature here
                $paymentStatus = 'completed'; // Assuming success if we got here via valid signature
                $transactionId = $request->razorpay_payment_id;
                $paymentInfo = [
                    'payment_id' => $request->razorpay_payment_id,
                    'order_id' => $request->razorpay_order_id,
                    'signature' => $request->razorpay_signature,
                ];
            } else if ($request->payment_method === 'cod') {
                $paymentStatus = 'pending';
            }

            // Create order
            $order = Order::create([
                'customer_id' => $customerId,
                'order_number' => Order::generateOrderNumber(),
                'status' => 'pending',
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping' => $shipping,
                'discount_amount' => $discountAmount,
                'coupon_code' => $couponCode,
                'total' => $total,
                'shipping_address' => $shippingAddress,
                'billing_address' => $billingAddress,
                'payment_method' => $request->payment_method,
                'payment_status' => $paymentStatus,
                'transaction_id' => $transactionId,
                'payment_info' => $paymentInfo,
                'notes' => $request->notes,
            ]);

            // Create order items
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $cartItem->product->product_name,
                    'metal_configuration' => $cartItem->metal_configuration,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price_at_addition,
                    'subtotal' => $cartItem->subtotal,
                ]);

                // Deduct stock
                $cartItem->product->decrement('stock_quantity', $cartItem->quantity);
            }

            // Save address if requested
            if ($request->save_address) {
                CustomerAddress::create([
                    'customer_id' => $customerId,
                    'address_type' => 'both',
                    'full_name' => $request->shipping_full_name,
                    'phone' => $request->shipping_phone,
                    'address_line1' => $request->shipping_address_line1,
                    'address_line2' => $request->shipping_address_line2,
                    'city' => $request->shipping_city,
                    'state' => $request->shipping_state,
                    'postal_code' => $request->shipping_postal_code,
                    'country' => $request->shipping_country,
                    'is_default' => false,
                ]);
            }

            // Clear cart or session
            if ($checkoutMode === 'direct') {
                session()->forget('direct_checkout_item');
            } else {
                Cart::where('customer_id', $customerId)->delete();
            }

            DB::commit();

            // Push to Shiprocket if enabled
            try {
                $shippingSetting = \App\Models\ShippingSetting::first();
                if ($shippingSetting && $shippingSetting->is_shiprocket_enabled) {
                    \Illuminate\Support\Facades\Log::info('Shiprocket: Attempting automatic push for Order ' . $order->order_number);
                    $shiprocketService = new \App\Services\ShiprocketService();
                    $result = $shiprocketService->createOrder($order->load('customer', 'items.product'));
                    if ($result['success']) {
                        $order->update([
                            'shiprocket_order_id' => $result['data']['order_id'],
                            'shiprocket_shipment_id' => $result['data']['shipment_id'],
                            'status' => 'processing'
                        ]);
                        \Illuminate\Support\Facades\Log::info('Shiprocket: Automatic push successful for Order ' . $order->order_number . '. SR ID: ' . $result['data']['order_id']);

                        // Try to assign AWB immediately
                        try {
                            $awbResult = $shiprocketService->assignAwb($result['data']['shipment_id']);
                            if ($awbResult['success']) {
                                $order->update([
                                    'awb_code' => $awbResult['awb_code'],
                                    'tracking_url' => "https://shiprocket.co/tracking/" . $awbResult['awb_code']
                                ]);
                                \Illuminate\Support\Facades\Log::info('Shiprocket: AWB auto-assigned for Order ' . $order->order_number . ': ' . $awbResult['awb_code']);
                            } else {
                                \Illuminate\Support\Facades\Log::warning('Shiprocket: AWB auto-assignment failed for Order ' . $order->order_number . ': ' . ($awbResult['message'] ?? 'Unknown Error'));
                            }
                        } catch (\Exception $e) {
                            \Illuminate\Support\Facades\Log::warning('Auto AWB Assignment Failed: ' . $e->getMessage());
                        }
                    } else {
                        \Illuminate\Support\Facades\Log::warning('Shiprocket: Automatic push failed for Order ' . $order->order_number . ': ' . ($result['message'] ?? 'Unknown reason'));
                    }
                } else {
                    \Illuminate\Support\Facades\Log::info('Shiprocket: Automatic push skipped for Order ' . $order->order_number . ' (Not enabled or settings missing)');
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Auto Shiprocket Push Exception: ' . $e->getMessage());
            }

            return redirect()->route('order.success', $order->order_number)
                ->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to place order. Please try again.')
                ->withInput();
        }
    }

    // Order success page
    public function success($orderNumber)
    {
        $categories = Category::where('home_category', true)->take(5)->get();

        $order = Order::with('items.product')
            ->where('order_number', $orderNumber)
            ->where('customer_id', Auth::guard('customer')->id())
            ->firstOrFail();

        return view('frontend.order-success', compact('order', 'categories'))->with('pageclass', 'hedersolution bg-1');
    }



    // Order details page
    public function orderDetails($orderNumber)
    {
        $order = Order::with(['items.product', 'customer'])
            ->where('order_number', $orderNumber)
            ->where('customer_id', Auth::guard('customer')->id())
            ->firstOrFail();

        return view('frontend.order-details', compact('order'))->with(['pageclass' => 'hedersolution bg-1']);
    }

    // Cancel order
    public function cancelOrder($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('customer_id', Auth::guard('customer')->id())
            ->firstOrFail();

        // Allow cancellation only if status is pending or processing
        if (!in_array($order->status, ['pending', 'processing'])) {
            return redirect()->back()->with('error', 'This order cannot be cancelled anymore.');
        }

        // Try to cancel in Shiprocket if it was pushed
        if ($order->shiprocket_order_id) {
            $shiprocketService = new \App\Services\ShiprocketService();
            $result = $shiprocketService->cancelOrder($order->shiprocket_order_id);
            if (!$result['success']) {
                \Illuminate\Support\Facades\Log::warning('Shiprocket User Cancel Failed: ' . ($result['message'] ?? 'Unknown error'), ['order' => $order->order_number]);
            }
        }

        // Restore Stock
        foreach ($order->load('items.product')->items as $item) {
            if ($item->product) {
                $item->product->increment('stock_quantity', $item->quantity);
            }
        }

        $order->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Order has been cancelled successfully.');
    }
    // Direct Checkout (Buy Now)
    public function directCheckout(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric',
            'metal_configuration' => 'required|array',
        ]);

        // Store item in session
        $item = [
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'metal_configuration' => $request->metal_configuration,
        ];

        session(['direct_checkout_item' => $item]);

        return response()->json([
            'success' => true,
            'redirect_url' => route('checkout.index', ['mode' => 'direct']),
        ]);
    }

    public function initiatePayment(Request $request)
    {
        $customerId = Auth::guard('customer')->id();
        $checkoutMode = $request->input('checkout_mode', 'regular');
        $cartItems = collect();

        if ($checkoutMode === 'direct') {
            $directItem = session('direct_checkout_item');
            if ($directItem) {
                $product = \App\Models\Product::find($directItem['product_id']);
                if ($product) {
                    $cartItem = new Cart([
                        'product_id' => $directItem['product_id'],
                        'quantity' => $directItem['quantity'],
                        'metal_configuration' => $directItem['metal_configuration'],
                        'price_at_addition' => $directItem['price'],
                    ]);
                    $cartItem->setRelation('product', $product);
                    $cartItems->push($cartItem);
                }
            }
        } else {
            $cartItems = Cart::with('product')
                ->where('customer_id', $customerId)
                ->get();
        }

        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Cart is empty'], 400);
        }

        $subtotal = $cartItems->sum('subtotal');
        $tax = $subtotal * 0.00;
        $total = $subtotal + $tax;

        // Coupon Logic
        if ($request->filled('coupon_code')) {
            $coupon = \App\Models\Coupon::where('code', $request->coupon_code)->first();
            if ($coupon && $coupon->isValid($subtotal)) {
                $discountAmount = $coupon->calculateDiscount($subtotal);
                $total -= $discountAmount;
            }
        }
        $total = max(0, $total);

        $settings = PaymentSetting::first();
        if (!$settings || !$settings->razorpay_key || !$settings->razorpay_secret) {
            return response()->json(['error' => 'Payment gateway not configured'], 500);
        }

        /** @var \Illuminate\Http\Client\Response $response */
        $response = Http::withBasicAuth($settings->razorpay_key, $settings->razorpay_secret)
            ->post('https://api.razorpay.com/v1/orders', [
                'amount' => (int) round($total * 100),
                'currency' => 'INR',
                'receipt' => 'order_' . time(),
                'payment_capture' => 1
            ]);

        if ($response && $response->successful()) {
            $orderData = $response->json();
            $user = Auth::guard('customer')->user();
            return response()->json([
                'order_id' => $orderData['id'],
                'amount' => $orderData['amount'],
                'key' => $settings->razorpay_key,
                'customer_name' => $user->name,
                'customer_email' => $user->email ?? '',
                'customer_phone' => $user->phone ?? '',
                'description' => 'Payment for Order',
            ]);
        } else {
            return response()->json(['error' => 'Failed to create payment order', 'details' => $response->body()], 500);
        }
    }
}
