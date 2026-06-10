@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1>Daftar Voucher</h1>
    <a href="{{ route('vouchers.create') }}" class="btn btn-primary">+ Tambah Voucher</a>
</div>

<div class="table-wrapper">
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
                <td class="price">Rp {{ number_format($voucher->min_purchase, 0, ',', '.') }}</td>
                <td>{{ $voucher->expired_at->format('d/m/Y') }}</td>
                <td>
                    @if($voucher->is_active)
                        <span class="badge badge-confirmed">Aktif</span>
                    @else
                        <span class="badge badge-cancelled">Nonaktif</span>
                    @endif
                </td>
                <td>
                    <div class="actions">
                        <a href="{{ route('vouchers.show', $voucher->id) }}" class="btn btn-primary btn-sm">Detail</a>
                        <a href="{{ route('vouchers.edit', $voucher->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form class="inline" action="{{ route('vouchers.destroy', $voucher->id) }}" method="POST"
                              onsubmit="return confirm('Hapus voucher ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center empty-state"><p>Belum ada voucher.</p></td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="pagination-wrap">{{ $vouchers->links() }}</div>
@endsection
