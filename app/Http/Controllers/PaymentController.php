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
}
