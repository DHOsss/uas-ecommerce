@extends('layouts.app')

@section('content')
<h1>Detail Produk</h1>
<div class="card" style="max-width:500px">
    <p><strong>Nama:</strong> {{ $product->name }}</p>
    <p><strong>Deskripsi:</strong> {{ $product->description ?? '-' }}</p>
    <p><strong>Kategori:</strong> {{ $product->category ?? '-' }}</p>
    <p><strong>Harga:</strong> Rp {{ number_format($product->price, 0, ',', '.') }}</p>
    <p><strong>Stok:</strong> {{ $product->stock }}</p>
    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('products.index') }}" class="btn btn-primary">Kembali</a>
</div>
@endsection
