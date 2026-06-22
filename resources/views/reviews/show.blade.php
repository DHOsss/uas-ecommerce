@extends('layouts.app')

@section('content')
<h1>Detail Ulasan</h1>
<div class="card card-narrow">
    <table>
        <tr><th>ID</th><td>{{ $review->id }}</td></tr>
        <tr><th>Produk</th><td>{{ $review->product->name }}</td></tr>
        <tr><th>Customer</th><td>{{ $review->customer_name }}</td></tr>
        <tr><th>Rating</th><td>{{ $review->rating }}/5 ★</td></tr>
        <tr><th>Komentar</th><td>{{ $review->comment ?? '-' }}</td></tr>
        <tr><th>Dibuat</th><td>{{ $review->created_at->format('d/m/Y H:i') }}</td></tr>
    </table>
    <div class="form-actions">
        <a href="{{ route('reviews.edit', $review->id) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('reviews.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
@endsection
