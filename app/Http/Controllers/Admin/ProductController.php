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
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
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

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'product_name' => 'required|string|max:255',
            'main_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'delivery_time' => 'required|string',
        ]);

        $data = $request->all();

        // Handle File Uploads
        if ($request->hasFile('main_image')) {
            $data['main_image'] = $request->file('main_image')->store('products', 'public');
        }

        if ($request->hasFile('additional_images')) {
            $images = [];
            foreach ($request->file('additional_images') as $image) {
                $images[] = $image->store('products', 'public');
            }
            $data['additional_images'] = $images;
        }



        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
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

    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'product_name' => 'required|string|max:255',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'delivery_time' => 'required|string',
        ]);

        $data = $request->except(['main_image', 'additional_images']);

        if ($request->hasFile('main_image')) {
            if ($product->main_image) {
                // Storage::disk('public')->delete($product->main_image); // Optional: delete old image
            }
            $data['main_image'] = $request->file('main_image')->store('products', 'public');
        }

        if ($request->hasFile('additional_images')) {
            // Optional: delete old additional images logic
            $images = [];
            foreach ($request->file('additional_images') as $image) {
                $images[] = $image->store('products', 'public');
            }
            $data['additional_images'] = $images; // Or merge with existing if needed
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
