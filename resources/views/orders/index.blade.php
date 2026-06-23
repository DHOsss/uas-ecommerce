@extends('layouts.app')

@php $pageTitle = auth()->user()->isAdmin() ? 'Manajemen Pesanan' : 'Pesanan Saya'; @endphp
@section('title', $pageTitle . ' — OutfitKu')

@section('content')

@if(auth()->user()->isAdmin())
{{-- ══════════ ADMIN VIEW ══════════ --}}
<div class="page-header">
    <h1>Manajemen Pesanan</h1>
</div>

{{-- Filter status --}}
@php $filterStatus = request('status'); @endphp
<div style="display:flex; gap:8px; margin-bottom:16px; flex-wrap:wrap;">
    @foreach(['' => 'Semua', 'pending' => '⏳ Pending', 'confirmed' => '✅ Dikonfirmasi', 'cancelled' => '❌ Dibatalkan'] as $val => $label)
    <a href="{{ route('orders.index', $val ? ['status' => $val] : []) }}"
       style="padding:6px 14px; border-radius:999px; font-size:12px; font-weight:700; text-decoration:none; border:1.5px solid;
              {{ $filterStatus === $val || ($val === '' && !$filterStatus)
                  ? 'background:#0a0a0a; color:#fff; border-color:#0a0a0a;'
                  : 'background:#fff; color:#555; border-color:#e0dfd9;' }}">
        {{ $label }}
    </a>
    @endforeach
</div>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Produk</th>
                <th>Total</th>
                <th>Status Pesanan</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td style="color:#9ca3af; font-size:12px;">
                    #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                </td>
                <td>
                    <div style="font-weight:700; font-size:13px;">{{ $order->customer_name }}</div>
                    <div style="font-size:11px; color:#9ca3af;">{{ $order->customer_email }}</div>
                </td>
                <td style="font-size:12px; color:#555;">
                    {{ $order->orderItems->count() }} item
                </td>
                <td class="price" style="font-weight:800;">
                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                </td>
                <td>
                    <form action="{{ route('orders.update', $order->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <select name="status" onchange="this.form.submit()"
                                style="padding:5px 8px; border-radius:7px; border:1.5px solid #e0dfd9; font-size:12px; font-weight:700; cursor:pointer;
                                       {{ $order->status === 'confirmed' ? 'color:#166534; background:#dcfce7;' : ($order->status === 'cancelled' ? 'color:#991b1b; background:#fee2e2;' : 'color:#92400e; background:#fef3c7;') }}">
                            <option value="pending"   {{ $order->status === 'pending'   ? 'selected' : '' }}>⏳ Pending</option>
                            <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>✅ Dikonfirmasi</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>❌ Dibatalkan</option>
                        </select>
                    </form>
                </td>
                <td style="font-size:12px; color:#9ca3af;">
                    {{ $order->created_at->format('d M Y') }}<br>
                    <span style="font-size:11px;">{{ $order->created_at->format('H:i') }}</span>
                </td>
                <td>
                    <div class="actions">
                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary btn-sm">Detail</a>
                        <form class="inline" action="{{ route('orders.destroy', $order->id) }}" method="POST"
                              onsubmit="return confirm('Hapus pesanan #{{ $order->id }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center empty-state"><p>Belum ada pesanan.</p></td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="pagination-wrap">{{ $orders->links() }}</div>

@else
{{-- ══════════ CUSTOMER VIEW ══════════ --}}
<div class="page-header">
    <h1>Pesanan Saya</h1>
    <a href="{{ route('products.index') }}" class="btn btn-primary">🛍️ Belanja Lagi</a>
</div>
<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Total</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td style="font-size:12px; color:#9ca3af;">#{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                <td class="price">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                <td><span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                <td style="font-size:12px;">{{ $order->created_at->format('d M Y') }}</td>
                <td>
                    <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-primary btn-sm">Detail</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center empty-state"><p>Belum ada pesanan. <a href="{{ route('products.index') }}">Mulai belanja</a></p></td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="pagination-wrap">{{ $orders->links() }}</div>
@endif

@endsection
