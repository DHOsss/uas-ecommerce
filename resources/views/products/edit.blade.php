@extends('layouts.app')

@section('content')
<h1>Edit Produk</h1>
<div class="card" style="max-width:500px">
    <form action="{{ route('products.update', $product->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="form-group">
            <label>Nama Produk</label>
            <input type="text" name="name" value="{{ old('name', $product->name) }}">
            @error('name') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="description" rows="3">{{ old('description', $product->description) }}</textarea>
        </div>
        <div class="form-group">
            <label>Harga (Rp)</label>
            <input type="number" name="price" value="{{ old('price', $product->price) }}" min="0" step="100">
            @error('price') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label>Stok</label>
            <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0">
            @error('stock') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label>Kategori</label>
            <input type="text" name="category" value="{{ old('category', $product->category) }}">
        </div>
        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('products.index') }}" class="btn btn-primary">Batal</a>
    </form>
</div>
@endsection
