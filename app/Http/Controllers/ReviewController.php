<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with('product')->latest()->paginate(10);
        return view('reviews.index', compact('reviews'));
    }

    public function create()
    {
        $products = Product::all();
        return view('reviews.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id'    => 'required|exists:products,id',
            'customer_name' => 'required|string|max:255',
            'rating'        => 'required|integer|min:1|max:5',
            'comment'       => 'nullable|string',
        ]);

        Review::create($request->all());
        return redirect()->route('reviews.index')->with('success', 'Ulasan berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $review = Review::with('product')->findOrFail($id);
        return view('reviews.show', compact('review'));
    }

    public function edit(string $id)
    {
        $review   = Review::findOrFail($id);
        $products = Product::all();
        return view('reviews.edit', compact('review', 'products'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'product_id'    => 'required|exists:products,id',
            'customer_name' => 'required|string|max:255',
            'rating'        => 'required|integer|min:1|max:5',
            'comment'       => 'nullable|string',
        ]);

        $review = Review::findOrFail($id);
        $review->update($request->all());
        return redirect()->route('reviews.index')->with('success', 'Ulasan berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $review = Review::findOrFail($id);
        $review->delete();
        return redirect()->route('reviews.index')->with('success', 'Ulasan berhasil dihapus.');
    }
}
