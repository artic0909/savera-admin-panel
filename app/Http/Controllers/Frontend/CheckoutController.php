<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    // Display checkout page
    public function index()
    {
        $customerId = Auth::guard('customer')->id();

        $cartItems = Cart::with('product')
            ->where('customer_id', $customerId)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        $subtotal = $cartItems->sum('subtotal');
        $tax = $subtotal * 0.03; // 3% GST
        $shipping = 0; // Free shipping
        $total = $subtotal + $tax + $shipping;

        $addresses = CustomerAddress::where('customer_id', $customerId)->get();
        $customer = Auth::guard('customer')->user();

        return view('frontend.checkout', compact('cartItems', 'subtotal', 'tax', 'shipping', 'total', 'addresses', 'customer'))->with('pageclass', 'hedersolution bg-1');
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
        ]);

        $customerId = Auth::guard('customer')->id();

        $cartItems = Cart::with('product')
            ->where('customer_id', $customerId)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        DB::beginTransaction();

        try {
            // Calculate totals
            $subtotal = $cartItems->sum('subtotal');
            $tax = $subtotal * 0.03; // 3% GST
            $shipping = 0; // Free shipping
            $total = $subtotal + $tax + $shipping;

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

            // Create order
            $order = Order::create([
                'customer_id' => $customerId,
                'order_number' => Order::generateOrderNumber(),
                'status' => 'pending',
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping' => $shipping,
                'total' => $total,
                'shipping_address' => $shippingAddress,
                'billing_address' => $billingAddress,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_method === 'cod' ? 'pending' : 'pending',
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

            // Clear cart
            Cart::where('customer_id', $customerId)->delete();

            DB::commit();

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
        $order = Order::with('items.product')
            ->where('order_number', $orderNumber)
            ->where('customer_id', Auth::guard('customer')->id())
            ->firstOrFail();

        return view('frontend.order-success', compact('order'))->with('pageclass', 'hedersolution bg-1');
    }

    // My orders page
    public function myOrders()
    {
        $orders = Order::where('customer_id', Auth::guard('customer')->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('frontend.my-orders', compact('orders'))->with('pageclass', 'hedersolution bg-1');
    }

    // Order details page
    public function orderDetails($orderNumber)
    {
        $order = Order::with('items.product')
            ->where('order_number', $orderNumber)
            ->where('customer_id', Auth::guard('customer')->id())
            ->firstOrFail();

        return view('frontend.order-details', compact('order'))
            ->with('pageclass', 'hedersolution bg-1');
    }
}
