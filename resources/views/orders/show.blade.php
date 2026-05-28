@extends('layouts.app')

@section('content')
<h1>Detail Pesanan</h1>
<div class="card" style="max-width:500px">
    <p><strong>Pelanggan:</strong> {{ $order->customer_name }}</p>
    <p><strong>Email:</strong> {{ $order->customer_email }}</p>
    <p><strong>Produk:</strong> {{ $order->product->name }}</p>
    <p><strong>Jumlah:</strong> {{ $order->quantity }}</p>
    <p><strong>Total:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
    <p><strong>Status:</strong> <span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span></p>
    <p><strong>Tanggal:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
    <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-warning">Ubah Status</a>
    <a href="{{ route('orders.index') }}" class="btn btn-primary">Kembali</a>
</div>
@endsection
