@extends('layouts.app')

@section('content')
<h1>Keranjang Belanja</h1>
<a href="{{ route('carts.create') }}" class="btn btn-primary" style="margin-bottom:16px">+ Tambah ke Keranjang</a>

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
            <td>Rp {{ number_format($cart->product->price * $cart->quantity, 0, ',', '.') }}</td>
            <td>
                <a href="{{ route('carts.show', $cart->id) }}" class="btn btn-primary">Detail</a>
                <a href="{{ route('carts.edit', $cart->id) }}" class="btn btn-warning">Edit</a>
                <form class="inline" action="{{ route('carts.destroy', $cart->id) }}" method="POST"
                      onsubmit="return confirm('Hapus item ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center">Keranjang kosong.</td></tr>
        @endforelse
    </tbody>
</table>
<div style="margin-top:12px">{{ $carts->links() }}</div>
@endsection
