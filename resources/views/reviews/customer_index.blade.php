@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1>Ulasan Saya</h1>
    <a href="{{ route('customer.reviews.create') }}" class="btn btn-primary">+ Tulis Ulasan</a>
</div>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Produk</th>
                <th>Rating</th>
                <th>Komentar</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reviews as $review)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $review->product->name ?? '-' }}</td>
                <td>
                    @for($i = 1; $i <= 5; $i++)
                        <span style="color:{{ $i <= $review->rating ? '#f59e0b' : 'var(--gray-200)' }}">★</span>
                    @endfor
                    {{ $review->rating }}/5
                </td>
                <td>{{ $review->comment ?? '-' }}</td>
                <td>{{ $review->created_at->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center empty-state"><p>Kamu belum punya ulasan. <a href="{{ route('customer.reviews.create') }}">Tulis sekarang</a></p></td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="pagination-wrap">{{ $reviews->links() }}</div>
@endsection
