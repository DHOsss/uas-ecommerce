@extends('layouts.app')

@section('content')
<h1>Detail Pesanan</h1>
<div class="card card-narrow">
    <table>
        <tr><th>Pelanggan</th><td>{{ $order->customer_name }}</td></tr>
        <tr><th>Email</th><td>{{ $order->customer_email }}</td></tr>
        <tr><th>Status</th><td><span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td></tr>
        <tr><th>Tanggal</th><td>{{ $order->created_at->format('d/m/Y H:i') }}</td></tr>
    </table>

    <h3 style="margin: 20px 0 10px;">Produk Dipesan</h3>
    @if ($order->orderItems->count())
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga Satuan</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderItems as $item)
                <tr>
                    <td>{{ $item->product->name ?? '-' }}</td>
                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td class="price">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align:right; font-weight:600;">Total</td>
                    <td class="price"><strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></td>
                </tr>
            </tfoot>
        </table>
    @else
        <p style="color: var(--gray-400);">Tidak ada item pada pesanan ini.</p>
    @endif

    <div class="form-actions">
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
@endsection
