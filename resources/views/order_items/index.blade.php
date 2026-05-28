@extends('layouts.app')

@section('content')
<h1>Daftar Item Pesanan</h1>
<a href="{{ route('order-items.create') }}" class="btn btn-primary" style="margin-bottom:16px">+ Tambah Item</a>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Pesanan #</th>
            <th>Produk</th>
            <th>Qty</th>
            <th>Harga Satuan</th>
            <th>Subtotal</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($orderItems as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>#{{ $item->order_id }}</td>
            <td>{{ $item->product->name }}</td>
            <td>{{ $item->quantity }}</td>
            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
            <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            <td>
                <a href="{{ route('order-items.show', $item->id) }}" class="btn btn-primary">Detail</a>
                <a href="{{ route('order-items.edit', $item->id) }}" class="btn btn-warning">Edit</a>
                <form class="inline" action="{{ route('order-items.destroy', $item->id) }}" method="POST"
                      onsubmit="return confirm('Hapus item ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center">Belum ada item pesanan.</td></tr>
        @endforelse
    </tbody>
</table>
<div style="margin-top:12px">{{ $orderItems->links() }}</div>
@endsection
