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
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', '%' . $search . '%')
                    ->orWhere('sku', 'like', '%' . $search . '%');
            });
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

    public function updateMRP(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'mrp' => 'required|numeric|min:0'
        ]);

        $product = Product::findOrFail($request->product_id);
        $configs = $product->metal_configurations;

        if (empty($configs) || !is_array($configs)) {
            return response()->json(['success' => false, 'message' => 'This product has no configurations to update. Please edit the product to add configurations first.']);
        }

        $updatedCount = 0;

        // Recursive function to update MRP in any structure
        $updateMrpRecursive = function (&$array) use (&$updateMrpRecursive, &$updatedCount, $request) {
            if (!is_array($array)) return;

            if (isset($array['mrp'])) {
                $array['mrp'] = $request->mrp;
                $updatedCount++;
            }

            foreach ($array as &$value) {
                if (is_array($value)) {
                    $updateMrpRecursive($value);
                }
            }
        };

        $updateMrpRecursive($configs);

        if ($updatedCount > 0) {
            $product->metal_configurations = $configs;
            $product->save();
            return response()->json(['success' => true, 'message' => 'MRP updated successfully across all configurations!', 'new_mrp' => $request->mrp]);
        }

        return response()->json(['success' => false, 'message' => 'No MRP field found in current configurations to update.']);
    }
}
