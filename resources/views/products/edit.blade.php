@extends('layouts.app')

@section('content')
<h1>Edit Produk</h1>
<div class="card card-narrow">
    <form action="{{ route('products.update', $product->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="form-group">
            <label>Nama Produk</label>
            <input type="text" name="name" value="{{ old('name', $product->name) }}">
            @error('name') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="description" rows="3">{{ old('description', $product->description) }}</textarea>
        </div>
        <div class="form-group">
            <label>Harga (Rp)</label>
            <input type="number" name="price" value="{{ old('price', $product->price) }}" min="0" step="100">
            @error('price') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label>Stok</label>
            <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0">
            @error('stock') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label>Kategori</label>
            <input type="text" name="category" value="{{ old('category', $product->category) }}">
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-success">Perbarui</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
