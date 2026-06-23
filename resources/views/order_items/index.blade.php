@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1>Daftar Item Pesanan</h1>
    <a href="{{ route('order-items.create') }}" class="btn btn-primary">+ Tambah Item</a>
</div>

<div class="table-wrapper">
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
                <td class="price">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                <td class="price">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                <td>
                    <div class="actions">
                        <a href="{{ route('order-items.show', $item->id) }}" class="btn btn-primary btn-sm">Detail</a>
                        <a href="{{ route('order-items.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form class="inline" action="{{ route('order-items.destroy', $item->id) }}" method="POST"
                              onsubmit="return confirm('Hapus item ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center empty-state"><p>Belum ada item pesanan.</p></td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="pagination-wrap">{{ $orderItems->links() }}</div>
@endsection
