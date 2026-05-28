@extends('layouts.app')

@section('content')
<h1>Tambah Produk</h1>
<div class="card" style="max-width:500px">
    <form action="{{ route('products.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Nama Produk</label>
            <input type="text" name="name" value="{{ old('name') }}">
            @error('name') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="description" rows="3">{{ old('description') }}</textarea>
        </div>
        <div class="form-group">
            <label>Harga (Rp)</label>
            <input type="number" name="price" value="{{ old('price') }}" min="0" step="100">
            @error('price') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label>Stok</label>
            <input type="number" name="stock" value="{{ old('stock', 0) }}" min="0">
            @error('stock') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label>Kategori</label>
            <input type="text" name="category" value="{{ old('category') }}">
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('products.index') }}" class="btn btn-primary">Batal</a>
    </form>
</div>
@endsection
