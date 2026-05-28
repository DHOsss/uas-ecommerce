@extends('layouts.app')

@section('content')
<h1>Tambah Pembayaran</h1>
<div class="card" style="max-width:500px">
    <form action="{{ route('payments.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Pesanan</label>
            <select name="order_id">
                <option value="">-- Pilih Pesanan --</option>
                @foreach($orders as $order)
                <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>
                    #{{ $order->id }} - {{ $order->customer_name }} (Rp {{ number_format($order->total_price, 0, ',', '.') }})
                </option>
                @endforeach
            </select>
            @error('order_id') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label>Jumlah Bayar (Rp)</label>
            <input type="number" name="amount" value="{{ old('amount') }}" min="0" step="100">
            @error('amount') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label>Metode Pembayaran</label>
            <select name="method">
                <option value="transfer" {{ old('method') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                <option value="cash" {{ old('method') == 'cash' ? 'selected' : '' }}>Cash</option>
                <option value="ewallet" {{ old('method') == 'ewallet' ? 'selected' : '' }}>E-Wallet</option>
            </select>
            @error('method') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status">
                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                <option value="failed" {{ old('status') == 'failed' ? 'selected' : '' }}>Gagal</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('payments.index') }}" class="btn btn-primary">Batal</a>
    </form>
</div>
@endsection
