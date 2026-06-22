@extends('layouts.app')

@section('content')
<h1>Detail Voucher</h1>
<div class="card card-narrow">
    <table>
        <tr><th>ID</th><td>{{ $voucher->id }}</td></tr>
        <tr><th>Kode</th><td><strong>{{ $voucher->code }}</strong></td></tr>
        <tr><th>Diskon</th><td>{{ $voucher->discount }}%</td></tr>
        <tr><th>Min. Pembelian</th><td class="price">Rp {{ number_format($voucher->min_purchase, 0, ',', '.') }}</td></tr>
        <tr><th>Kadaluarsa</th><td>{{ $voucher->expired_at->format('d/m/Y') }}</td></tr>
        <tr><th>Status</th><td>
            @if($voucher->is_active)
                <span class="badge badge-confirmed">Aktif</span>
            @else
                <span class="badge badge-cancelled">Nonaktif</span>
            @endif
        </td></tr>
        <tr><th>Dibuat</th><td>{{ $voucher->created_at->format('d/m/Y H:i') }}</td></tr>
    </table>
    <div class="form-actions">
        <a href="{{ route('vouchers.edit', $voucher->id) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('vouchers.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
@endsection
