<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $query = Order::with('product')->latest();

        if (auth()->user()->isCustomer()) {
            $query->where('customer_email', auth()->user()->email);
        }

        $orders = $query->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $products  = Product::where('stock', '>', 0)->get();
        $customers = Customer::orderBy('name')->get();
        return view('orders.create', compact('products', 'customers'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->isAdmin()) {
            $request->validate([
                'customer_id'  => 'required|exists:customers,id',
                'product_id'   => 'required|array|min:1',
                'product_id.*' => 'required|exists:products,id',
                'quantity'     => 'required|array|min:1',
                'quantity.*'   => 'required|integer|min:1',
            ]);
            $customer = Customer::findOrFail($request->customer_id);
        } else {
            $request->validate([
                'product_id'   => 'required|array|min:1',
                'product_id.*' => 'required|exists:products,id',
                'quantity'     => 'required|array|min:1',
                'quantity.*'   => 'required|integer|min:1',
            ]);
            $customer = (object) [
                'name'  => auth()->user()->name,
                'email' => auth()->user()->email,
            ];
        }
        $total    = 0;
        $items    = [];

        foreach ($request->product_id as $i => $productId) {
            $product  = Product::findOrFail($productId);
            $qty      = $request->quantity[$i];

            if ($qty > $product->stock) {
                return back()->withErrors([
                    'product_id' => "Stok produk \"{$product->name}\" tidak mencukupi (tersisa {$product->stock})."
                ])->withInput();
            }

            $subtotal = $product->price * $qty;
            $total   += $subtotal;
            $items[]  = ['product' => $product, 'qty' => $qty, 'size' => $request->size[$i] ?? null, 'price' => $product->price, 'subtotal' => $subtotal];
        }

        // Proses voucher
        $discountAmount = 0;
        $voucherCode    = null;
        if ($request->filled('voucher_code')) {
            $voucher = Voucher::where('code', $request->voucher_code)
                ->where('is_active', true)
                ->where('expired_at', '>=', now()->toDateString())
                ->first();

            if ($voucher && $total >= $voucher->min_purchase) {
                $discountAmount = round($total * $voucher->discount / 100, 2);
                $voucherCode    = $voucher->code;
            }
        }

        $finalTotal = $total - $discountAmount;

        $order = Order::create([
            'customer_name'   => $customer->name,
            'customer_email'  => $customer->email,
            'product_id'      => $items[0]['product']->id,
            'quantity'        => $items[0]['qty'],
            'total_price'     => $finalTotal,
            'voucher_code'    => $voucherCode,
            'discount_amount' => $discountAmount,
            'status'          => 'pending',
        ]);

        foreach ($items as $item) {
            \App\Models\OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item['product']->id,
                'quantity'   => $item['qty'],
                'size'       => $item['size'],
                'price'      => $item['price'],
                'subtotal'   => $item['subtotal'],
            ]);
            $item['product']->decrement('stock', $item['qty']);
        }

        $route = auth()->user()->isAdmin() ? 'orders.index' : 'customer.orders';
        return redirect()->route($route)->with('success', 'Pesanan berhasil dibuat.');
    }

    public function show(string $id)
    {
        $order = Order::with('orderItems.product')->findOrFail($id);
        return view('orders.show', compact('order'));
    }

    public function edit(string $id)
    {
        $order = Order::findOrFail($id);
        return view('orders.edit', compact('order'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);
        return redirect()->route('orders.index')->with('success', 'Status pesanan diperbarui.');
    }

    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dihapus.');
    }

    public function buyNow(Request $request)
    {
        $request->validate([
            'product_id'     => 'required|exists:products,id',
            'size'           => 'nullable|string|max:20',
            'quantity'       => 'required|integer|min:1|max:99',
            'payment_method' => 'required|in:transfer_bank,qris,cod',
        ]);

        $customer = Customer::firstOrCreate(
            ['email' => auth()->user()->email],
            ['name'  => auth()->user()->name, 'phone' => '', 'address' => '']
        );

        $product = Product::findOrFail($request->product_id);
        $qty     = (int) $request->quantity;

        if ($qty > $product->stock) {
            return back()->withErrors(["Stok \"{$product->name}\" tidak mencukupi (tersisa {$product->stock})."]);
        }

        $subtotal = $product->price * $qty;

        $order = Order::create([
            'customer_name'  => auth()->user()->name,
            'customer_email' => auth()->user()->email,
            'product_id'     => $product->id,
            'quantity'       => $qty,
            'total_price'    => $subtotal,
            'status'         => 'pending',
        ]);

        OrderItem::create([
            'order_id'   => $order->id,
            'product_id' => $product->id,
            'quantity'   => $qty,
            'size'       => $request->size,
            'price'      => $product->price,
            'subtotal'   => $subtotal,
        ]);

        $product->decrement('stock', $qty);

        $payment = Payment::create([
            'order_id' => $order->id,
            'amount'   => $subtotal,
            'method'   => $request->payment_method,
            'status'   => 'pending',
        ]);

        return redirect()->route('customer.payments.show', $payment->id)
            ->with('success', '🎉 Pesanan berhasil dibuat! Silakan selesaikan pembayaran.');
    }
}
