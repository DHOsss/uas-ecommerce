@extends('layouts.app')

@section('content')
<h1>Tambah Ulasan</h1>
<div class="card" style="max-width:500px">
    <form action="{{ route('reviews.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Produk</label>
            <select name="product_id">
                <option value="">-- Pilih Produk --</option>
                @foreach($products as $product)
                <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                    {{ $product->name }}
                </option>
                @endforeach
            </select>
            @error('product_id') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label>Nama Customer</label>
            <input type="text" name="customer_name" value="{{ old('customer_name') }}">
            @error('customer_name') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label>Rating (1-5)</label>
            <select name="rating">
                @for($i = 1; $i <= 5; $i++)
                <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>{{ $i }} Bintang</option>
                @endfor
            </select>
            @error('rating') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label>Komentar</label>
            <textarea name="comment" rows="4">{{ old('comment') }}</textarea>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('reviews.index') }}" class="btn btn-primary">Batal</a>
    </form>
</div>
@endsection
