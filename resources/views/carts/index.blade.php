@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1>Keranjang Belanja</h1>
    <a href="{{ route('carts.create') }}" class="btn btn-primary">+ Tambah ke Keranjang</a>
</div>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Total</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($carts as $cart)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $cart->customer->name }}</td>
                <td>{{ $cart->product->name }}</td>
                <td>{{ $cart->quantity }}</td>
                <td class="price">Rp {{ number_format($cart->product->price * $cart->quantity, 0, ',', '.') }}</td>
                <td>
                    <div class="actions">
                        <a href="{{ route('carts.show', $cart->id) }}" class="btn btn-primary btn-sm">Detail</a>
                        <a href="{{ route('carts.edit', $cart->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form class="inline" action="{{ route('carts.destroy', $cart->id) }}" method="POST"
                              onsubmit="return confirm('Hapus item ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center empty-state"><p>Keranjang kosong.</p></td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="pagination-wrap">{{ $carts->links() }}</div>
@endsection
