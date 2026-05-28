<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::latest()->paginate(10);
        return view('vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        return view('vouchers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'         => 'required|string|unique:vouchers,code',
            'discount'     => 'required|numeric|min:1|max:100',
            'min_purchase' => 'required|numeric|min:0',
            'expired_at'   => 'required|date|after:today',
        ]);

        Voucher::create(array_merge($request->all(), [
            'is_active' => $request->has('is_active'),
        ]));

        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $voucher = Voucher::findOrFail($id);
        return view('vouchers.show', compact('voucher'));
    }

    public function edit(string $id)
    {
        $voucher = Voucher::findOrFail($id);
        return view('vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'code'         => 'required|string|unique:vouchers,code,' . $id,
            'discount'     => 'required|numeric|min:1|max:100',
            'min_purchase' => 'required|numeric|min:0',
            'expired_at'   => 'required|date',
        ]);

        $voucher = Voucher::findOrFail($id);
        $voucher->update(array_merge($request->all(), [
            'is_active' => $request->has('is_active'),
        ]));

        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();
        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil dihapus.');
    }
}
