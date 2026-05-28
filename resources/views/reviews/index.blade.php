@extends('layouts.app')

@section('content')
<h1>Daftar Ulasan</h1>
<a href="{{ route('reviews.create') }}" class="btn btn-primary" style="margin-bottom:16px">+ Tambah Ulasan</a>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Produk</th>
            <th>Nama Customer</th>
            <th>Rating</th>
            <th>Komentar</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($reviews as $review)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $review->product->name }}</td>
            <td>{{ $review->customer_name }}</td>
            <td>{{ $review->rating }}/5 ★</td>
            <td>{{ Str::limit($review->comment, 50) ?? '-' }}</td>
            <td>
                <a href="{{ route('reviews.show', $review->id) }}" class="btn btn-primary">Detail</a>
                <a href="{{ route('reviews.edit', $review->id) }}" class="btn btn-warning">Edit</a>
                <form class="inline" action="{{ route('reviews.destroy', $review->id) }}" method="POST"
                      onsubmit="return confirm('Hapus ulasan ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center">Belum ada ulasan.</td></tr>
        @endforelse
    </tbody>
</table>
<div style="margin-top:12px">{{ $reviews->links() }}</div>
@endsection
