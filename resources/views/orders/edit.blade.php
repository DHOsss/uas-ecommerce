@extends('layouts.app')

@section('content')
<h1>Ubah Status Pesanan</h1>
<div class="card card-narrow">
    <p><strong>Pelanggan:</strong> {{ $order->customer_name }}</p>
    <p style="margin-bottom:16px"><strong>Produk:</strong> {{ $order->product->name ?? '-' }}</p>
    <form action="{{ route('orders.update', $order->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="form-group">
            <label>Status</label>
            <select name="status">
                <option value="pending"   {{ $order->status == 'pending'   ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            @error('status') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ route('orders.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
