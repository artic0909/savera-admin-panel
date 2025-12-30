<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Product;
use App\Models\WhyChoose;

class FrontendController extends Controller
{
    public function home(Request $request)
    {
        $categories = Category::where('home_category', true)->take(5)->get();
        // Default to first category if none or invalid
        $selectedCategory = $categories->first();

        $products = collect();
        if ($selectedCategory) {
            $products = Product::where('category_id', $selectedCategory->id)
                ->where('is_active', true)
                ->take(10)
                ->get();

            // Always sort home products by price low to high
            $products = $products->sortBy(function ($product) {
                return (float) str_replace(',', '', $product->display_price);
            });
        }

        $storyVideos = \App\Models\StoryVideo::with(['products' => function ($query) {
            $query->where('products.is_active', true);
        }])->where('is_active', true)->latest()->get();

        $wishlistProductIds = [];
        if (\Illuminate\Support\Facades\Auth::guard('customer')->check()) {
            $wishlistProductIds = \App\Models\Wishlist::where('customer_id', \Illuminate\Support\Facades\Auth::guard('customer')->id())
                ->pluck('product_id')
                ->toArray();
        }

        $homeSettings = \App\Models\HomePageSetting::all()->pluck('value', 'key');

        return view('frontend.home', compact('categories', 'selectedCategory', 'products', 'storyVideos', 'wishlistProductIds', 'homeSettings'));
    }

