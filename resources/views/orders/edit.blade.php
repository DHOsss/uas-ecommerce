@extends('layouts.app')

@section('content')
<h1>Ubah Status Pesanan</h1>
<div class="card" style="max-width:400px">
    <p><strong>Pelanggan:</strong> {{ $order->customer_name }}</p>
    <p><strong>Produk:</strong> {{ $order->product->name ?? '-' }}</p>
    <form action="{{ route('orders.update', $order->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="form-group">
            <label>Status</label>
            <select name="status">
                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            @error('status') <div class="error">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('orders.index') }}" class="btn btn-primary">Batal</a>
    </form>
</div>
@endsection
