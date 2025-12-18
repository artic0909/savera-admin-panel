<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    // Display wishlist page
    public function index()
    {
        $wishlistItems = Wishlist::with('product')
            ->where('customer_id', Auth::guard('customer')->id())
            ->get();

        return view('frontend.wishlist', compact('wishlistItems'))->with('pageclass', 'hedersolution bg-1');
    }

    // Add product to wishlist
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $customerId = Auth::guard('customer')->id();

        // Check if already in wishlist
        $exists = Wishlist::where('customer_id', $customerId)
            ->where('product_id', $request->product_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Product already in wishlist!',
            ], 409);
        }

        $wishlist = Wishlist::create([
            'customer_id' => $customerId,
            'product_id' => $request->product_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product added to wishlist!',
            'wishlist_count' => Wishlist::where('customer_id', $customerId)->count(),
            'wishlist_id' => $wishlist->id,
        ]);
    }

    // Remove product from wishlist
    public function remove($id)
    {
        $wishlistItem = Wishlist::where('id', $id)
            ->where('customer_id', Auth::guard('customer')->id())
            ->firstOrFail();

        $wishlistItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from wishlist!',
            'wishlist_count' => Wishlist::where('customer_id', Auth::guard('customer')->id())->count(),
        ]);
    }

    // Move wishlist item to cart
    public function moveToCart($id)
    {
        $wishlistItem = Wishlist::with('product')
            ->where('id', $id)
            ->where('customer_id', Auth::guard('customer')->id())
            ->firstOrFail();

        $product = $wishlistItem->product;
        $customerId = Auth::guard('customer')->id();

        // Get first available configuration
        $metalConfig = is_array($product->metal_configurations) && count($product->metal_configurations) > 0
            ? $product->metal_configurations[0]
            : null;

        if ($metalConfig) {
            // Add to cart
            Cart::create([
                'customer_id' => $customerId,
                'product_id' => $product->id,
                'quantity' => 1,
                'metal_configuration' => $metalConfig,
                'price_at_addition' => floatval(str_replace(',', '', $product->display_price)),
            ]);

            // Remove from wishlist
            $wishlistItem->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product moved to cart!',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Product configuration not available!',
        ], 400);
    }

    // Get wishlist count (for header badge)
    public function count()
    {
        $count = Wishlist::where('customer_id', Auth::guard('customer')->id())->count();

        return response()->json(['count' => $count]);
    }
}
