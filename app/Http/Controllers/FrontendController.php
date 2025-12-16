<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Product;

class FrontendController extends Controller
{
    public function home(Request $request)
    {
        $categories = Category::all();
        $selectedCategoryId = $request->query('category_id');

        if ($selectedCategoryId) {
            $selectedCategory = Category::find($selectedCategoryId);
            // If selected category not found, fallback to first
            if (!$selectedCategory && $categories->isNotEmpty()) {
                $selectedCategory = $categories->first();
            }
        } else {
            $selectedCategory = $categories->first();
        }

        $products = collect();
        if ($selectedCategory) {
            $products = Product::where('category_id', $selectedCategory->id)->take(10)->get();
        }

        return view('frontend.home', compact('categories', 'selectedCategory', 'products'));
    }
}
