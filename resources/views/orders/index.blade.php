@extends('layouts.app')

@section('content')
<h1>Daftar Pesanan</h1>
<a href="{{ route('orders.create') }}" class="btn btn-primary" style="margin-bottom:16px">+ Buat Pesanan</a>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Pelanggan</th>
            <th>Produk</th>
            <th>Qty</th>
            <th>Total</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($orders as $order)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $order->customer_name }}<br><small>{{ $order->customer_email }}</small></td>
            <td>{{ $order->product->name }}</td>
            <td>{{ $order->quantity }}</td>
            <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
            <td>
                <span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
            </td>
            <td>
                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary">Detail</a>
                <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-warning">Status</a>
                <form class="inline" action="{{ route('orders.destroy', $order->id) }}" method="POST"
                      onsubmit="return confirm('Hapus pesanan ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center">Belum ada pesanan.</td></tr>
        @endforelse
    </tbody>
</table>
<div style="margin-top:12px">{{ $orders->links() }}</div>
@endsection
