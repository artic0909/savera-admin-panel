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

        if (is_array($configs)) {
            // Traverse the configs to find and update MRP
            // The structure can be flat or nested by metal/size as seen in edit.blade.php logic

            // Helper to update MRP in a nested or flat array
            $updated = false;
            foreach ($configs as &$item) {
                if (is_array($item)) {
                    if (isset($item['mrp'])) {
                        $item['mrp'] = $request->mrp;
                        $updated = true;
                    } else {
                        // Check one level deeper for nested configs
                        foreach ($item as &$subItem) {
                            if (is_array($subItem) && isset($subItem['mrp'])) {
                                $subItem['mrp'] = $request->mrp;
                                $updated = true;
                            }
                        }
                    }
                }
            }

            if ($updated) {
                $product->metal_configurations = $configs;
                $product->save();
                return response()->json(['success' => true, 'message' => 'MRP updated successfully!', 'new_mrp' => $request->mrp]);
            }
        }

        return response()->json(['success' => false, 'message' => 'No MRP found to update in configurations.']);
    }
}
