<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private function resolveCustomer(): Customer
    {
        return Customer::firstOrCreate(
            ['email' => auth()->user()->email],
            ['name'  => auth()->user()->name, 'phone' => '', 'address' => '']
        );
    }

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
            'customer_id'  => 'required|exists:customers,id',
            'product_id'   => 'required|array|min:1',
            'product_id.*' => 'required|exists:products,id',
            'quantity'     => 'required|array|min:1',
            'quantity.*'   => 'required|integer|min:1',
        ]);

        foreach ($request->product_id as $i => $productId) {
            $qty      = $request->quantity[$i];
            $existing = Cart::where('customer_id', $request->customer_id)
                            ->where('product_id', $productId)
                            ->first();

            if ($existing) {
                $existing->increment('quantity', $qty);
            } else {
                Cart::create([
                    'customer_id' => $request->customer_id,
                    'product_id'  => $productId,
                    'quantity'    => $qty,
                ]);
            }
        }

        $count = count($request->product_id);
        $label = $count > 1 ? "{$count} produk berhasil ditambahkan ke keranjang." : 'Item berhasil ditambahkan ke keranjang.';
        return redirect()->route('carts.index')->with('success', $label);
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

    public function customerIndex()
    {
        $customer = $this->resolveCustomer();
        $carts = Cart::with('product')
            ->where('customer_id', $customer->id)
            ->latest()->get();
        return view('carts.customer_index', compact('carts'));
    }

    public function customerCreate()
    {
        $products = Product::where('stock', '>', 0)->get();
        $orders   = \App\Models\Order::with('orderItems.product')
            ->where('customer_email', auth()->user()->email)
            ->latest()->get();
        return view('carts.customer_create', compact('products', 'orders'));
    }

    public function customerStore(Request $request)
    {
        $request->validate([
            'product_id'   => 'required|array|min:1',
            'product_id.*' => 'required|exists:products,id',
            'quantity'     => 'required|array|min:1',
            'quantity.*'   => 'required|integer|min:1',
        ]);

        $customer = $this->resolveCustomer();

        foreach ($request->product_id as $i => $productId) {
            $qty  = $request->quantity[$i];
            $size = $request->size[$i] ?? null;
            $existing = Cart::where('customer_id', $customer->id)
                            ->where('product_id', $productId)
                            ->where('size', $size)->first();
            if ($existing) {
                $existing->increment('quantity', $qty);
            } else {
                Cart::create(['customer_id' => $customer->id, 'product_id' => $productId, 'quantity' => $qty, 'size' => $size]);
            }
        }

        return redirect()->route('customer.carts')->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function customerDestroy(string $id)
    {
        $customer = $this->resolveCustomer();
        $cart     = Cart::where('id', $id)->where('customer_id', $customer->id)->firstOrFail();
        $cart->delete();
        return redirect()->route('customer.carts')->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    public function quickAdd(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'size'       => 'nullable|string|max:20',
            'quantity'   => 'nullable|integer|min:1|max:99',
        ]);

        $customer = $this->resolveCustomer();

        $product = Product::findOrFail($request->product_id);
        $qty     = (int) $request->input('quantity', 1);

        if ($product->stock < 1) {
            return back()->withErrors(["Stok produk \"{$product->name}\" sudah habis."]);
        }
        if ($qty > $product->stock) {
            return back()->withErrors(["Jumlah melebihi stok yang tersedia (tersisa {$product->stock})."]);
        }

        $existing = Cart::where('customer_id', $customer->id)
            ->where('product_id', $request->product_id)
            ->where('size', $request->size)
            ->first();

        if ($existing) {
            $existing->increment('quantity', $qty);
        } else {
            Cart::create([
                'customer_id' => $customer->id,
                'product_id'  => $request->product_id,
                'quantity'    => $qty,
                'size'        => $request->size,
            ]);
        }

        $sizeLabel = $request->size ? " (ukuran {$request->size})" : '';
        $msg = "🛒 {$product->name}{$sizeLabel} ditambahkan ke keranjang!";

        // "Beli Langsung" → langsung ke keranjang
        if ($request->has('buy_now')) {
            return redirect()->route('customer.carts')->with('success', $msg);
        }
        return back()->with('success', $msg);
    }

    public function checkout(Request $request)
    {
        $customer = $this->resolveCustomer();
        $carts    = Cart::with('product')->where('customer_id', $customer->id)->get();

        if ($carts->isEmpty()) {
            return back()->withErrors(['cart' => 'Keranjang kosong. Tambahkan produk terlebih dahulu.']);
        }

        $cartQuantities = $request->input('cart_quantities', []);
        $total = 0;
        $items = [];

        foreach ($carts as $cart) {
            if (!$cart->product) continue;
            $qty = isset($cartQuantities[$cart->id]) ? max(1, (int) $cartQuantities[$cart->id]) : $cart->quantity;
            if ($qty > $cart->product->stock) {
                return back()->withErrors([
                    'cart' => "Stok \"{$cart->product->name}\" tidak mencukupi (tersisa {$cart->product->stock})."
                ]);
            }
            $subtotal = $cart->product->price * $qty;
            $total   += $subtotal;
            $items[]  = ['cart' => $cart, 'qty' => $qty, 'subtotal' => $subtotal];
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

        if (empty($items)) {
            return back()->withErrors(['cart' => 'Tidak ada produk valid di keranjang.']);
        }

        $finalTotal = $total - $discountAmount;
        $first      = $items[0];

        $order = Order::create([
            'customer_name'   => auth()->user()->name,
            'customer_email'  => auth()->user()->email,
            'product_id'      => $first['cart']->product_id,
            'quantity'        => $first['qty'],
            'total_price'     => $finalTotal,
            'voucher_code'    => $voucherCode,
            'discount_amount' => $discountAmount,
            'status'          => 'pending',
        ]);

        foreach ($items as $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item['cart']->product_id,
                'quantity'   => $item['qty'],
                'size'       => $item['cart']->size,
                'price'      => $item['cart']->product->price,
                'subtotal'   => $item['subtotal'],
            ]);
            $item['cart']->product->decrement('stock', $item['qty']);
        }

        Cart::where('customer_id', $customer->id)->delete();

        return redirect()->route('customer.orders.show', $order->id)
            ->with('success', '🎉 Pesanan berhasil dibuat! Silakan lakukan pembayaran.');
    }
}
