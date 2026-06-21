@extends('layouts.app')

@section('content')

<style>
.admin-hero {
    background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 100%);
    border-radius: 16px; padding: 28px 32px; margin-bottom: 24px;
    display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;
}
.admin-hero h2 { color: #fff; font-size: 22px; font-weight: 800; margin: 0 0 4px; }
.admin-hero p  { color: #9ca3af; font-size: 13px; margin: 0; }
.admin-hero-date { color: #6b7280; font-size: 12px; background: rgba(255,255,255,.06); padding: 6px 14px; border-radius: 999px; }

.kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 14px; margin-bottom: 24px; }
.kpi-card {
    background: #fff; border: 1.5px solid #e8e7e3; border-radius: 14px;
    padding: 20px 20px 16px; position: relative; overflow: hidden;
    transition: box-shadow .15s, transform .15s;
}
.kpi-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,.09); transform: translateY(-2px); }
.kpi-icon {
    width: 42px; height: 42px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; margin-bottom: 12px;
}
.kpi-val   { font-size: 22px; font-weight: 900; color: #0a0a0a; margin-bottom: 3px; line-height: 1.1; }
.kpi-label { font-size: 12px; color: #9ca3af; font-weight: 600; }
.kpi-sub   { font-size: 11px; margin-top: 6px; font-weight: 600; }
.kpi-sub.warn { color: #f59e0b; }
.kpi-sub.ok   { color: #16a34a; }
.kpi-accent { position: absolute; bottom: 0; left: 0; right: 0; height: 3px; border-radius: 0 0 12px 12px; }

.admin-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
@media (max-width: 768px) { .admin-grid { grid-template-columns: 1fr; } }

.admin-card { background: #fff; border: 1.5px solid #e8e7e3; border-radius: 14px; overflow: hidden; }
.admin-card-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 20px; border-bottom: 1.5px solid #f0efeb;
}
.admin-card-title { font-size: 13px; font-weight: 800; color: #1a1a1a; }
.admin-card-link  { font-size: 12px; color: #e63946; text-decoration: none; font-weight: 600; }
.admin-card-link:hover { text-decoration: underline; }

.admin-table { width: 100%; border-collapse: collapse; }
.admin-table th {
    font-size: 10px; font-weight: 800; color: #9ca3af; text-transform: uppercase;
    letter-spacing: .5px; padding: 10px 16px; text-align: left;
    background: #fafafa; border-bottom: 1.5px solid #f0efeb;
}
.admin-table td { padding: 11px 16px; font-size: 13px; border-bottom: 1px solid #f5f4f0; color: #374151; }
.admin-table tr:last-child td { border-bottom: none; }
.admin-table tr:hover td { background: #fafafa; }
.admin-table .price { font-weight: 700; color: #1a1a1a; }

.badge-sm {
    display: inline-flex; align-items: center; padding: 2px 9px;
    border-radius: 999px; font-size: 11px; font-weight: 700;
}
.badge-pending   { background: #fef3c7; color: #92400e; }
.badge-confirmed { background: #dcfce7; color: #166534; }
.badge-cancelled { background: #fee2e2; color: #991b1b; }
.badge-paid      { background: #dcfce7; color: #166534; }
.badge-failed    { background: #fee2e2; color: #991b1b; }

.quick-actions { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 24px; }
.qa-btn {
    display: flex; align-items: center; gap: 8px;
    background: #fff; border: 1.5px solid #e8e7e3; border-radius: 10px;
    padding: 10px 16px; font-size: 13px; font-weight: 700; color: #1a1a1a;
    text-decoration: none; transition: all .15s;
}
.qa-btn:hover { background: #0a0a0a; color: #fff; border-color: #0a0a0a; }
.qa-btn span  { font-size: 16px; }

.empty-row td { text-align: center; padding: 28px 16px; color: #9ca3af; font-size: 13px; }
</style>

{{-- Hero --}}
<div class="admin-hero">
    <div>
        <h2>Halo, {{ auth()->user()->name }}! 👋</h2>
        <p>Berikut ringkasan aktivitas toko Outfitku.</p>
    </div>
    <div class="admin-hero-date">{{ now()->translatedFormat('l, d F Y') }}</div>
</div>

{{-- KPI Cards --}}
<div class="kpi-grid">

    <div class="kpi-card">
        <div class="kpi-icon" style="background:#fff0f0;">💰</div>
        <div class="kpi-val">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</div>
        <div class="kpi-label">Total Pendapatan</div>
        <div class="kpi-accent" style="background:#e63946;"></div>
    </div>

    <div class="kpi-card">
        <div class="kpi-icon" style="background:#eff6ff;">📦</div>
        <div class="kpi-val">{{ $stats['orders_total'] }}</div>
        <div class="kpi-label">Total Pesanan</div>
        @if($stats['orders_pending'] > 0)
        <div class="kpi-sub warn">⚠ {{ $stats['orders_pending'] }} menunggu konfirmasi</div>
        @else
        <div class="kpi-sub ok">✓ Semua sudah dikonfirmasi</div>
        @endif
        <div class="kpi-accent" style="background:#3b82f6;"></div>
    </div>

    <div class="kpi-card">
        <div class="kpi-icon" style="background:#f0fdf4;">💳</div>
        <div class="kpi-val">{{ $stats['payments_pending'] }}</div>
        <div class="kpi-label">Pembayaran Pending</div>
        @if($stats['payments_pending'] > 0)
        <div class="kpi-sub warn">⚠ Perlu dikonfirmasi</div>
        @else
        <div class="kpi-sub ok">✓ Tidak ada yang pending</div>
        @endif
        <div class="kpi-accent" style="background:#16a34a;"></div>
    </div>

    <div class="kpi-card">
        <div class="kpi-icon" style="background:#faf5ff;">👥</div>
        <div class="kpi-val">{{ $stats['customers_total'] }}</div>
        <div class="kpi-label">Total Customer</div>
        <div class="kpi-accent" style="background:#8b5cf6;"></div>
    </div>

    <div class="kpi-card">
        <div class="kpi-icon" style="background:#fff8f0;">🛍️</div>
        <div class="kpi-val">{{ $stats['products_total'] }}</div>
        <div class="kpi-label">Total Produk</div>
        <div class="kpi-accent" style="background:#f59e0b;"></div>
    </div>

    <div class="kpi-card">
        <div class="kpi-icon" style="background:#f0f9ff;">⭐</div>
        <div class="kpi-val">{{ $stats['reviews_total'] }}</div>
        <div class="kpi-label">Total Ulasan</div>
        <div class="kpi-accent" style="background:#0ea5e9;"></div>
    </div>

</div>

{{-- Quick Actions --}}
<div class="quick-actions">
    <a href="{{ route('products.create') }}"  class="qa-btn"><span>➕</span> Tambah Produk</a>
    <a href="{{ route('orders.index') }}"     class="qa-btn"><span>📦</span> Kelola Pesanan</a>
    <a href="{{ route('payments.index') }}"   class="qa-btn"><span>💳</span> Konfirmasi Bayar</a>
    <a href="{{ route('vouchers.create') }}"  class="qa-btn"><span>🎟️</span> Buat Voucher</a>
    <a href="{{ route('customers.index') }}"  class="qa-btn"><span>👥</span> Data Customer</a>
</div>

{{-- Recent Tables --}}
<div class="admin-grid">

    {{-- Pesanan Terbaru --}}
    <div class="admin-card">
        <div class="admin-card-head">
            <span class="admin-card-title">📦 Pesanan Terbaru</span>
            <a href="{{ route('orders.index') }}" class="admin-card-link">Lihat semua →</a>
        </div>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                <tr>
                    <td>
                        <div style="font-weight:700; font-size:13px;">{{ $order->customer_name }}</div>
                        <div style="font-size:11px; color:#9ca3af;">{{ $order->created_at->diffForHumans() }}</div>
                    </td>
                    <td class="price">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge-sm badge-{{ $order->status }}">
                            {{ $order->status === 'pending' ? 'Pending' : ($order->status === 'confirmed' ? 'Dikonfirmasi' : 'Dibatalkan') }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr class="empty-row"><td colspan="3">Belum ada pesanan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pembayaran Terbaru --}}
    <div class="admin-card">
        <div class="admin-card-head">
            <span class="admin-card-title">💳 Pembayaran Terbaru</span>
            <a href="{{ route('payments.index') }}" class="admin-card-link">Lihat semua →</a>
        </div>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentPayments as $payment)
                <tr>
                    <td>
                        <div style="font-weight:700; font-size:13px;">{{ $payment->order->customer_name ?? '-' }}</div>
                        <div style="font-size:11px; color:#9ca3af;">
                            @php
                            $icons = ['transfer_bank'=>'🏦','qris'=>'📱','cod'=>'🤝','transfer'=>'🏦','cash'=>'💵','ewallet'=>'📲'];
                            @endphp
                            {{ $icons[$payment->method] ?? '💳' }} {{ $payment->method }}
                        </div>
                    </td>
                    <td class="price">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge-sm badge-{{ $payment->status }}">
                            {{ $payment->status === 'paid' ? 'Lunas' : ($payment->status === 'pending' ? 'Pending' : 'Gagal') }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr class="empty-row"><td colspan="3">Belum ada pembayaran.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
