@extends('layouts.app')

@section('content')

@php
$catConfig = [
    'Baju'   => ['emoji' => '👕', 'from' => '#ff6b6b', 'to' => '#ee5a24'],
    'Kemeja' => ['emoji' => '👔', 'from' => '#74b9ff', 'to' => '#0984e3'],
    'Celana' => ['emoji' => '👖', 'from' => '#636e72', 'to' => '#2d3436'],
    'Jaket'  => ['emoji' => '🧥', 'from' => '#55efc4', 'to' => '#00b894'],
    'Topi'   => ['emoji' => '🧢', 'from' => '#a29bfe', 'to' => '#6c5ce7'],
    'Sepatu' => ['emoji' => '👟', 'from' => '#fd79a8', 'to' => '#e84393'],
];
@endphp

<style>
.cart-wrap { display:flex; gap:24px; align-items:flex-start; }
.cart-items-col { flex:1; min-width:0; }
.cart-summary-col { width:300px; min-width:280px; position:sticky; top:20px; }

.cart-item-card {
    display:flex; align-items:center; gap:16px;
    background:#fff; border:1.5px solid #e8e7e3; border-radius:12px;
    padding:16px; margin-bottom:12px;
    transition: box-shadow .15s;
}
.cart-item-card:hover { box-shadow:0 4px 16px rgba(0,0,0,.08); }

