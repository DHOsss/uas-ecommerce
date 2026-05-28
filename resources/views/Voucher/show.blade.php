@extends('layouts.app')

@section('content')
<h1>Detail Voucher</h1>
<div class="card" style="max-width:500px">
    <table>
        <tr><th>ID</th><td>{{ $voucher->id }}</td></tr>
        <tr><th>Kode</th><td><strong>{{ $voucher->code }}</strong></td></tr>
        <tr><th>Diskon</th><td>{{ $voucher->discount }}%</td></tr>
        <tr><th>Min. Pembelian</th><td>Rp {{ number_format($voucher->min_purchase, 0, ',', '.') }}</td></tr>
        <tr><th>Kadaluarsa</th><td>{{ $voucher->expired_at->format('d/m/Y') }}</td></tr>
        <tr><th>Status</th><td>{{ $voucher->is_active ? 'Aktif' : 'Nonaktif' }}</td></tr>
        <tr><th>Dibuat</th><td>{{ $voucher->created_at->format('d/m/Y H:i') }}</td></tr>
    </table>
    <div style="margin-top:12px">
        <a href="{{ route('vouchers.edit', $voucher->id) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('vouchers.index') }}" class="btn btn-primary">Kembali</a>
    </div>
</div>
@endsection
