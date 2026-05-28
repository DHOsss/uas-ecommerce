@extends('layouts.app')

@section('content')
<h1>Detail Item Pesanan</h1>
<div class="card" style="max-width:500px">
    <table>
        <tr><th>ID</th><td>{{ $orderItem->id }}</td></tr>
        <tr><th>Pesanan</th><td>#{{ $orderItem->order_id }} - {{ $orderItem->order->customer_name }}</td></tr>
        <tr><th>Produk</th><td>{{ $orderItem->product->name }}</td></tr>
        <tr><th>Jumlah</th><td>{{ $orderItem->quantity }}</td></tr>
        <tr><th>Harga Satuan</th><td>Rp {{ number_format($orderItem->price, 0, ',', '.') }}</td></tr>
        <tr><th>Subtotal</th><td>Rp {{ number_format($orderItem->subtotal, 0, ',', '.') }}</td></tr>
        <tr><th>Dibuat</th><td>{{ $orderItem->created_at->format('d/m/Y H:i') }}</td></tr>
    </table>
    <div style="margin-top:12px">
        <a href="{{ route('order-items.edit', $orderItem->id) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('order-items.index') }}" class="btn btn-primary">Kembali</a>
    </div>
</div>
@endsection