.cart-thumb {
    width:72px; height:72px; border-radius:10px;
    display:flex; align-items:center; justify-content:center;
    font-size:32px; flex-shrink:0;
}
.cart-item-info { flex:1; min-width:0; }
.cart-item-name { font-weight:700; font-size:15px; color:#1a1a1a; margin-bottom:4px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.cart-item-meta { display:flex; align-items:center; gap:8px; margin-bottom:6px; flex-wrap:wrap; }
.size-badge { background:#f0efeb; border:1.5px solid #e0dfd9; border-radius:999px; padding:2px 10px; font-size:11px; font-weight:700; color:#555; }
.cart-item-price-unit { font-size:12px; color:#9ca3af; }

.qty-control { display:flex; align-items:center; gap:0; border:1.5px solid #e0dfd9; border-radius:8px; overflow:hidden; width:100px; }
.qty-btn { width:30px; height:34px; background:#f5f4f0; border:none; cursor:pointer; font-size:16px; font-weight:700; color:#555; display:flex; align-items:center; justify-content:center; transition: background .1s; }
.qty-btn:hover { background:#e0dfd9; }
.qty-num { width:40px; height:34px; border:none; border-left:1.5px solid #e0dfd9; border-right:1.5px solid #e0dfd9; text-align:center; font-size:14px; font-weight:700; background:#fff; }
.qty-num:focus { outline:none; background:#fffbf5; }

.cart-item-right { display:flex; flex-direction:column; align-items:flex-end; gap:8px; flex-shrink:0; }
.cart-item-subtotal { font-size:15px; font-weight:800; color:#1a1a1a; }
.cart-remove-btn { background:none; border:1.5px solid #fecaca; border-radius:6px; color:#ef4444; padding:4px 10px; font-size:12px; cursor:pointer; transition: all .15s; }
.cart-remove-btn:hover { background:#fef2f2; border-color:#ef4444; }

.cart-empty { text-align:center; padding:72px 24px; color:#9ca3af; }
.cart-empty-icon { font-size:64px; margin-bottom:16px; }
.cart-empty-text { font-size:18px; font-weight:600; color:#6b7280; margin-bottom:8px; }

.summary-card { background:#fff; border:1.5px solid #e8e7e3; border-radius:16px; padding:24px; }
.summary-title { font-size:16px; font-weight:800; color:#1a1a1a; margin-bottom:20px; padding-bottom:12px; border-bottom:1.5px solid #f0efeb; }
.summary-row { display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; font-size:14px; color:#555; }
.summary-row span:last-child { font-weight:600; color:#1a1a1a; }
.summary-divider { border:none; border-top:1.5px solid #f0efeb; margin:16px 0; }
.summary-total-row { display:flex; justify-content:space-between; align-items:center; font-size:16px; font-weight:800; color:#1a1a1a; margin-bottom:20px; }
.summary-total-row span:last-child { color:#e63946; font-size:18px; }

.voucher-row { display:flex; gap:8px; margin-bottom:8px; }
.voucher-row input { flex:1; padding:8px 12px; border:1.5px solid #e0dfd9; border-radius:8px; font-size:13px; text-transform:uppercase; }
.voucher-row button { padding:8px 14px; border:none; background:#0a0a0a; color:#fff; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; white-space:nowrap; }
.voucher-row button:hover { background:#e63946; }
.voucher-msg { font-size:12px; min-height:16px; margin-bottom:8px; }
.discount-row { display:flex; justify-content:space-between; font-size:13px; color:#16a34a; margin-bottom:8px; font-weight:600; }

.btn-checkout { display:block; width:100%; padding:14px; background:#0a0a0a; color:#fff; border:none; border-radius:10px; font-size:15px; font-weight:800; cursor:pointer; text-align:center; transition: background .15s; letter-spacing:.4px; }
.btn-checkout:hover { background:#e63946; }
.btn-continue { display:block; text-align:center; color:#6b7280; font-size:13px; margin-top:12px; text-decoration:none; }
.btn-continue:hover { color:#1a1a1a; }

.cart-count-badge { display:inline-flex; align-items:center; justify-content:center; background:#e63946; color:#fff; border-radius:999px; min-width:22px; height:22px; font-size:11px; font-weight:800; padding:0 6px; margin-left:8px; }

@media (max-width: 768px) {
    .cart-wrap { flex-direction: column; }
    .cart-summary-col { width:100%; position:static; }
}
</style>

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px;">
    <h1 style="margin:0; display:flex; align-items:center; gap:4px;">
        Keranjang Saya
        @if($carts->count() > 0)
        <span class="cart-count-badge">{{ $carts->count() }}</span>
        @endif
    </h1>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">← Lanjut Belanja</a>
</div>

@if($errors->has('cart'))
<div class="alert alert-error" style="margin-bottom:16px;">&#9888; {{ $errors->first('cart') }}</div>
@endif

@if($carts->isEmpty())
<div class="card cart-empty">
    <div class="cart-empty-icon">🛒</div>
    <div class="cart-empty-text">Keranjang kamu kosong</div>
    <p style="margin-bottom:24px;">Yuk, mulai pilih produk favoritmu!</p>
    <a href="{{ route('products.index') }}" class="btn btn-primary">Mulai Belanja</a>
</div>

@else

@php
$grandSubtotal = $carts->sum(fn($c) => ($c->product->price ?? 0) * $c->quantity);
@endphp

<form action="{{ route('customer.cart.checkout') }}" method="POST" id="checkout-form">
@csrf

<div class="cart-wrap">

    {{-- KOLOM KIRI: Cart Items --}}
    <div class="cart-items-col">
        @foreach($carts as $cart)
        @php
            $product = $cart->product;
            $cfg = $catConfig[$product->category ?? ''] ?? ['emoji' => '🏷️', 'from' => '#b2bec3', 'to' => '#636e72'];
        @endphp

        {{-- Hidden input for quantity (updated by JS) --}}
        <input type="hidden" name="cart_quantities[{{ $cart->id }}]"
               id="qty-hidden-{{ $cart->id }}" value="{{ $cart->quantity }}">

        <div class="cart-item-card">
            {{-- Thumbnail --}}
            <div class="cart-thumb"
                 style="background:linear-gradient(135deg, {{ $cfg['from'] }} 0%, {{ $cfg['to'] }} 100%);">
                {{ $cfg['emoji'] }}
            </div>

            {{-- Info --}}
            <div class="cart-item-info">
                <div class="cart-item-name">{{ $product->name ?? 'Produk tidak tersedia' }}</div>
                <div class="cart-item-meta">
                    @if($cart->size)
                    <span class="size-badge">{{ $cart->size }}</span>
                    @endif
                    <span class="cart-item-price-unit">Rp {{ number_format($product->price ?? 0, 0, ',', '.') }} / pcs</span>
                </div>
                {{-- Quantity control --}}
                <div class="qty-control">
                    <button type="button" class="qty-btn" onclick="adjustQty({{ $cart->id }}, -1, {{ $product->stock ?? 0 }}, {{ $product->price ?? 0 }})">−</button>
                    <input type="text" class="qty-num" id="qty-display-{{ $cart->id }}"
                           value="{{ $cart->quantity }}" readonly>
                    <button type="button" class="qty-btn" onclick="adjustQty({{ $cart->id }}, 1, {{ $product->stock ?? 0 }}, {{ $product->price ?? 0 }})">+</button>
                </div>
            </div>

            {{-- Kanan: Subtotal + Hapus --}}
            <div class="cart-item-right">
                <div class="cart-item-subtotal" id="subtotal-{{ $cart->id }}">
                    Rp {{ number_format(($product->price ?? 0) * $cart->quantity, 0, ',', '.') }}
                </div>
                <button type="submit" class="cart-remove-btn"
                        form="del-cart-{{ $cart->id }}"
                        onclick="return confirm('Hapus item ini dari keranjang?')">✕ Hapus</button>
            </div>
        </div>
        @endforeach
    </div>

    {{-- KOLOM KANAN: Ringkasan & Checkout --}}
    <div class="cart-summary-col">
        <div class="summary-card">
            <div class="summary-title">Ringkasan Pesanan</div>

            <div class="summary-row">
                <span>Subtotal (<span id="item-count">{{ $carts->count() }}</span> produk)</span>
                <span id="grand-subtotal">Rp {{ number_format($grandSubtotal, 0, ',', '.') }}</span>
            </div>

            <hr class="summary-divider">

            {{-- Voucher --}}
            <div style="font-size:13px; font-weight:600; margin-bottom:8px; color:#555;">Kode Voucher</div>
            <div class="voucher-row">
                <input type="text" id="voucher-input" placeholder="Masukkan kode..." autocomplete="off">
                <button type="button" id="btn-apply-voucher">Terapkan</button>
            </div>
            <div class="voucher-msg" id="voucher-msg"></div>
            <input type="hidden" name="voucher_code" id="voucher-code-hidden">

            <div class="discount-row" id="discount-row" style="display:none;">
                <span>Diskon (<span id="discount-pct">0</span>%)</span>
                <span id="discount-amount">- Rp 0</span>
            </div>

            <hr class="summary-divider">

            <div class="summary-total-row">
                <span>Total Bayar</span>
                <span id="grand-total">Rp {{ number_format($grandSubtotal, 0, ',', '.') }}</span>
            </div>

            <button type="submit" class="btn-checkout">
                🛒 &nbsp;Buat Pesanan
            </button>
            <a href="{{ route('products.index') }}" class="btn-continue">← Lanjut belanja</a>
        </div>
    </div>

</div>
</form>

{{-- Delete forms di luar checkout form agar tidak menyebabkan nested form --}}
@foreach($carts as $cart)
<form id="del-cart-{{ $cart->id }}"
      action="{{ route('customer.carts.destroy', $cart->id) }}"
      method="POST" style="display:none;">
    @csrf @method('DELETE')
</form>
@endforeach

@endif

<script>
const prices = {
    @foreach($carts as $cart)
    {{ $cart->id }}: {{ $cart->product->price ?? 0 }},
    @endforeach
};

let discountPct = 0;
let minPurchase = 0;

function formatRp(num) {
    return 'Rp ' + Math.round(num).toLocaleString('id-ID');
}

function adjustQty(id, delta, maxStock, price) {
    const hiddenInput   = document.getElementById('qty-hidden-' + id);
    const displayInput  = document.getElementById('qty-display-' + id);
    const currentVal    = parseInt(hiddenInput.value) || 1;
    const newVal        = Math.max(1, Math.min(maxStock, currentVal + delta));
    hiddenInput.value   = newVal;
    displayInput.value  = newVal;
    updateSubtotal(id, price, newVal);
    recalcGrandTotal();
}

function updateSubtotal(id, price, qty) {
    const el = document.getElementById('subtotal-' + id);
    if (el) el.textContent = formatRp(price * qty);
}

function recalcGrandTotal() {
    let subtotal = 0;
    Object.keys(prices).forEach(id => {
        const qty = parseInt(document.getElementById('qty-hidden-' + id)?.value) || 0;
        subtotal += prices[id] * qty;
    });

    document.getElementById('grand-subtotal').textContent = formatRp(subtotal);

    let discount = 0;
    const discountRow = document.getElementById('discount-row');
    if (discountPct > 0 && subtotal >= minPurchase) {
        discount = subtotal * discountPct / 100;
        discountRow.style.display = 'flex';
        document.getElementById('discount-amount').textContent = '- ' + formatRp(discount);
    } else {
        discountRow.style.display = 'none';
    }

    document.getElementById('grand-total').textContent = formatRp(subtotal - discount);
}

// Voucher check
const voucherCheckUrl = "{{ route('voucher.check') }}";

document.getElementById('btn-apply-voucher')?.addEventListener('click', function() {
    const code = document.getElementById('voucher-input').value.trim().toUpperCase();
    const msg  = document.getElementById('voucher-msg');
    if (!code) {
        msg.style.color = '#ef4444';
        msg.textContent = 'Masukkan kode voucher terlebih dahulu.';
        return;
    }
    msg.style.color = '#9ca3af';
    msg.textContent = 'Mengecek...';

    fetch(`${voucherCheckUrl}?code=${code}`)
        .then(r => r.json())
        .then(data => {
            if (data.valid) {
                discountPct = data.discount;
                minPurchase = data.min_purchase;
                document.getElementById('discount-pct').textContent = data.discount;
                document.getElementById('voucher-code-hidden').value = code;
                msg.style.color = '#16a34a';
                msg.textContent = '✓ ' + data.message;
            } else {
                discountPct = 0;
                minPurchase = 0;
                document.getElementById('voucher-code-hidden').value = '';
                msg.style.color = '#ef4444';
                msg.textContent = data.message;
            }
            recalcGrandTotal();
        })
        .catch(() => {
            msg.style.color = '#ef4444';
            msg.textContent = 'Gagal memeriksa voucher.';
        });
});

document.getElementById('voucher-input')?.addEventListener('input', function() {
    if (!this.value.trim()) {
        discountPct = 0;
        minPurchase = 0;
        document.getElementById('voucher-code-hidden').value = '';
        document.getElementById('voucher-msg').textContent = '';
        recalcGrandTotal();
    }
});
</script>

@endsection
