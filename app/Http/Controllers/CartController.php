<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::with(['customer', 'product'])->latest()->paginate(10);
        return view('carts.index', compact('carts'));
    }

    public function create()
    {
        $customers = Customer::all();
        $products  = Product::where('stock', '>', 0)->get();
        return view('carts.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id'  => 'required|exists:products,id',
            'quantity'    => 'required|integer|min:1',
        ]);

        $existing = Cart::where('customer_id', $request->customer_id)
                        ->where('product_id', $request->product_id)
                        ->first();

        if ($existing) {
            $existing->increment('quantity', $request->quantity);
        } else {
            Cart::create($request->all());
        }

        return redirect()->route('carts.index')->with('success', 'Item berhasil ditambahkan ke keranjang.');
    }

    public function show(string $id)
    {
        $cart = Cart::with(['customer', 'product'])->findOrFail($id);
        return view('carts.show', compact('cart'));
    }

    public function edit(string $id)
    {
        $cart      = Cart::findOrFail($id);
        $customers = Customer::all();
        $products  = Product::all();
        return view('carts.edit', compact('cart', 'customers', 'products'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id'  => 'required|exists:products,id',
            'quantity'    => 'required|integer|min:1',
        ]);

        $cart = Cart::findOrFail($id);
        $cart->update($request->all());
        return redirect()->route('carts.index')->with('success', 'Keranjang berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $cart = Cart::findOrFail($id);
        $cart->delete();
        return redirect()->route('carts.index')->with('success', 'Item berhasil dihapus dari keranjang.');
    }
}
