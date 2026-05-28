@extends('layouts.app')

@section('content')
<h1>Daftar Produk</h1>
<a href="{{ route('products.create') }}" class="btn btn-primary" style="margin-bottom:16px">+ Tambah Produk</a>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($products as $product)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->category ?? '-' }}</td>
            <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
            <td>{{ $product->stock }}</td>
            <td>
                <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary">Detail</a>
                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">Edit</a>
                <form class="inline" action="{{ route('products.destroy', $product->id) }}" method="POST"
                      onsubmit="return confirm('Hapus produk ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center">Belum ada produk.</td></tr>
        @endforelse
    </tbody>
</table>
<div style="margin-top:12px">{{ $products->links() }}</div>
@endsection
