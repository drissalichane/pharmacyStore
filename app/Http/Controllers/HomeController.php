<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch first 20 active products with their categories and brands
        $featuredProducts = Product::active()
            ->with(['category', 'brand'])
            ->take(20)
            ->get();

        // Fetch first 20 active brands
        $featuredBrands = Brand::where('is_active', true)
            ->take(20)
            ->get();

        return view('welcome', compact('featuredProducts', 'featuredBrands'));
    }
}
