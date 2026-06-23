@extends('layouts.app')

@section('content')
<h1>Edit Ulasan</h1>
<div class="card card-narrow">
    <form action="{{ route('reviews.update', $review->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="form-group">
            <label>Produk</label>
            <select name="product_id">
                @foreach($products as $product)
                <option value="{{ $product->id }}" {{ $review->product_id == $product->id ? 'selected' : '' }}>
                    {{ $product->name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Nama Customer</label>
            <input type="text" name="customer_name" value="{{ old('customer_name', $review->customer_name) }}">
            @error('customer_name') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label>Rating (1-5)</label>
            <select name="rating">
                @for($i = 1; $i <= 5; $i++)
                <option value="{{ $i }}" {{ $review->rating == $i ? 'selected' : '' }}>{{ $i }} Bintang</option>
                @endfor
            </select>
        </div>
        <div class="form-group">
            <label>Komentar</label>
            <textarea name="comment" rows="4">{{ old('comment', $review->comment) }}</textarea>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-success">Perbarui</button>
            <a href="{{ route('reviews.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
