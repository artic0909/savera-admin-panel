<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Metal;
use App\Models\Size;
use App\Models\Color;
use App\Models\Material;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

        $products = $query->latest()->paginate(10);
        return view('admin.product.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $metals = Metal::all();
        $sizes = Size::all();
        $colors = Color::all();
        $materials = Material::all();
        return view('admin.product.create', compact('categories', 'metals', 'sizes', 'colors', 'materials'));
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'category_id' => 'required|exists:categories,id',
    //         'product_name' => 'required|string|max:255',
    //         'main_image' => 'required|image|mimes:jpeg,png,jpg,gif',
    //         'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif',
    //         'delivery_time' => 'required|string',
    //     ]);

    //     $data = $request->all();

    //     // Handle File Uploads
    //     if ($request->hasFile('main_image')) {
    //         $data['main_image'] = $request->file('main_image')->store('products', 'public');
    //     }

    //     if ($request->hasFile('additional_images')) {
    //         $images = [];
    //         foreach ($request->file('additional_images') as $image) {
    //             $images[] = $image->store('products', 'public');
    //         }
    //         $data['additional_images'] = $images;
    //     }



    //     Product::create($data);

    //     return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    // }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'category_id' => 'required|exists:categories,id',
                'product_name' => 'required|string|max:255',
                'sku' => 'required|string|unique:products,sku',
                'description' => 'nullable|string',
                'main_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp',
                'additional_images.*' => 'nullable|mimes:jpeg,png,jpg,gif,webp,mp4,mov,ogg,wmv,avi,flv,mkv,webm',
                'delivery_time' => 'required|string',
                'stock_quantity' => 'required|numeric|min:0',
                'is_active' => 'sometimes|boolean',
            ]);

            $data = $request->all();
            $data['is_active'] = $request->has('is_active');

            // Handle File Uploads
            if ($request->hasFile('main_image')) {
                $data['main_image'] = $request->file('main_image')->store('products', 'public');
            }

            if ($request->hasFile('additional_images')) {
                $media = [];
                foreach ($request->file('additional_images') as $file) {
                    $media[] = $file->store('products', 'public');
                }
                $data['additional_images'] = $media;
            }

            if ($request->has('metal_configurations')) {
                $configs = $request->input('metal_configurations');
                $pairs = [];
                foreach ($configs as $config) {
                    $mId = $config['material_id'] ?? null;
                    $sId = $config['size_id'] ?? null;
                    if ($mId && $sId) {
                        $key = $mId . '-' . $sId;
                        if (in_array($key, $pairs)) {
                            return redirect()->back()->with('error', 'Duplicate Material and Size combination found in variants.')->withInput();
                        }
                        $pairs[] = $key;
                    }
                }
            }

            Product::create($data);

            return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create product: ' . $e->getMessage())->withInput();
        }
    }

    public function show(string $id)
    {
        $product = Product::with(['category'])->findOrFail($id);

        $metals = Metal::all();
        $sizes = Size::all();
        $materials = Material::all();

        // Fetch related models based on JSON arrays
        $productColors = $product->colors ? Color::whereIn('id', $product->colors)->get() : collect();

        return view('admin.product.show', compact('product', 'metals', 'sizes', 'materials', 'productColors'))->render();
    }

    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $metals = Metal::all();
        $sizes = Size::all();
        $colors = Color::all();
        $materials = Material::all();
        return view('admin.product.edit', compact('product', 'categories', 'metals', 'sizes', 'colors', 'materials'));
    }

    // public function update(Request $request, string $id)
    // {
    //     $product = Product::findOrFail($id);

    //     $request->validate([
    //         'category_id' => 'required|exists:categories,id',
    //         'product_name' => 'required|string|max:255',
    //         'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
    //         'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif',
    //         'delivery_time' => 'required|string',
    //     ]);

    //     $data = $request->except(['main_image', 'additional_images']);

    //     if ($request->hasFile('main_image')) {
    //         if ($product->main_image) {
    //             // Storage::disk('public')->delete($product->main_image); // Optional: delete old image
    //         }
    //         $data['main_image'] = $request->file('main_image')->store('products', 'public');
    //     }

    //     if ($request->hasFile('additional_images')) {
    //         // Optional: delete old additional images logic
    //         $images = [];
    //         foreach ($request->file('additional_images') as $image) {
    //             $images[] = $image->store('products', 'public');
    //         }
    //         $data['additional_images'] = $images; // Or merge with existing if needed
    //     }

    //     $product->update($data);

    //     return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    // }

    // public function destroy(string $id)
    // {
    //     $product = Product::findOrFail($id);
    //     $product->delete();
    //     return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    // }

    public function update(Request $request, string $id)
    {
        try {
            $product = Product::findOrFail($id);

            $request->validate([
                'category_id' => 'required|exists:categories,id',
                'product_name' => 'required|string|max:255',
                'sku' => 'required|string|unique:products,sku,' . $id,
                'description' => 'nullable|string',
                'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
                'additional_images.*' => 'nullable|mimes:jpeg,png,jpg,gif,webp,mp4,mov,ogg,wmv,avi,flv,mkv,webm',
                'delivery_time' => 'required|string',
                'stock_quantity' => 'required|numeric|min:0',
                'is_active' => 'sometimes|boolean',
            ]);

            $data = $request->except(['main_image', 'additional_images', 'deleted_additional_images']);
            $data['is_active'] = $request->has('is_active');

            if ($request->hasFile('main_image')) {
                $data['main_image'] = $request->file('main_image')->store('products', 'public');
            }

            // Get current additional images
            $currentMedia = $product->additional_images ?? [];

            // Handle deletions
            if ($request->has('deleted_additional_images')) {
                $deletedItems = $request->input('deleted_additional_images');
                $currentMedia = array_values(array_filter($currentMedia, function ($item) use ($deletedItems) {
                    if (in_array($item, $deletedItems)) {
                        Storage::disk('public')->delete($item);
                        return false;
                    }
                    return true;
                }));
            }

            // Handle new uploads
            if ($request->hasFile('additional_images')) {
                foreach ($request->file('additional_images') as $file) {
                    $currentMedia[] = $file->store('products', 'public');
                }
            }

            $data['additional_images'] = $currentMedia;

            if ($request->has('metal_configurations')) {
                $configs = $request->input('metal_configurations');
                $pairs = [];
                foreach ($configs as $config) {
                    $mId = $config['material_id'] ?? null;
                    $sId = $config['size_id'] ?? null;
                    if ($mId && $sId) {
                        $key = $mId . '-' . $sId;
                        if (in_array($key, $pairs)) {
                            return redirect()->back()->with('error', 'Duplicate Material and Size combination found in variants.')->withInput();
                        }
                        $pairs[] = $key;
                    }
                }
            }

            $product->update($data);

            return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update product: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(string $id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();
            return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete product. Please try again.');
        }
    }

    public function checkSKU(Request $request)
    {
        $sku = $request->query('sku');
        $productId = $request->query('product_id');

        $query = Product::where('sku', $sku);

        if ($productId) {
            $query->where('id', '!=', $productId);
        }

        $exists = $query->exists();

        return response()->json([
            'exists' => $exists,
            'message' => $exists ? 'This SKU already taken, use different' : 'SKU is available.'
        ]);
    }
}
