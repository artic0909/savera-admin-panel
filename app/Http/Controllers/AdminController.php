<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\Material;
use App\Models\WhyChoose;

class AdminController extends Controller
{

    // Admin Login View
    public function adminLoginView()
    {
        return view('admin.admin-login');
    }

    // Admin Login
    public function adminLogin(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);

        if (Auth::guard('admin')->attempt([
            'email'    => $validated['email'],
            'password' => $validated['password'],
        ])) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard')
                ->with('success', 'Welcome Admin!');
        }

        return back()->withErrors([
            'error' => 'Invalid email or password.',
        ])->withInput();
    }

    // Admin Logout
    public function adminLogout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'You have been logged out.');
    }

    // Admin Dashboard View
    public function adminDashboardView(Request $request)
    {
        return view('admin.admin-dashboard');
    }




    // Admin Category ==================================================================================================================================>
    public function adminCategoriesView()
    {
        $categories = Category::orderBy('id', 'desc')->paginate(8);

        return view('admin.category.index', compact('categories'));
    }

    public function categoryStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'  => 'required|string|max:255',
                'image' => 'required|image|mimes:jpg,jpeg,png,gif,svg,webp',
            ]);

            $imagePath = null;

            if ($request->hasFile('image')) {
                $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();

                $request->image->move(public_path('category'), $imageName);

                $imagePath = 'category/' . $imageName;
            }

            $slug = Str::slug($validated['name']);
            $count = Category::where('slug', 'LIKE', "$slug%")->count();
            if ($count > 0) {
                $slug = $slug . '-' . ($count + 1);
            }

            $isMenu = $request->has('menu') ? true : false;
            $isHomeCategory = $request->has('home_category') ? true : false;
            $isFooter = $request->has('footer') ? true : false;

            if ($isMenu) {
                if (Category::where('menu', true)->count() >= 8) {
                    return redirect()->back()->with('error', 'You can only have 8 categories in the menu.');
                }
            }

            if ($isHomeCategory) {
                if (Category::where('home_category', true)->count() >= 5) {
                    return redirect()->back()->with('error', 'You can only have 5 categories in the home page.');
                }
            }

            Category::create([
                'name'  => $validated['name'],
                'slug'  => $slug,
                'image' => $imagePath,
                'menu'  => $isMenu,
                'home_category' => $isHomeCategory,
                'footer' => $isFooter,
            ]);

            return redirect()->back()->with('success', 'Category created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function categoryEdit($id)
    {
        try {
            $category = Category::findOrFail($id);
            return response()->json([
                'success' => true,
                'category' => $category
            ]);
        } catch (\Exception $e) {
            Log::error('Category Edit Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Category not found!'
            ], 404);
        }
    }

    public function categoryUpdate(Request $request, $id)
    {
        try {
            $category = Category::findOrFail($id);

            $validated = $request->validate([
                'name'  => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg,webp',
            ]);

            $imagePath = $category->image;

            if ($request->hasFile('image')) {
                if ($category->image && file_exists(public_path($category->image))) {
                    unlink(public_path($category->image));
                }

                $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
                $request->image->move(public_path('category'), $imageName);
                $imagePath = 'category/' . $imageName;
            }

            $slug = $category->slug;
            if ($category->name !== $validated['name']) {
                $slug = Str::slug($validated['name']);
                $count = Category::where('slug', 'LIKE', "$slug%")
                    ->where('id', '!=', $id)
                    ->count();
                if ($count > 0) {
                    $slug = $slug . '-' . ($count + 1);
                }
            }

            $isMenu = $request->has('menu') ? true : false;
            $isHomeCategory = $request->has('home_category') ? true : false;
            $isFooter = $request->has('footer') ? true : false;

            if ($isMenu) {
                if (Category::where('menu', true)->where('id', '!=', $id)->count() >= 8) {
                    return redirect()->back()->with('error', 'You can only have 8 categories in the menu.');
                }
            }

            if ($isHomeCategory) {
                if (Category::where('home_category', true)->where('id', '!=', $id)->count() >= 5) {
                    return redirect()->back()->with('error', 'You can only have 5 categories in the home page.');
                }
            }

            $category->update([
                'name'  => $validated['name'],
                'slug'  => $slug,
                'image' => $imagePath,
                'menu'  => $isMenu,
                'home_category' => $isHomeCategory,
                'footer' => $isFooter,
            ]);

            return redirect()->back()->with('success', 'Category updated successfully');
        } catch (\Exception $e) {
            Log::error('Category Update Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function categoryDelete($id)
    {
        try {
            $category = Category::findOrFail($id);

            if ($category->image && file_exists(public_path($category->image))) {
                unlink(public_path($category->image));
            }

            $category->delete();

            return redirect()->back()->with('success', 'Category deleted successfully');
        } catch (\Exception $e) {
            Log::error('Category Delete Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }


    // Material ==================================================================================================================================>
    public function adminMaterialsView()
    {
        $materials = Material::orderBy('id', 'desc')->paginate(10);
        return view('admin.material.index', compact('materials'));
    }

    public function materialStore(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric',
            ]);

            Material::create([
                'name' => $request->name,
                'price' => $request->price,
            ]);

            return redirect()->back()->with('success', 'Material added successfully');
        } catch (\Exception $e) {
            Log::error('Material Store Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function materialUpdate(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric',
            ]);

            $material = Material::findOrFail($id);
            $material->update([
                'name' => $request->name,
                'price' => $request->price,
            ]);

            return redirect()->back()->with('success', 'Material updated successfully');
        } catch (\Exception $e) {
            Log::error('Material Update Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function materialDelete($id)
    {
        try {
            $material = Material::findOrFail($id);
            $material->delete();

            return redirect()->back()->with('success', 'Material deleted successfully');
        } catch (\Exception $e) {
            Log::error('Material Delete Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }


    // Admin Products ==================================================================================================================================>
    public function addProductView()
    {
        return view('admin.product.add');
    }

    public function editProductView()
    {
        return view('admin.product.edit');
    }

    public function indexProductView()
    {
        return view('admin.product.index');
    }




    // Why Choose ===================================================================================================================================>
    public function adminWhyChooseView()
    {
        $whyChooses = WhyChoose::get();
        return view('admin.whychoose.index', compact('whyChooses'));
    }

    public function whyChooseStore(Request $request)
    {
        try {
            // Check if already 4 images exist
            if (WhyChoose::count() >= 4) {
                return redirect()->back()->with('error', 'Maximum 4 images allowed!');
            }

            $request->validate([
                'image' => 'required|image|mimes:jpg,jpeg,png,gif,svg,webp|max:2048',
            ]);

            $imagePath = null;

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('whychoose', 'public');
            }

            WhyChoose::create([
                'image' => $imagePath,
            ]);

            return redirect()->back()->with('success', 'Image added successfully');
        } catch (\Exception $e) {
            Log::error('WhyChoose Store Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function whyChooseUpdate(Request $request, $id)
    {
        try {
            $request->validate([
                'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg,webp|max:2048',
            ]);

            $whyChoose = WhyChoose::findOrFail($id);
            $imagePath = $whyChoose->image;

            if ($request->hasFile('image')) {
                // Delete old image
                if ($whyChoose->image && \Storage::disk('public')->exists($whyChoose->image)) {
                    \Storage::disk('public')->delete($whyChoose->image);
                }

                $imagePath = $request->file('image')->store('whychoose', 'public');
            }

            $whyChoose->update([
                'image' => $imagePath,
            ]);

            return redirect()->back()->with('success', 'Image updated successfully');
        } catch (\Exception $e) {
            Log::error('WhyChoose Update Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function whyChooseDelete($id)
    {
        try {
            $whyChoose = WhyChoose::findOrFail($id);

            // Delete image from storage
            if ($whyChoose->image && \Storage::disk('public')->exists($whyChoose->image)) {
                \Storage::disk('public')->delete($whyChoose->image);
            }

            $whyChoose->delete();

            return redirect()->back()->with('success', 'Image deleted successfully');
        } catch (\Exception $e) {
            Log::error('WhyChoose Delete Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }




    // Profile ==================================================================================================================================>
    public function adminProfileView()
    {
        return view('admin.profile.index');
    }

    public function adminUpdateProfile(Request $request)
    {
        try {
            $request->validate([
                'name'     => 'nullable|string|max:255',
                'password' => 'nullable|string|min:8|confirmed',
            ]);

            $user = Auth::guard('admin')->user();

            if (!$user) {
                return redirect()->back()->with('error', 'Admin not authenticated.');
            }

            if ($request->filled('name')) {
                $user->name = $request->name;
            }

            if ($request->filled('password')) {
                $user->password = $request->password;
            }

            $user->save();

            return redirect()->back()->with('success', 'Profile Updated Successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Admin profile update error: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }
}
