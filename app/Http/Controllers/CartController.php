<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        $products = Product::whereIn('id', array_keys($cart))->get();

        $total = 0;

        foreach ($products as $product) {
            $total += $product->price * $cart[$product->id];
        }

        return view('cart.index', compact('cart', 'products', 'total'));
    }

    public function store(Request $request, Product $product)
    {
        $cart = session()->get('cart', []);

        $cart[$product->id] = ($cart[$product->id] ?? 0) + 1;

        session()->put('cart', $cart);

        return redirect()->route('cart.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id] = $request->quantity;
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index');
    }

    public function destroy(Product $product)
    {
        $cart = session()->get('cart', []);

        unset($cart[$product->id]);

        session()->put('cart', $cart);

        return redirect()->route('cart.index');
    }
}