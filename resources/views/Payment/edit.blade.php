@extends('layouts.app')

@section('content')
<h1>Edit Pembayaran</h1>
<div class="card card-narrow">
    <form action="{{ route('payments.update', $payment->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="form-group">
            <label>Pesanan</label>
            <select name="order_id">
                @foreach($orders as $order)
                <option value="{{ $order->id }}" {{ $payment->order_id == $order->id ? 'selected' : '' }}>
                    #{{ $order->id }} - {{ $order->customer_name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Jumlah Bayar (Rp)</label>
            <input type="number" name="amount" value="{{ old('amount', $payment->amount) }}" min="0" step="100">
            @error('amount') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label>Metode Pembayaran</label>
            <select name="method">
                <option value="transfer" {{ $payment->method == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                <option value="cash"     {{ $payment->method == 'cash'     ? 'selected' : '' }}>Cash</option>
                <option value="ewallet"  {{ $payment->method == 'ewallet'  ? 'selected' : '' }}>E-Wallet</option>
            </select>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status">
                <option value="pending" {{ $payment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="paid"    {{ $payment->status == 'paid'    ? 'selected' : '' }}>Lunas</option>
                <option value="failed"  {{ $payment->status == 'failed'  ? 'selected' : '' }}>Gagal</option>
            </select>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-success">Perbarui</button>
            <a href="{{ route('payments.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
