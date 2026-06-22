@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1>Ulasan Produk</h1>
    <a href="{{ route('reviews.create') }}" class="btn btn-primary">+ Tambah Ulasan</a>
</div>

@php
$avgRating = $reviews->count() ? round($reviews->avg('rating'), 1) : 0;
$total     = $reviews->total();
@endphp
<div style="display:flex; gap:12px; margin-bottom:18px; flex-wrap:wrap;">
    <div style="background:#fffbeb; border:1.5px solid #fde68a; border-radius:10px; padding:10px 18px; font-size:13px;">
        ⭐ <strong>Rata-rata:</strong> {{ $avgRating }}/5
    </div>
    <div style="background:#f0f9ff; border:1.5px solid #bae6fd; border-radius:10px; padding:10px 18px; font-size:13px;">
        💬 <strong>Total:</strong> {{ $total }} ulasan
    </div>
</div>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Produk</th>
                <th>Rating</th>
                <th>Komentar</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reviews as $review)
            <tr>
                <td style="color:#9ca3af; font-size:12px;">#{{ str_pad($review->id, 3, '0', STR_PAD_LEFT) }}</td>
                <td>
                    <div style="font-weight:700; font-size:13px;">{{ $review->customer_name }}</div>
                </td>
                <td>
                    <div style="font-size:13px; font-weight:600; color:#1a1a1a;">
                        {{ $review->product->name ?? '—' }}
                    </div>
                </td>
                <td>
                    @php $r = intval($review->rating); @endphp
                    <div style="display:flex; align-items:center; gap:4px;">
                        <span style="font-size:14px; letter-spacing:1px;">
                            @for($i = 1; $i <= 5; $i++)
                                {{ $i <= $r ? '★' : '☆' }}
                            @endfor
                        </span>
                        <span style="font-size:11px; color:#9ca3af; font-weight:700;">{{ $r }}/5</span>
                    </div>
                </td>
                <td style="font-size:12px; color:#6b7280; max-width:220px;">
                    {{ $review->comment ? Str::limit($review->comment, 60) : '—' }}
                </td>
                <td style="font-size:12px; color:#9ca3af;">
                    {{ $review->created_at->format('d M Y') }}
                </td>
                <td>
                    <div class="actions">
                        <a href="{{ route('reviews.show', $review->id) }}" class="btn btn-primary btn-sm">Detail</a>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center empty-state"><p>Belum ada ulasan.</p></td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="pagination-wrap">{{ $reviews->links() }}</div>
@endsection
