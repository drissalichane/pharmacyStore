<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category', 'brand');

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('dosage_form', 'like', '%' . $search . '%')
                  ->orWhere('strength', 'like', '%' . $search . '%');
            });
        }

        // Flexible pagination
        $perPage = $request->get('per_page', 15);
        $perPage = in_array($perPage, [10, 15, 25, 50, 100]) ? $perPage : 15;

        $products = $query->latest()->paginate($perPage);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $rootCategories = Category::where('is_root', true)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        $brands = Brand::all();
        return view('admin.products.create', compact('rootCategories', 'brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // Change category_id validation to nullable to allow root only selection
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'description' => 'nullable|string',
            'manufacturer' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'dosage_form' => 'nullable|string|max:255',
            'strength' => 'nullable|string|max:255',
            'ingredients' => 'nullable|string',
            'usage_instructions' => 'nullable|string',
            'side_effects' => 'nullable|string'
        ]);

        // Adjust validation for category_id to allow root category only
        $categoryId = $request->input('category_id');

        // If category_id is empty or zero, fallback to root_category dropdown value
        if (empty($categoryId) || $categoryId === '0') {
            $categoryId = $request->input('root_category');
        }

        if (empty($categoryId)) {
            return redirect()->back()->withErrors(['category_id' => 'Please select a category.'])->withInput();
        }

        $category = \App\Models\Category::find($categoryId);
        if ($category && !$category->is_root) {
            // If selected category is not root, check if its parent is root
            if (!$category->parent || !$category->parent->is_root) {
                return redirect()->back()->withErrors(['category_id' => 'Please select a valid root category or its immediate subcategory.'])->withInput();
            }
        }

        // Check for duplicate product by name, dosage_form, strength, and category_id
        $existingProduct = Product::where('name', $request->name)
            ->where('dosage_form', $request->dosage_form)
            ->where('strength', $request->strength)
            ->where('category_id', $categoryId)
            ->first();

        if ($existingProduct) {
            return redirect()->back()->withErrors(['name' => 'A product with the same name, dosage form, strength, and category already exists.'])->withInput();
        }

        $data = $request->all();
        $data['requires_prescription'] = $request->has('requires_prescription');

        // Generate unique slug
        $baseSlug = \Illuminate\Support\Str::slug($request->name);
        $slug = $baseSlug;
        $counter = 1;

        while (Product::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $data['slug'] = $slug;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = Storage::url($imagePath);
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }

    public function show(Product $product)
    {
        $product->load('category', 'brand');

        // Get category hierarchy for the product
        $categoryHierarchy = $this->getCategoryHierarchy($product->category_id);

        return view('admin.products.show', compact('product', 'categoryHierarchy'));
    }

    public function edit(Product $product)
    {
        $rootCategories = Category::where('is_root', true)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        $brands = Brand::all();

        // Get category hierarchy for the product
        $categoryHierarchy = $this->getCategoryHierarchy($product->category_id);

        return view('admin.products.edit', compact('product', 'rootCategories', 'brands', 'categoryHierarchy'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'description' => 'nullable|string',
            'manufacturer' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'dosage_form' => 'nullable|string|max:255',
            'strength' => 'nullable|string|max:255',
            'ingredients' => 'nullable|string',
            'usage_instructions' => 'nullable|string',
            'side_effects' => 'nullable|string'
        ]);

        $data = $request->all();
        $data['requires_prescription'] = $request->has('requires_prescription');

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image && Storage::disk('public')->exists(str_replace('/storage/', '', $product->image))) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $product->image));
            }

            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = Storage::url($imagePath);
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        // Delete image if exists
        if ($product->image && Storage::disk('public')->exists(str_replace('/storage/', '', $product->image))) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $product->image));
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
    }

    private function getCategoryHierarchy($categoryId)
    {
        $hierarchy = [];
        $category = Category::find($categoryId);

        if (!$category) {
            return $hierarchy;
        }

        $current = $category;
        while ($current) {
            array_unshift($hierarchy, $current);
            $current = $current->parent;
        }

        return $hierarchy;
    }
}
