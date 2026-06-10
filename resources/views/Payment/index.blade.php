@extends('layouts.app')

@section('content')

@php
$methodLabels = [
    'transfer_bank' => ['label' => 'Transfer Bank', 'icon' => '🏦'],
    'qris'          => ['label' => 'QRIS',          'icon' => '📱'],
    'cod'           => ['label' => 'COD',            'icon' => '🤝'],
    'transfer'      => ['label' => 'Transfer',       'icon' => '🏦'],
    'cash'          => ['label' => 'Tunai',          'icon' => '💵'],
    'ewallet'       => ['label' => 'E-Wallet',       'icon' => '📲'],
];
@endphp

<div class="page-header">
    <h1>Manajemen Pembayaran</h1>
</div>

{{-- Summary chips --}}
@php
$totalPaid    = $payments->where('status', 'paid')->sum('amount');
$countPending = $payments->where('status', 'pending')->count();
@endphp
<div style="display:flex; gap:12px; margin-bottom:18px; flex-wrap:wrap;">
    <div style="background:#dcfce7; border:1.5px solid #86efac; border-radius:10px; padding:10px 18px; font-size:13px;">
        ✅ <strong>Lunas:</strong> Rp {{ number_format($totalPaid, 0, ',', '.') }}
    </div>
    <div style="background:#fef3c7; border:1.5px solid #fde68a; border-radius:10px; padding:10px 18px; font-size:13px;">
        ⏳ <strong>Pending:</strong> {{ $countPending }} pembayaran
    </div>
</div>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Pesanan / Customer</th>
                <th>Jumlah</th>
                <th>Metode</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
            @php $m = $methodLabels[$payment->method] ?? ['label' => ucfirst($payment->method), 'icon' => '💳']; @endphp
            <tr>
                <td style="color:#9ca3af; font-size:12px;">
                    #{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}
                </td>
                <td>
                    <div style="font-weight:700; font-size:13px;">{{ $payment->order->customer_name ?? '-' }}</div>
                    <div style="font-size:11px; color:#9ca3af;">Pesanan #{{ str_pad($payment->order_id, 4, '0', STR_PAD_LEFT) }}</div>
                </td>
                <td class="price" style="font-weight:800;">
                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                </td>
                <td>
                    <span style="display:inline-flex; align-items:center; gap:5px; background:#f5f4f0; padding:4px 10px; border-radius:6px; font-size:12px; font-weight:600;">
                        {{ $m['icon'] }} {{ $m['label'] }}
                    </span>
                </td>
                <td>
                    <form action="{{ route('payments.updateStatus', $payment->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <select name="status" onchange="this.form.submit()"
                                style="padding:5px 8px; border-radius:7px; border:1.5px solid #e0dfd9; font-size:12px; font-weight:700; cursor:pointer;
                                       {{ $payment->status === 'paid' ? 'color:#166534; background:#dcfce7;' : ($payment->status === 'failed' ? 'color:#991b1b; background:#fee2e2;' : 'color:#92400e; background:#fef3c7;') }}">
                            <option value="pending" {{ $payment->status === 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                            <option value="paid"    {{ $payment->status === 'paid'    ? 'selected' : '' }}>✅ Lunas</option>
                            <option value="failed"  {{ $payment->status === 'failed'  ? 'selected' : '' }}>❌ Gagal</option>
                        </select>
                    </form>
                </td>
                <td style="font-size:12px; color:#9ca3af;">
                    {{ $payment->created_at->format('d M Y') }}<br>
                    <span style="font-size:11px;">{{ $payment->created_at->format('H:i') }}</span>
                </td>
                <td>
                    <form class="inline" action="{{ route('payments.destroy', $payment->id) }}" method="POST"
                          onsubmit="return confirm('Hapus pembayaran ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center empty-state"><p>Belum ada pembayaran.</p></td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="pagination-wrap">{{ $payments->links() }}</div>

@endsection
