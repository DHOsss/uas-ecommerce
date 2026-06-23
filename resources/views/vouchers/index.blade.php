@extends('layouts.app')

@section('content')
<h1>Daftar Voucher</h1>
<a href="{{ route('vouchers.create') }}" class="btn btn-primary" style="margin-bottom:16px">+ Tambah Voucher</a>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Kode</th>
            <th>Diskon</th>
            <th>Min. Pembelian</th>
            <th>Kadaluarsa</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($vouchers as $voucher)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td><strong>{{ $voucher->code }}</strong></td>
            <td>{{ $voucher->discount }}%</td>
            <td>Rp {{ number_format($voucher->min_purchase, 0, ',', '.') }}</td>
            <td>{{ $voucher->expired_at->format('d/m/Y') }}</td>
            <td>
                @if($voucher->is_active)
                    <span class="badge badge-confirmed">Aktif</span>
                @else
                    <span class="badge badge-cancelled">Nonaktif</span>
                @endif
            </td>
            <td>
                <a href="{{ route('vouchers.show', $voucher->id) }}" class="btn btn-primary">Detail</a>
                <a href="{{ route('vouchers.edit', $voucher->id) }}" class="btn btn-warning">Edit</a>
                <form class="inline" action="{{ route('vouchers.destroy', $voucher->id) }}" method="POST"
                      onsubmit="return confirm('Hapus voucher ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center">Belum ada voucher.</td></tr>
        @endforelse
    </tbody>
</table>
<div style="margin-top:12px">{{ $vouchers->links() }}</div>
@endsection
