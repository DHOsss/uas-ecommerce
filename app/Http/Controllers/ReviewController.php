<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
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

    public function customerIndex()
    {
        $reviews = Review::with('product')
            ->where('customer_name', auth()->user()->name)
            ->latest()->paginate(10);
        return view('reviews.customer_index', compact('reviews'));
    }

    public function customerCreate()
    {
        // Ambil product_id dari semua order milik customer ini
        $orderedProductIds = OrderItem::whereHas('order', function ($q) {
            $q->where('customer_email', auth()->user()->email);
        })->pluck('product_id')->unique();

        // Produk yang sudah pernah diulas oleh customer ini
        $reviewedProductIds = Review::where('customer_name', auth()->user()->name)
            ->pluck('product_id');

        // Hanya produk yang sudah dipesan dan belum diulas
        $products = Product::whereIn('id', $orderedProductIds)
            ->whereNotIn('id', $reviewedProductIds)
            ->get();

        return view('reviews.customer_create', compact('products'));
    }

    public function customerStore(Request $request)
    {
        // Validasi produk memang pernah dipesan customer ini
        $orderedProductIds = OrderItem::whereHas('order', function ($q) {
            $q->where('customer_email', auth()->user()->email);
        })->pluck('product_id');

        $request->validate([
            'product_id' => ['required', 'exists:products,id', function ($attr, $value, $fail) use ($orderedProductIds) {
                if (!$orderedProductIds->contains($value)) {
                    $fail('Kamu hanya bisa mengulas produk yang sudah kamu pesan.');
                }
            }],
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        Review::create([
            'product_id'    => $request->product_id,
            'customer_name' => auth()->user()->name,
            'rating'        => $request->rating,
            'comment'       => $request->comment,
        ]);

        return redirect()->route('customer.reviews')->with('success', 'Ulasan berhasil ditambahkan.');
    }
}
