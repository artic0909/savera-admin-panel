<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Display cart page
    public function index()
    {
        $cartItems = Cart::with('product')
            ->where('customer_id', Auth::guard('customer')->id())
            ->get();

        $subtotal = $cartItems->sum('subtotal');
        $tax = $subtotal * 0.03; // 3% GST
        $shipping = 0; // Free shipping
        $total = $subtotal + $tax + $shipping;

        return view('frontend.cart', compact('cartItems', 'subtotal', 'tax', 'shipping', 'total'))->with('pageclass', 'hedersolution bg-1');
    }

    // Add item to cart
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'metal_configuration' => 'required|array',
            'price' => 'required|numeric|min:0',
        ]);

        $customerId = Auth::guard('customer')->id();

        // Check if item already exists in cart with same configuration
        $existingCart = Cart::where('customer_id', $customerId)
            ->where('product_id', $request->product_id)
            ->where('metal_configuration', json_encode($request->metal_configuration))
            ->first();

        if ($existingCart) {
            // Update quantity
            $existingCart->quantity += $request->quantity;
            $existingCart->save();
        } else {
            // Create new cart item
            Cart::create([
                'customer_id' => $customerId,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'metal_configuration' => $request->metal_configuration,
                'price_at_addition' => $request->price,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully!',
            'cart_count' => Cart::where('customer_id', $customerId)->sum('quantity'),
        ]);
    }

    // Update cart item quantity
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = Cart::where('id', $id)
            ->where('customer_id', Auth::guard('customer')->id())
            ->firstOrFail();

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully!',
            'subtotal' => $cartItem->subtotal,
        ]);
    }

    // Remove item from cart
    public function remove($id)
    {
        $cartItem = Cart::where('id', $id)
            ->where('customer_id', Auth::guard('customer')->id())
            ->firstOrFail();

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart!',
        ]);
    }

    // Clear entire cart
    public function clear()
    {
        Cart::where('customer_id', Auth::guard('customer')->id())->delete();

        return redirect()->route('cart.index')->with('success', 'Cart cleared successfully!');
    }

    // Get cart count (for header badge)
    public function count()
    {
        $count = Cart::where('customer_id', Auth::guard('customer')->id())->sum('quantity');

        return response()->json(['count' => $count]);
    }
}
