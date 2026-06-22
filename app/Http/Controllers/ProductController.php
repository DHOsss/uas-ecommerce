<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::latest();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $products   = $query->paginate(12)->withQueryString();
        $categories = Product::select('category')->whereNotNull('category')->distinct()->orderBy('category')->pluck('category');

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|array|min:1',
            'name.*'      => 'required|string|max:255',
            'price'       => 'required|array|min:1',
            'price.*'     => 'required|numeric|min:0',
            'stock'       => 'required|array|min:1',
            'stock.*'     => 'required|integer|min:0',
            'description' => 'nullable|array',
            'category'    => 'nullable|array',
        ]);

        $count = count($request->name);
        for ($i = 0; $i < $count; $i++) {
            Product::create([
                'name'        => $request->name[$i],
                'description' => $request->description[$i] ?? null,
                'price'       => $request->price[$i],
                'stock'       => $request->stock[$i],
                'category'    => $request->category[$i] ?? null,
            ]);
        }

        $label = $count > 1 ? "{$count} produk berhasil ditambahkan." : 'Produk berhasil ditambahkan.';
        return redirect()->route('products.index')->with('success', $label);
    }

    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::orderBy('name')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'price'    => 'required|numeric|min:0',
            'stock'    => 'required|integer|min:0',
            'category' => 'nullable|string|max:100',
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->all());
        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
