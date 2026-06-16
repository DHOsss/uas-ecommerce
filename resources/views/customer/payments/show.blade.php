@extends('layouts.app')

@section('content')

@php
$methodLabels = [
    'transfer_bank' => ['label' => 'Transfer Bank',     'icon' => '🏦'],
    'qris'          => ['label' => 'QRIS',              'icon' => '📱'],
    'cod'           => ['label' => 'COD (Bayar di Tempat)', 'icon' => '🤝'],
    'transfer'      => ['label' => 'Transfer Bank',     'icon' => '🏦'],
    'cash'          => ['label' => 'Tunai',             'icon' => '💵'],
    'ewallet'       => ['label' => 'E-Wallet',          'icon' => '📲'],
];
$method  = $methodLabels[$payment->method] ?? ['label' => ucfirst($payment->method), 'icon' => '💳'];
$isPaid  = $payment->status === 'paid';
$isFail  = $payment->status === 'failed';
@endphp

<style>
.pay-confirm-wrap { max-width:540px; margin:0 auto; }

.pay-status-banner {
    text-align:center; padding:32px 24px 24px; border-radius:16px; margin-bottom:24px;
}
.pay-status-banner.pending { background:linear-gradient(135deg,#fff8f0,#fff3e0); border:1.5px solid #fed7aa; }
.pay-status-banner.paid    { background:linear-gradient(135deg,#f0fff4,#dcfce7); border:1.5px solid #86efac; }
.pay-status-banner.failed  { background:linear-gradient(135deg,#fff5f5,#fee2e2); border:1.5px solid #fca5a5; }

.pay-status-icon  { font-size:52px; margin-bottom:12px; }
.pay-status-title { font-size:20px; font-weight:800; margin-bottom:6px; }
.pay-status-sub   { font-size:13px; color:#6b7280; }

.pay-card { background:#fff; border:1.5px solid #e8e7e3; border-radius:14px; padding:20px 24px; margin-bottom:16px; }
.pay-card-title { font-size:12px; font-weight:800; color:#9ca3af; letter-spacing:.6px; text-transform:uppercase; margin-bottom:14px; }

.pay-info-row { display:flex; justify-content:space-between; align-items:center; padding:8px 0; border-bottom:1px solid #f5f4f0; font-size:14px; }
.pay-info-row:last-child { border-bottom:none; }
.pay-info-label { color:#6b7280; }
.pay-info-val   { font-weight:700; color:#1a1a1a; text-align:right; }
.pay-info-val.price { color:#e63946; font-size:16px; font-weight:800; }

.pay-method-box {
    display:flex; align-items:center; gap:14px; padding:14px 16px;
    background:#f9fafb; border:1.5px solid #e8e7e3; border-radius:10px;
    margin-bottom:14px;
}
.pay-method-icon { font-size:28px; }
.pay-method-name { font-weight:800; font-size:15px; color:#1a1a1a; }
.pay-method-sub  { font-size:12px; color:#9ca3af; margin-top:2px; }

.pay-instruction-box {
    background:#fffbf0; border:1.5px solid #fde68a; border-radius:10px;
    padding:16px 18px; margin-bottom:0;
}
.pay-instruction-title { font-size:13px; font-weight:800; color:#92400e; margin-bottom:10px; }
.pay-instruction-item  { font-size:13px; color:#78350f; padding:4px 0; display:flex; gap:8px; align-items:flex-start; }
.pay-instruction-item::before { content:'→'; font-weight:700; flex-shrink:0; }

.pay-actions { display:flex; gap:10px; margin-top:20px; flex-direction:column; }
.pay-actions .btn { text-align:center; }
</style>

<div class="pay-confirm-wrap">

    {{-- Status Banner --}}
    <div class="pay-status-banner {{ $payment->status }}">
        <div class="pay-status-icon">
            @if($isPaid)  ✅
            @elseif($isFail) ❌
            @else         ⏳
            @endif
        </div>
        <div class="pay-status-title">
            @if($isPaid)  Pembayaran Lunas!
            @elseif($isFail) Pembayaran Gagal
            @else         Menunggu Pembayaran
            @endif
        </div>
        <div class="pay-status-sub">
            @if($isPaid)  Terima kasih! Pesanan kamu sedang diproses.
            @elseif($isFail) Hubungi admin jika ada pertanyaan.
            @else         Selesaikan pembayaran sebelum pesananmu diproses.
            @endif
        </div>
    </div>

    {{-- Info Pembayaran --}}
    <div class="pay-card">
        <div class="pay-card-title">Detail Pembayaran</div>
        <div class="pay-info-row">
            <span class="pay-info-label">No. Pembayaran</span>
            <span class="pay-info-val">#{{ str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="pay-info-row">
            <span class="pay-info-label">No. Pesanan</span>
            <span class="pay-info-val">#{{ str_pad($payment->order_id, 5, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="pay-info-row">
            <span class="pay-info-label">Tanggal</span>
            <span class="pay-info-val">{{ $payment->created_at->format('d M Y, H:i') }}</span>
        </div>
        @if($payment->paid_at)
        <div class="pay-info-row">
            <span class="pay-info-label">Tanggal Lunas</span>
            <span class="pay-info-val" style="color:#16a34a;">{{ $payment->paid_at->format('d M Y, H:i') }}</span>
        </div>
        @endif
        <div class="pay-info-row">
            <span class="pay-info-label">Total Bayar</span>
            <span class="pay-info-val price">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
        </div>
    </div>

    {{-- Metode Pembayaran + Instruksi --}}
    <div class="pay-card">
        <div class="pay-card-title">Metode Pembayaran</div>
        <div class="pay-method-box">
            <span class="pay-method-icon">{{ $method['icon'] }}</span>
            <div>
                <div class="pay-method-name">{{ $method['label'] }}</div>
                <div class="pay-method-sub">
                    @if($isPaid) Sudah dikonfirmasi
                    @else Status: Menunggu konfirmasi admin
                    @endif
                </div>
            </div>
        </div>

        @if(!$isPaid && !$isFail)
        <div class="pay-instruction-box">
            <div class="pay-instruction-title">📋 Cara Pembayaran</div>
            @if($payment->method === 'transfer_bank' || $payment->method === 'transfer')
                <div class="pay-instruction-item">Transfer ke rekening yang tertera di bawah ini.</div>
                <div class="pay-instruction-item">BCA: <strong>1234-5678-90</strong> a.n. OUTFITKU STORE</div>
                <div class="pay-instruction-item">Jumlah tepat: <strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong></div>
            @elseif($payment->method === 'qris')
                <div style="text-align:center; margin-bottom:14px;">
                    <img src="{{ asset('images/qris.jpeg') }}" alt="QRIS Outfitku"
                         style="width:200px; height:200px; object-fit:contain; border-radius:10px; border:2px solid #fde68a;">
                    <div style="font-size:11px; color:#92400e; margin-top:6px; font-weight:700;">Scan QR di atas untuk membayar</div>
                </div>
                <div class="pay-instruction-item">Buka aplikasi dompet digital kamu (GoPay, OVO, Dana, dll).</div>
                <div class="pay-instruction-item">Scan QR Code di atas lalu masukkan nominal <strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong></div>
                <div class="pay-instruction-item">Screenshot bukti pembayaran dan kirim ke admin.</div>
            @elseif($payment->method === 'cod')
                <div class="pay-instruction-item">Bayar saat barang tiba di alamat kamu.</div>
                <div class="pay-instruction-item">Siapkan uang pas: <strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong></div>
                <div class="pay-instruction-item">Pesanan akan dikirim setelah dikonfirmasi admin.</div>
            @else
                <div class="pay-instruction-item">Hubungi admin untuk informasi pembayaran lebih lanjut.</div>
            @endif
        </div>
        @endif
    </div>

    {{-- Info Pesanan --}}
    @if($payment->order)
    <div class="pay-card">
        <div class="pay-card-title">Ringkasan Pesanan</div>
        @foreach($payment->order->orderItems ?? [] as $item)
        <div class="pay-info-row">
            <span class="pay-info-label">
                {{ $item->product->name ?? 'Produk' }}
                @if($item->size) <span style="background:#f0efeb; padding:1px 7px; border-radius:4px; font-size:11px; font-weight:700;">{{ $item->size }}</span> @endif
                × {{ $item->quantity }}
            </span>
            <span class="pay-info-val">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Aksi --}}
    <div class="pay-actions">
        <a href="{{ route('products.index') }}" class="btn btn-primary">🛍️ &nbsp;Lanjut Belanja</a>
        <a href="{{ route('customer.payments') }}" class="btn btn-secondary">Lihat Semua Pembayaran</a>
        <a href="{{ route('customer.orders') }}"   class="btn btn-secondary">Lihat Pesanan Saya</a>
    </div>

</div>
@endsection
