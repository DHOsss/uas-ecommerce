@extends('layouts.app')

@section('content')
<h1>Tulis Ulasan</h1>
<div class="card card-narrow">
    @if($products->isEmpty())
        <p style="color:var(--gray-600);">Kamu belum punya produk yang bisa diulas.</p>
        <p style="color:var(--gray-500); font-size:0.875rem;">
            Hanya produk yang sudah kamu pesan dan belum diulas yang bisa diberi ulasan.
        </p>
        <div class="form-actions">
            <a href="{{ route('customer.orders.create') }}" class="btn btn-primary">Pesan Produk</a>
            <a href="{{ route('customer.reviews') }}" class="btn btn-secondary">Kembali</a>
        </div>
    @else
    <form action="{{ route('customer.reviews.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Nama Kamu</label>
            <input type="text" value="{{ auth()->user()->name }}" disabled
                style="background:var(--gray-100); color:var(--gray-600);">
        </div>
        <div class="form-group">
            <label>Produk yang Dipesan</label>
            <select name="product_id">
                <option value="">-- Pilih Produk --</option>
                @foreach($products as $product)
                <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                    {{ $product->name }}
                </option>
                @endforeach
            </select>
            @error('product_id') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label>Rating</label>
            <div style="display:flex; gap:8px; align-items:center;">
                @for($i = 1; $i <= 5; $i++)
                <label style="display:flex; align-items:center; gap:4px; cursor:pointer; font-weight:400;">
                    <input type="radio" name="rating" value="{{ $i }}" {{ old('rating') == $i ? 'checked' : ($i == 5 ? 'checked' : '') }}>
                    {{ $i }} ★
                </label>
                @endfor
            </div>
            @error('rating') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label>Komentar</label>
            <textarea name="comment" rows="4" placeholder="Ceritakan pengalamanmu...">{{ old('comment') }}</textarea>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-success">Kirim Ulasan</button>
            <a href="{{ route('customer.reviews') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
    @endif
</div>
@endsection
