<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->has('search') && $request->search != '') {
            $query->where('product_name', 'like', '%' . $request->search . '%');
        }

        $products = $query->paginate(15);

        return view('admin.inventory.index', compact('products'));
    }

    public function updateStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'stock_quantity' => 'required|integer|min:0'
        ]);

        $product = Product::findOrFail($request->product_id);
        $product->stock_quantity = $request->stock_quantity;
        $product->save();

        return response()->json(['success' => true, 'message' => 'Stock updated successfully!', 'new_stock' => $product->stock_quantity]);
    }
}
