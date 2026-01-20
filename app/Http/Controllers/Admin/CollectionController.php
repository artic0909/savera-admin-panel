<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Collection;
use App\Models\Product;
use App\Models\HomePageSetting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CollectionController extends Controller
{
    public function updateBanner(Request $request)
    {
        $request->validate([
            'collections_page_banner' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('collections_page_banner')) {
            $path = $request->file('collections_page_banner')->store('home-settings', 'public');

            HomePageSetting::updateOrCreate(
                ['key' => 'collections_page_banner'],
                ['value' => $path, 'type' => 'image', 'created_at' => now(), 'updated_at' => now()]
            );
        }

        return redirect()->back()->with('success', 'Banner updated successfully.');
    }

    public function index()
    {
        $collections = Collection::latest()->paginate(10);
        return view('admin.collections.index', compact('collections'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)->get(['id', 'product_name']);
        return view('admin.collections.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('collections', 'public');
        }

        $bannerPath = null;
        if ($request->hasFile('banner_image')) {
            $bannerPath = $request->file('banner_image')->store('collections/banners', 'public');
        }

        $baseSlug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
        $slug = Collection::generateUniqueSlug($baseSlug);

        $collection = Collection::create([
            'name' => $request->name,
            'slug' => $slug,
            'image' => $imagePath,
            'banner_image' => $bannerPath,
            'is_active' => $request->has('is_active'),
        ]);

        if ($request->has('products')) {
            $collection->products()->sync($request->products);
        }

        return redirect()->route('admin.collections.index')->with('success', 'Collection created successfully.');
    }

    public function edit(Collection $collection)
    {
        $products = Product::where('is_active', true)->get(['id', 'product_name']);
        $collection->load('products');
        return view('admin.collections.edit', compact('collection', 'products'));
    }

    public function update(Request $request, Collection $collection)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id',
        ]);

        $baseSlug = Str::slug($request->slug);
        $slug = Collection::generateUniqueSlug($baseSlug, $collection->id);

        $data = [
            'name' => $request->name,
            'slug' => $slug,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('image')) {
            if ($collection->image) {
                Storage::disk('public')->delete($collection->image);
            }
            $data['image'] = $request->file('image')->store('collections', 'public');
        }

        if ($request->hasFile('banner_image')) {
            if ($collection->banner_image) {
                Storage::disk('public')->delete($collection->banner_image);
            }
            $data['banner_image'] = $request->file('banner_image')->store('collections/banners', 'public');
        }

        $collection->update($data);

        if ($request->has('products')) {
            $collection->products()->sync($request->products);
        } else {
            $collection->products()->detach();
        }

        return redirect()->route('admin.collections.index')->with('success', 'Collection updated successfully.');
    }

    public function destroy(Collection $collection)
    {
        if ($collection->image) {
            Storage::disk('public')->delete($collection->image);
        }
        $collection->delete();
        return redirect()->route('admin.collections.index')->with('success', 'Collection deleted successfully.');
    }
}
