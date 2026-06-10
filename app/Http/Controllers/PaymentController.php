<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('order')->latest()->paginate(10);
        return view('payments.index', compact('payments'));
    }

    public function create()
    {
        $orders = Order::all();
        return view('payments.create', compact('orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount'   => 'required|numeric|min:0',
            'method'   => 'required|in:transfer,cash,ewallet',
            'status'   => 'required|in:pending,paid,failed',
        ]);

        $data = $request->all();
        if ($request->status === 'paid') {
            $data['paid_at'] = now();
        }

        Payment::create($data);
        return redirect()->route('payments.index')->with('success', 'Pembayaran berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $payment = Payment::with('order')->findOrFail($id);
        return view('payments.show', compact('payment'));
    }

    public function edit(string $id)
    {
        $payment = Payment::findOrFail($id);
        $orders  = Order::all();
        return view('payments.edit', compact('payment', 'orders'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount'   => 'required|numeric|min:0',
            'method'   => 'required|in:transfer,cash,ewallet',
            'status'   => 'required|in:pending,paid,failed',
        ]);

        $payment = Payment::findOrFail($id);
        $data    = $request->all();

        if ($request->status === 'paid' && !$payment->paid_at) {
            $data['paid_at'] = now();
        }

        $payment->update($data);
        return redirect()->route('payments.index')->with('success', 'Pembayaran berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Pembayaran berhasil dihapus.');
    }

    public function updateStatus(Request $request, string $id)
    {
        $request->validate(['status' => 'required|in:pending,paid,failed']);

        $payment = Payment::findOrFail($id);
        $data    = ['status' => $request->status];

        if ($request->status === 'paid' && !$payment->paid_at) {
            $data['paid_at'] = now();
        } elseif ($request->status !== 'paid') {
            $data['paid_at'] = null;
        }

        $payment->update($data);
        return redirect()->route('payments.index')->with('success', 'Status pembayaran berhasil diubah.');
    }

    // ── Customer methods ──────────────────────────────────────────────────────

    public function customerIndex()
    {
        $payments = Payment::with('order')
            ->whereHas('order', fn($q) => $q->where('customer_email', auth()->user()->email))
            ->latest()
            ->paginate(10);

        return view('customer.payments.index', compact('payments'));
    }

    public function customerCreate()
    {
        // Hanya order milik customer yang belum lunas
        $paidOrderIds = Payment::where('status', 'paid')->pluck('order_id');

        $orders = Order::where('customer_email', auth()->user()->email)
            ->whereNotIn('id', $paidOrderIds)
            ->latest()
            ->get();

        return view('customer.payments.create', compact('orders'));
    }

    public function customerStore(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'method'   => 'required|in:transfer,cash,ewallet',
        ]);

        // Pastikan order milik customer yang login
        $order = Order::where('id', $request->order_id)
            ->where('customer_email', auth()->user()->email)
            ->firstOrFail();

        // Cegah bayar ulang jika sudah paid
        $alreadyPaid = Payment::where('order_id', $order->id)->where('status', 'paid')->exists();
        if ($alreadyPaid) {
            return back()->withErrors(['order_id' => 'Pesanan ini sudah lunas.']);
        }

        Payment::create([
            'order_id' => $order->id,
            'amount'   => $order->total_price,
            'method'   => $request->method,
            'status'   => 'pending',
        ]);

        return redirect()->route('customer.payments')->with('success', 'Pembayaran berhasil diajukan, menunggu konfirmasi admin.');
    }

    public function customerShow(string $id)
    {
        $payment = Payment::with('order')
            ->whereHas('order', fn($q) => $q->where('customer_email', auth()->user()->email))
            ->findOrFail($id);

        return view('customer.payments.show', compact('payment'));
    }
}
