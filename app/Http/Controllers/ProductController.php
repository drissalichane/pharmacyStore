<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('manufacturer', 'like', "%{$search}%")
                  ->orWhereHas('category', function($categoryQuery) use ($search) {
                      $categoryQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Category filter (now includes subcategories)
        if ($request->filled('category')) {
            $category = Category::find($request->category);
            if ($category) {
                $subcategoryIds = $category->getAllSubcategoryIds();
                $query->whereIn('category_id', $subcategoryIds);
            }
        }

        // Brand filter
        if ($request->filled('brand')) {
            $query->where('brand_id', $request->brand);
        }

        // Sort functionality
        switch ($request->get('sort', 'newest')) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->get();
        $brands = Brand::where('is_active', true)->get();

        return view('products.index', compact('products', 'categories', 'brands'));
    }

    public function show(Product $product)
    {
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        $breadcrumb = $product->breadcrumb;

        return view('products.show', compact('product', 'relatedProducts', 'breadcrumb'));
    }

    public function category(Category $category)
    {
        $subcategoryIds = $category->getAllSubcategoryIds();

        $products = Product::whereIn('category_id', $subcategoryIds)
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $categories = Category::where('is_active', true)->get();

        // Get brands for this category tree
        $brands = $category->brandsInTree()->get();

        return view('products.category', compact('products', 'categories', 'category', 'brands'));
    }

    public function getBrandsForCategory(Category $category)
    {
        $brands = $category->brandsInTree()
            ->withCount(['products' => function ($query) use ($category) {
                $subcategoryIds = $category->getAllSubcategoryIds();
                $query->whereIn('category_id', $subcategoryIds)
                      ->where('is_active', true)
                      ->where('stock_quantity', '>', 0);
            }])
            ->having('products_count', '>', 0)
            ->orderBy('name')
            ->get();

        return response()->json($brands);
    }
}
