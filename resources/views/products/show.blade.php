@extends('layouts.app')

@section('content')
<h1>Detail Produk</h1>
<div class="card card-narrow">
    <table>
        <tr><th>Nama</th><td>{{ $product->name }}</td></tr>
        <tr><th>Deskripsi</th><td>{{ $product->description ?? '-' }}</td></tr>
        <tr><th>Kategori</th><td>{{ $product->category ?? '-' }}</td></tr>
        <tr><th>Harga</th><td class="price">Rp {{ number_format($product->price, 0, ',', '.') }}</td></tr>
        <tr><th>Stok</th><td>{{ $product->stock }}</td></tr>
    </table>
    <div class="form-actions">
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
@endsection
