<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function index()
    {
        $orderItems = OrderItem::with(['order', 'product'])->latest()->paginate(10);
        return view('order_items.index', compact('orderItems'));
    }

    public function create()
    {
        $orders   = Order::all();
        $products = Product::where('stock', '>', 0)->get();
        return view('order_items.create', compact('orders', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id'     => 'required|exists:orders,id',
            'product_id'   => 'required|array|min:1',
            'product_id.*' => 'required|exists:products,id',
            'quantity'     => 'required|array|min:1',
            'quantity.*'   => 'required|integer|min:1',
        ]);

        foreach ($request->product_id as $i => $productId) {
            $product  = Product::findOrFail($productId);
            $qty      = $request->quantity[$i];
            $subtotal = $product->price * $qty;

            OrderItem::create([
                'order_id'   => $request->order_id,
                'product_id' => $productId,
                'quantity'   => $qty,
                'price'      => $product->price,
                'subtotal'   => $subtotal,
            ]);
        }

        $count = count($request->product_id);
        $label = $count > 1 ? "{$count} item berhasil ditambahkan." : 'Item pesanan berhasil ditambahkan.';
        return redirect()->route('order-items.index')->with('success', $label);
    }

    public function show(string $id)
    {
        $orderItem = OrderItem::with(['order', 'product'])->findOrFail($id);
        return view('order_items.show', compact('orderItem'));
    }

    public function edit(string $id)
    {
        $orderItem = OrderItem::findOrFail($id);
        $orders    = Order::all();
        $products  = Product::all();
        return view('order_items.edit', compact('orderItem', 'orders', 'products'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'order_id'   => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $orderItem = OrderItem::findOrFail($id);
        $product   = Product::findOrFail($request->product_id);
        $subtotal  = $product->price * $request->quantity;

        $orderItem->update([
            'order_id'   => $request->order_id,
            'product_id' => $request->product_id,
            'quantity'   => $request->quantity,
            'price'      => $product->price,
            'subtotal'   => $subtotal,
        ]);

        return redirect()->route('order-items.index')->with('success', 'Item pesanan berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $orderItem = OrderItem::findOrFail($id);
        $orderItem->delete();
        return redirect()->route('order-items.index')->with('success', 'Item pesanan berhasil dihapus.');
    }
}
