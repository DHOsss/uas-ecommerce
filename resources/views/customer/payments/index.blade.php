@extends('layouts.app')

@section('title', 'Pembayaran Saya — OutfitKu')

@section('content')
<div class="page-header">
    <h1>Pembayaran Saya</h1>
    <a href="{{ route('customer.payments.create') }}" class="btn btn-primary">+ Bayar Sekarang</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="table-wrapper">
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
                <td>Pesanan #{{ $payment->order_id }}</td>
                <td class="price">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                <td>{{ ucfirst($payment->method) }}</td>
                <td>
                    @if($payment->status === 'paid')
                        <span class="badge badge-confirmed">Lunas</span>
                    @elseif($payment->status === 'pending')
                        <span class="badge badge-pending">Menunggu Konfirmasi</span>
                    @else
                        <span class="badge badge-cancelled">Gagal</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('customer.payments.show', $payment->id) }}" class="btn btn-primary btn-sm">Detail</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center empty-state"><p>Belum ada pembayaran.</p></td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="pagination-wrap">{{ $payments->links() }}</div>
@endsection
