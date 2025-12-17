<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Product;

class FrontendController extends Controller
{
    public function home(Request $request)
    {
        $categories = Category::where('home_category', true)->take(5)->get();
        // Default to first category if none or invalid
        $selectedCategory = $categories->first();

        $products = collect();
        if ($selectedCategory) {
            $products = Product::where('category_id', $selectedCategory->id)->take(10)->get();
        }

        return view('frontend.home', compact('categories', 'selectedCategory', 'products'));
    }

    public function ajaxProducts(Request $request)
    {
        $categoryId = $request->input('category_id');
        $query = Product::where('category_id', $categoryId);

        // 1. Get all candidates from DB (filtering by category)
        // We will filter by other attributes in memory because they are in complex JSON or computed.
        $products = $query->get();

        // 2. Filter by Metal (material_id in metal_configurations)
        if ($request->has('metal') && $request->metal != '') {
            $metalIds = explode(',', $request->metal);
            $products = $products->filter(function ($product) use ($metalIds) {
                if (empty($product->metal_configurations)) return false;
                foreach ($product->metal_configurations as $config) {
                    // Check direct material_id or nested
                    if (isset($config['material_id']) && in_array($config['material_id'], $metalIds)) return true;
                }
                return false;
            });
        }

        // 3. Filter by Shape (in diamond_gemstone_info)
        if ($request->has('shape') && $request->shape != '') {
            $shapeNames = explode(',', $request->shape);
            // Case insensitive search needed?
            // Let's normalize both to lowercase for comparison if needed, or just exact match from DB
            $products = $products->filter(function ($product) use ($shapeNames) {
                if (empty($product->metal_configurations)) return false;
                foreach ($product->metal_configurations as $config) {
                    if (isset($config['diamond_info']) && is_array($config['diamond_info'])) {
                        foreach ($config['diamond_info'] as $dParams) {
                            if (isset($dParams['shape']) && in_array($dParams['shape'], $shapeNames)) return true;
                        }
                    }
                }
                return false;
            });
        }

        // 4. Sort by Price
        // Calculate price for sorting
        if ($request->has('sort') && in_array($request->sort, ['price_asc', 'price_desc'])) {
            $products = $products->sortBy(function ($product) {
                // Remove commas and cast to float
                return floatval(str_replace(',', '', $product->display_price));
            }, SORT_REGULAR, $request->sort === 'price_desc');
        }

        // Limit results if it's home page (optional, but keep consistent with request)
        // Use pagination logic if needed, but for now just take all or limit
        // User didn't specify limit for category page, but likely wants all.
        // For Home page strict limit of 10 might apply, but let's return all matching for category page.
        // We can add a 'limit' param.
        if ($request->has('limit')) {
            $products = $products->take($request->limit);
        }

        // Render the partial
        $html = view('frontend.partials.product_loop', compact('products'))->render();

        $category = Category::find($categoryId);

        return response()->json([
            'html' => $html,
            'category_slug' => $category ? $category->slug : '#'
        ]);
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $products = Product::where('category_id', $category->id)->get();
        // Pass filter options
        $materials = \App\Models\Material::all();
        $shapes = \App\Models\Shape::all();
        $styles = \App\Models\Style::all();

        return view('frontend.category', compact('category', 'products', 'materials', 'shapes', 'styles'))->with(['pageclass' => 'hedersolution bg-1']);
    }

    public function productDetails($id)
    {
        $product = Product::with('category')->findOrFail($id);

        $materials = \App\Models\Material::all();
        $shapes = \App\Models\Shape::all();
        $styles = \App\Models\Style::all();
        $sizes = \App\Models\Size::all();
        $colors = \App\Models\Color::all(); // Fetch all colors from Color model as a collection

        // Similar Products Logic (same category, excluding current)
        $similarProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $id)
            ->take(4)
            ->get();

        return view('frontend.product-details', compact('product', 'materials', 'shapes', 'styles', 'sizes', 'colors', 'similarProducts'));
    }
}
