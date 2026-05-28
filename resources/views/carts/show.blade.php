@extends('layouts.app')

@section('content')
<h1>Detail Keranjang</h1>
<div class="card" style="max-width:500px">
    <table>
        <tr><th>ID</th><td>{{ $cart->id }}</td></tr>
        <tr><th>Customer</th><td>{{ $cart->customer->name }}</td></tr>
        <tr><th>Produk</th><td>{{ $cart->product->name }}</td></tr>
        <tr><th>Jumlah</th><td>{{ $cart->quantity }}</td></tr>
        <tr><th>Total</th><td>Rp {{ number_format($cart->product->price * $cart->quantity, 0, ',', '.') }}</td></tr>
        <tr><th>Ditambahkan</th><td>{{ $cart->created_at->format('d/m/Y H:i') }}</td></tr>
    </table>
    <div style="margin-top:12px">
        <a href="{{ route('carts.edit', $cart->id) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('carts.index') }}" class="btn btn-primary">Kembali</a>
    </div>
</div>
@endsection