    public function ajaxProducts(Request $request)
    {
        $categoryId = $request->input('category_id');
        $query = Product::where('category_id', $categoryId)->where('is_active', true);

        // 1. Get all candidates from DB (filtering by category)
        $products = $query->get();

        // 2. Filter by Metal (material_id in metal_configurations)
        if ($request->has('metal') && $request->metal != '') {
            $metalIds = explode(',', $request->metal);
            $products = $products->filter(function ($product) use ($metalIds) {
                if (empty($product->metal_configurations))
                    return false;
                foreach ($product->metal_configurations as $config) {
                    if (isset($config['material_id']) && in_array($config['material_id'], $metalIds))
                        return true;
                }
                return false;
            });
        }

        // 3. Filter by Shape (in diamond_gemstone_info)
        if ($request->has('shape') && $request->shape != '') {
            $shapeNames = explode(',', $request->shape);
            $products = $products->filter(function ($product) use ($shapeNames) {
                if (empty($product->metal_configurations))
                    return false;
                foreach ($product->metal_configurations as $config) {
                    if (isset($config['diamond_info']) && is_array($config['diamond_info'])) {
                        foreach ($config['diamond_info'] as $dParams) {
                            if (isset($dParams['shape']) && in_array($dParams['shape'], $shapeNames))
                                return true;
                        }
                    }
                }
                return false;
            });
        }

        // 4. Sort by Price
        $sortOrder = $request->input('sort', 'price_asc'); // Default to price_asc
        $products = $products->sortBy(function ($product) {
            return (float) str_replace(',', '', $product->display_price);
        }, SORT_REGULAR, $sortOrder === 'price_desc');

        // Pagination
        $perPage = 15;
        $page = $request->get('page', 1);
        $category = Category::find($categoryId);
        $path = $category ? route('category.show', $category->slug) : url()->current();

        $paginatedProducts = new \Illuminate\Pagination\LengthAwarePaginator(
            $products->forPage($page, $perPage),
            $products->count(),
            $perPage,
            $page,
            ['path' => $path, 'query' => $request->query()]
        );

        $html = view('frontend.partials.product_loop', ['products' => $paginatedProducts])->render();
        $paginationHtml = $paginatedProducts->links('frontend.partials.custom_pagination')->toHtml();

        return response()->json([
            'html' => $html,
            'pagination' => $paginationHtml,
            'category_slug' => $category ? $category->slug : '#'
        ]);
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $products = Product::where('category_id', $category->id)->where('is_active', true)->get();

        // Always sort by price low to high
        $products = $products->sortBy(function ($product) {
            return (float) str_replace(',', '', $product->display_price);
        });

        // Manual Pagination
        $perPage = 15;
        $page = request()->get('page', 1);
        $paginatedProducts = new \Illuminate\Pagination\LengthAwarePaginator(
            $products->forPage($page, $perPage),
            $products->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // Pass filter options
        $materials = \App\Models\Material::all();
        $shapes = \App\Models\Shape::all();
        $styles = \App\Models\Style::all();

        $categories = Category::where('home_category', true)->take(5)->get();

        return view('frontend.category', [
            'category' => $category,
            'products' => $paginatedProducts,
            'materials' => $materials,
            'shapes' => $shapes,
            'styles' => $styles,
            'categories' => $categories
        ])->with(['pageclass' => 'hedersolution bg-1']);
    }

    public function productDetails($slug)
    {
        $product = Product::with('category')->where('slug', $slug)->where('is_active', true)->firstOrFail();

        $materials = \App\Models\Material::all();
        $shapes = \App\Models\Shape::all();
        $styles = \App\Models\Style::all();
        $sizes = \App\Models\Size::all();
        $colors = \App\Models\Color::all(); // Fetch all colors from Color model as a collection
        $categories = Category::where('home_category', true)->take(5)->get();

        // Similar Products Logic (same category, excluding current)
        $similarProducts = Product::where('category_id', $product->category_id)
            ->where('is_active', true)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        // Check if product is in wishlist
        $wishlistItem = null;
        if (\Illuminate\Support\Facades\Auth::guard('customer')->check()) {
            $wishlistItem = \App\Models\Wishlist::where('customer_id', \Illuminate\Support\Facades\Auth::guard('customer')->id())
                ->where('product_id', $product->id)
                ->first();
        }

        // Fetch active coupons
        $coupons = \App\Models\Coupon::active()->get();

        // Check if customer already requested stock notification
        $alreadyRequested = false;
        if (\Illuminate\Support\Facades\Auth::guard('customer')->check()) {
            $alreadyRequested = \App\Models\ProductNotification::where('customer_id', \Illuminate\Support\Facades\Auth::guard('customer')->id())
                ->where('product_id', $product->id)
                ->where('status', 'pending')
                ->exists();
        }

        // Fetch related story videos
        $productStoryVideos = $product->storyVideos()
            ->with(['products' => function ($query) {
                $query->where('products.is_active', true);
            }])
            ->where('is_active', true)
            ->latest()
            ->get();

        return view('frontend.product-details', compact('product', 'materials', 'shapes', 'styles', 'sizes', 'colors', 'similarProducts', 'wishlistItem', 'categories', 'coupons', 'alreadyRequested', 'productStoryVideos'))->with(['pageclass' => 'hedersolution bg-1']);
    }

    /**
     * Check if pincode is available for delivery
     */
    public function checkPincode(Request $request)
    {
        $request->validate([
            'pincode' => 'required|string',
        ]);

        $pincode = \App\Models\Pincode::where('code', $request->pincode)
            ->where('status', 'active')
            ->first();

        if ($pincode) {
            return response()->json([
                'available' => true,
                'message' => '✅ Delivery available in this area'
            ]);
        }

        return response()->json([
            'available' => false,
            'message' => '❌ Delivery not available in this area'
        ]);
    }

    /**
     * Check Coupon Code
     */
    public function checkCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        $coupon = \App\Models\Coupon::where('code', $request->code)->first();

        if (!$coupon) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid coupon code.'
            ]);
        }

        if (!$coupon->isValid($request->amount)) {
            return response()->json([
                'valid' => false,
                'message' => 'Coupon requirements not met.'
            ]);
        }

        $discount = $coupon->calculateDiscount($request->amount);

        return response()->json([
            'valid' => true,
            'message' => 'Coupon applied successfully!',
            'discount' => $discount,
            'code' => $coupon->code,
        ]);
    }


    // About Us
    public function aboutView()
    {
        $categories = Category::where('home_category', true)->take(5)->get();
        return view('frontend.aboutus', compact('categories'))->with(['pageclass' => 'hedersolution bg-1']);
    }

    public function privacyPolicyView()
    {
        $categories = Category::where('home_category', true)->take(5)->get();
        return view('frontend.privacy-policy', compact('categories'))->with(['pageclass' => 'hedersolution bg-1']);
    }

    public function searchProduct(Request $request)
    {
        $search = $request->input('search');
        $products = collect();

        if ($search) {
            $products = Product::where('product_name', 'like', '%' . $search . '%')
                ->where('is_active', true)
                ->get();

            // Always sort by price low to high
            $products = $products->sortBy(function ($product) {
                return (float) str_replace(',', '', $product->display_price);
            });
        }

        // Fetch categories for layout if needed (mimicking other methods typically, 
        // though header usage of menuCategories suggests a provider, I'll pass categories just in case or if it's the same variable name misread)
        // Re-reading header: it uses $menuCategories.
        // I will wait for grep result to be sure, but standard practice is to pass what's needed.
        // For now I'll adding the method, and I will fix the variables if grep shows something different.

        $categories = Category::where('home_category', true)->take(5)->get();

        return view('frontend.search-product', compact('products', 'search', 'categories'))->with(['pageclass' => 'hedersolution bg-1']);
    }

    public function notifyProduct(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'phone_number' => 'required|string|max:20',
        ]);

        $customerId = \Illuminate\Support\Facades\Auth::guard('customer')->id();

        // Check if already exists for this phone or customer
        $query = \App\Models\ProductNotification::where('product_id', $request->product_id)
            ->where('status', 'pending');

        if ($customerId) {
            $query->where(function ($q) use ($customerId, $request) {
                $q->where('customer_id', $customerId)
                    ->orWhere('phone_number', $request->phone_number);
            });
        } else {
            $query->where('phone_number', $request->phone_number);
        }

        if ($query->exists()) {
            return response()->json([
                'success' => false,
                'already_exists' => true,
                'message' => 'You have already requested for this item; when it\'s available we will inform you.'
            ]);
        }

        \App\Models\ProductNotification::create([
            'product_id' => $request->product_id,
            'customer_id' => $customerId,
            'phone_number' => $request->phone_number,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you! We will notify you when this product is back in stock.'
        ]);
    }
}
