@extends('layouts.app')

@section('content')
<h1>Detail Pembayaran</h1>
<div class="card card-narrow">
    <table>
        <tr><th>ID</th><td>{{ $payment->id }}</td></tr>
        <tr><th>Pesanan</th><td>#{{ $payment->order_id }} - {{ $payment->order->customer_name }}</td></tr>
        <tr><th>Jumlah</th><td class="price">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td></tr>
        <tr><th>Metode</th><td>{{ ucfirst($payment->method) }}</td></tr>
        <tr><th>Status</th><td>{{ ucfirst($payment->status) }}</td></tr>
        <tr><th>Waktu Bayar</th><td>{{ optional($payment->paid_at)->format('d/m/Y H:i') ?? '-' }}</td></tr>
        <tr><th>Dibuat</th><td>{{ $payment->created_at->format('d/m/Y H:i') }}</td></tr>
    </table>
    <div class="form-actions">
        <a href="{{ route('payments.edit', $payment->id) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('payments.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
@endsection
