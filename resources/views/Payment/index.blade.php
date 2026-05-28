@extends('layouts.app')

@section('content')
<h1>Daftar Pembayaran</h1>
<a href="{{ route('payments.create') }}" class="btn btn-primary" style="margin-bottom:16px">+ Tambah Pembayaran</a>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Pesanan</th>
            <th>Jumlah</th>
            <th>Metode</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($payments as $payment)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>#{{ $payment->order_id }} - {{ $payment->order->customer_name }}</td>
            <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
            <td>{{ ucfirst($payment->method) }}</td>
            <td>
                @if($payment->status === 'paid')
                    <span class="badge badge-confirmed">Lunas</span>
                @elseif($payment->status === 'pending')
                    <span class="badge badge-pending">Pending</span>
                @else
                    <span class="badge badge-cancelled">Gagal</span>
                @endif
            </td>
            <td>
                <a href="{{ route('payments.show', $payment->id) }}" class="btn btn-primary">Detail</a>
                <a href="{{ route('payments.edit', $payment->id) }}" class="btn btn-warning">Edit</a>
                <form class="inline" action="{{ route('payments.destroy', $payment->id) }}" method="POST"
                      onsubmit="return confirm('Hapus pembayaran ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center">Belum ada pembayaran.</td></tr>
        @endforelse
    </tbody>
</table>
<div style="margin-top:12px">{{ $payments->links() }}</div>
@endsection
