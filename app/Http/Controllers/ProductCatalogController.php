<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductCatalogController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->active()
            ->latest()
            ->get();

        $categories = Category::all();

        return view('shop.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        if (!$product->is_active) {
            abort(404);
        }

        $product->load('category');

        return view('shop.show', compact('product'));
    }
}
