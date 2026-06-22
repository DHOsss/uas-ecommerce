@extends('layouts.app')

@section('content')

@if(auth()->user()->isAdmin())
{{-- ══════════════ ADMIN VIEW ══════════════ --}}
<div class="page-header">
    <h1>Daftar Produk</h1>
    <a href="{{ route('products.create') }}" class="btn btn-primary">+ Tambah Produk</a>
</div>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category ?? '-' }}</td>
                <td class="price">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                <td>{{ $product->stock }}</td>
                <td>
                    <div class="actions">
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary btn-sm">Detail</a>
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form class="inline" action="{{ route('products.destroy', $product->id) }}" method="POST"
                              onsubmit="return confirm('Hapus produk ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center empty-state"><p>Belum ada produk.</p></td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="pagination-wrap">{{ $products->links() }}</div>

@else
{{-- ══════════════ CUSTOMER SHOP VIEW ══════════════ --}}

@php
$catConfig = [
    'Baju'   => ['emoji' => '👕', 'from' => '#ff6b6b', 'to' => '#ee5a24'],
    'Kemeja' => ['emoji' => '👔', 'from' => '#74b9ff', 'to' => '#0984e3'],
    'Celana' => ['emoji' => '👖', 'from' => '#636e72', 'to' => '#2d3436'],
    'Jaket'  => ['emoji' => '🧥', 'from' => '#55efc4', 'to' => '#00b894'],
    'Topi'   => ['emoji' => '🧢', 'from' => '#a29bfe', 'to' => '#6c5ce7'],
    'Sepatu' => ['emoji' => '👟', 'from' => '#fd79a8', 'to' => '#e84393'],
];
$activeCategory = request('category');
@endphp

<style>
/* ── Split buy button ── */
.split-buy-btn {
    display:flex; width:100%; border-radius:9px; overflow:hidden;
    margin-top:12px; box-shadow:0 2px 8px rgba(0,0,0,.14);
}
.split-buy-cart {
    background:#2dc5a2; padding:0 14px;
    display:flex; flex-direction:column; align-items:center; justify-content:center; flex-shrink:0;
    border:none; cursor:pointer; transition:background .15s;
}
.split-buy-cart:hover { background:#23a98a; }
.split-buy-cart-label { font-size:9px; color:#fff; font-weight:700; letter-spacing:.3px; margin-top:3px; }
.split-buy-info {
    background:#e63946; flex:1; padding:9px 10px;
    display:flex; flex-direction:column; align-items:center; justify-content:center; color:#fff;
    border:none; cursor:pointer; transition:background .15s;
}
.split-buy-info:hover { background:#c0303c; }
.split-buy-label { font-size:10px; font-weight:600; letter-spacing:.4px; opacity:.88; }
.split-buy-price { font-size:13px; font-weight:800; line-height:1.3; }
.detail-link { display:block; text-align:center; font-size:11px; color:#bbb; text-decoration:none; margin-top:7px; }
.detail-link:hover { color:#555; text-decoration:underline; }

/* ── Bottom sheet overlay ── */
#bs-overlay {
    display:none; position:fixed; inset:0; background:rgba(0,0,0,.55);
    z-index:2000; opacity:0; transition:opacity .25s;
}
#bs-overlay.open { opacity:1; }

/* ── Bottom sheet panel ── */
#bs-panel {
    display:none; position:fixed; bottom:0; left:50%; z-index:2001;
    transform:translateX(-50%) translateY(102%);
    width:100%; max-width:580px;
    background:#fff; border-radius:22px 22px 0 0;
    box-shadow:0 -8px 40px rgba(0,0,0,.18);
    transition:transform .32s cubic-bezier(.4,0,.2,1);
}
#bs-panel.open { transform:translateX(-50%) translateY(0); }

.bs-handle { width:44px; height:5px; background:#e0e0e0; border-radius:99px; margin:14px auto 0; }
.bs-close {
    position:absolute; top:12px; right:18px; background:none; border:none;
    font-size:24px; color:#aaa; cursor:pointer; line-height:1; padding:4px;
}
.bs-close:hover { color:#111; }
.bs-body { padding:16px 24px 36px; }

.bs-product-row {
    display:flex; gap:14px; align-items:center;
    margin-bottom:18px; padding-bottom:16px; border-bottom:1.5px solid #f0efeb;
}
.bs-thumb {
    width:80px; height:80px; border-radius:12px;
    display:flex; align-items:center; justify-content:center;
    font-size:36px; flex-shrink:0;
}
.bs-prod-name  { font-size:14px; font-weight:700; color:#1a1a1a; margin-bottom:5px; line-height:1.35; }
.bs-prod-price { font-size:22px; font-weight:800; color:#e63946; }
.bs-prod-stock { font-size:12px; color:#9ca3af; margin-top:3px; }

.bs-section-label {
    font-size:12px; font-weight:800; color:#1a1a1a;
    letter-spacing:.6px; text-transform:uppercase; margin-bottom:10px;
}
.bs-sizes { display:flex; flex-wrap:wrap; gap:8px; margin-bottom:22px; }
.bs-sz-radio { display:none; }
.bs-sz-chip {
    padding:9px 20px; border:1.5px solid #ddd; border-radius:7px;
    cursor:pointer; font-size:13px; font-weight:700; color:#555;
    transition:all .12s; user-select:none; min-width:52px; text-align:center;
}
.bs-sz-radio:checked + .bs-sz-chip { background:#0a0a0a; color:#fff; border-color:#0a0a0a; }
.bs-sz-chip:hover { border-color:#0a0a0a; color:#0a0a0a; }

.bs-qty-row {
    display:flex; align-items:center; justify-content:space-between; margin-bottom:24px;
}
.bs-qty-ctrl {
    display:flex; align-items:center;
    border:1.5px solid #e0dfd9; border-radius:9px; overflow:hidden;
}
.bs-qty-btn {
    width:40px; height:40px; background:#f5f4f0; border:none; cursor:pointer;
    font-size:20px; font-weight:700; color:#555;
    display:flex; align-items:center; justify-content:center; transition:background .1s;
}
.bs-qty-btn:hover { background:#0a0a0a; color:#fff; }
.bs-qty-num {
    width:54px; height:40px; border:none;
    border-left:1.5px solid #e0dfd9; border-right:1.5px solid #e0dfd9;
    text-align:center; font-size:16px; font-weight:800; background:#fff;
    -moz-appearance:textfield;
}
.bs-qty-num::-webkit-inner-spin-button,
.bs-qty-num::-webkit-outer-spin-button { -webkit-appearance:none; }
.bs-qty-num:focus { outline:none; background:#fffbf5; }

.bs-submit {
    display:block; width:100%; padding:15px; border:none; border-radius:11px;
    background:#e63946; color:#fff; font-size:15px; font-weight:800;
    cursor:pointer; letter-spacing:.3px; transition:background .15s;
}
.bs-submit:hover { background:#c0303c; }

/* Payment method options */
.bs-pm-grid { display:flex; gap:8px; margin-bottom:22px; flex-wrap:wrap; }
.bs-pm-radio { display:none; }
.bs-pm-chip {
    display:flex; flex-direction:column; align-items:center; gap:4px;
    padding:10px 14px; border:1.5px solid #ddd; border-radius:10px;
    cursor:pointer; font-size:11px; font-weight:700; color:#555;
    transition:all .12s; user-select:none; min-width:80px; text-align:center;
    background:#fff;
}
.bs-pm-chip .pm-icon { font-size:20px; line-height:1; }
.bs-pm-radio:checked + .bs-pm-chip { background:#fff8f8; border-color:#e63946; color:#e63946; box-shadow:0 0 0 1px #e63946; }
.bs-pm-chip:hover { border-color:#e63946; color:#e63946; }

.bs-total-row {
    display:flex; justify-content:space-between; align-items:center;
    padding:12px 0; border-top:1.5px solid #f0efeb; margin-bottom:16px;
}
.bs-total-label { font-size:13px; font-weight:600; color:#555; }
.bs-total-val   { font-size:18px; font-weight:800; color:#e63946; }
</style>

{{-- ══ BOTTOM SHEET MODAL (satu untuk semua produk) ══ --}}
<div id="bs-overlay" onclick="closeBuySheet()"></div>
<div id="bs-panel" role="dialog" aria-modal="true">
    <div class="bs-handle"></div>
    <button class="bs-close" onclick="closeBuySheet()" aria-label="Tutup">×</button>
    <div class="bs-body">

        {{-- Info produk --}}
        <div class="bs-product-row">
            <div class="bs-thumb" id="bs-thumb"></div>
            <div style="flex:1; min-width:0;">
                <div class="bs-prod-name"  id="bs-name"></div>
                <div class="bs-prod-price" id="bs-price"></div>
                <div class="bs-prod-stock" id="bs-stock"></div>
            </div>
        </div>

        <form id="bs-form" action="{{ route('customer.buynow') }}" method="POST">
            @csrf
            <input type="hidden" name="product_id" id="bs-product-id">

            {{-- Ukuran --}}
            <div id="bs-size-section">
                <div class="bs-section-label">Ukuran</div>
                <div class="bs-sizes" id="bs-sizes"></div>
            </div>

            {{-- Jumlah --}}
            <div class="bs-qty-row">
                <span class="bs-section-label" style="margin-bottom:0;">Jumlah</span>
                <div class="bs-qty-ctrl">
                    <button type="button" class="bs-qty-btn" onclick="bsQty(-1)">−</button>
                    <input  type="number" class="bs-qty-num" id="bs-qty" name="quantity" value="1" min="1">
                    <button type="button" class="bs-qty-btn" onclick="bsQty(1)">+</button>
                </div>
            </div>

            {{-- Metode Pembayaran --}}
            <div class="bs-section-label">Metode Pembayaran</div>
            <div class="bs-pm-grid">
                <input type="radio" name="payment_method" id="pm-bank" value="transfer_bank" class="bs-pm-radio" checked>
                <label for="pm-bank" class="bs-pm-chip">
                    <span class="pm-icon">🏦</span>Transfer Bank
                </label>

                <input type="radio" name="payment_method" id="pm-qris" value="qris" class="bs-pm-radio">
                <label for="pm-qris" class="bs-pm-chip">
                    <span class="pm-icon">📱</span>QRIS
                </label>

                <input type="radio" name="payment_method" id="pm-cod" value="cod" class="bs-pm-radio">
                <label for="pm-cod" class="bs-pm-chip">
                    <span class="pm-icon">🤝</span>COD
                </label>
            </div>

            {{-- Total --}}
            <div class="bs-total-row">
                <span class="bs-total-label">Total Bayar</span>
                <span class="bs-total-val" id="bs-total">Rp 0</span>
            </div>

            <button type="submit" class="bs-submit">Beli Langsung →</button>
        </form>
    </div>
</div>

{{-- ══ HERO ══ --}}
<div class="shop-hero">
    <div>
        <h2>Koleksi Terbaru<br><span>OUTFITKU</span></h2>
        <p>Style terbaik untuk tampil percaya diri setiap hari.</p>
        @if($activeCategory)
            <a href="{{ route('products.index') }}" class="btn btn-outline" style="border-color:rgba(255,255,255,.4); color:#fff;">
                ← Semua Produk
            </a>
        @else
            <a href="{{ route('customer.carts') }}" class="btn btn-accent btn-lg">🛒 Keranjang Saya</a>
        @endif
    </div>
</div>

{{-- Category filter --}}
<div class="category-filter">
    <a href="{{ route('products.index') }}"
       class="cat-btn {{ !$activeCategory ? 'active' : '' }}">Semua</a>
    @foreach($categories as $cat)
    <a href="{{ route('products.index', ['category' => $cat]) }}"
       class="cat-btn {{ $activeCategory === $cat ? 'active' : '' }}">
        {{ $catConfig[$cat]['emoji'] ?? '' }} {{ $cat }}
    </a>
    @endforeach
</div>

{{-- Product grid --}}
@if($products->isEmpty())
<div class="card" style="text-align:center; padding:56px 24px; color:var(--gray-400);">
    <p style="font-size:48px; margin-bottom:12px;">🛍️</p>
    <p style="font-size:16px; font-weight:600;">Belum ada produk di kategori ini.</p>
</div>
@else
<div class="product-grid">
    @foreach($products as $product)
    @php
        $cfg   = $catConfig[$product->category] ?? ['emoji' => '🏷️', 'from' => '#b2bec3', 'to' => '#636e72'];
        $isLow = $product->stock > 0 && $product->stock <= 5;
        $isOut = $product->stock === 0;
        $sizes = $product->sizes ?? [];
    @endphp
    <div class="product-card">
        <div class="product-card-thumb"
             style="background:linear-gradient(135deg,{{ $cfg['from'] }} 0%,{{ $cfg['to'] }} 100%);">
            <span class="cat-emoji">{{ $cfg['emoji'] }}</span>
            <span class="cat-label">{{ $product->category ?? 'Produk' }}</span>
        </div>
        <div class="product-card-body">
            <div class="product-card-name">{{ $product->name }}</div>
            <div class="product-card-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
            <div class="product-card-stock {{ $isLow ? 'low' : '' }}">
                @if($isOut)     ✗ Stok Habis
                @elseif($isLow) ⚡ Sisa {{ $product->stock }}
                @else           ✓ Stok {{ $product->stock }}
                @endif
            </div>

            @if($isOut)
                <a href="{{ route('products.show', $product->id) }}" class="btn btn-secondary btn-sm"
                   style="display:block; text-align:center; margin-top:14px;">Lihat Detail</a>
            @else
                {{-- Split Buy Button --}}
                <div class="split-buy-btn">
                    {{-- Kiri: Tambah ke Keranjang --}}
                    <form action="{{ route('customer.cart.quickadd') }}" method="POST" style="display:contents;">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="split-buy-cart" title="Tambah ke Keranjang">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                 stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="9"  cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                                <path d="M1 1h4l2.68 13.39a2 2 0 001.99 1.61h9.72a2 2 0 001.99-1.61L23 6H6"/>
                            </svg>
                            <span class="split-buy-cart-label">Keranjang</span>
                        </button>
                    </form>
                    {{-- Kanan: Beli Langsung --}}
                    <button type="button" class="split-buy-info"
                            data-id="{{ $product->id }}"
                            data-name="{{ $product->name }}"
                            data-price="{{ $product->price }}"
                            data-stock="{{ $product->stock }}"
                            data-sizes="{{ json_encode($sizes) }}"
                            data-emoji="{{ $cfg['emoji'] }}"
                            data-from="{{ $cfg['from'] }}"
                            data-to="{{ $cfg['to'] }}"
                            onclick="openBuySheet(this)">
                        <span class="split-buy-label">Beli Langsung</span>
                        <span class="split-buy-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    </button>
                </div>
                <a href="{{ route('products.show', $product->id) }}" class="detail-link">Lihat Detail →</a>
            @endif
        </div>
    </div>
    @endforeach
</div>
<div class="pagination-wrap">{{ $products->links() }}</div>
@endif

@endif

<script>
var bsMaxStock  = 1;
var bsUnitPrice = 0;

function updateBsTotal() {
    var qty   = parseInt(document.getElementById('bs-qty').value) || 1;
    var total = bsUnitPrice * qty;
    document.getElementById('bs-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
}

function openBuySheet(btn) {
    var id    = btn.dataset.id;
    var name  = btn.dataset.name;
    var price = parseInt(btn.dataset.price);
    var stock = parseInt(btn.dataset.stock);
    var sizes = JSON.parse(btn.dataset.sizes || '[]');
    var emoji = btn.dataset.emoji;
    var from  = btn.dataset.from;
    var to    = btn.dataset.to;

    bsMaxStock  = stock;
    bsUnitPrice = price;

    // Isi info produk
    document.getElementById('bs-product-id').value = id;
    document.getElementById('bs-name').textContent  = name;
    document.getElementById('bs-price').textContent = 'Rp ' + price.toLocaleString('id-ID');
    document.getElementById('bs-stock').textContent = 'Stok tersedia: ' + stock;
    document.getElementById('bs-qty').value = 1;
    document.getElementById('bs-qty').max   = stock;

    // Thumbnail
    var thumb = document.getElementById('bs-thumb');
    thumb.textContent      = emoji;
    thumb.style.background = 'linear-gradient(135deg,' + from + ' 0%,' + to + ' 100%)';

    // Size chips dinamis
    var section  = document.getElementById('bs-size-section');
    var sizesDiv = document.getElementById('bs-sizes');
    sizesDiv.innerHTML = '';
    if (sizes.length > 0) {
        section.style.display = 'block';
        sizes.forEach(function(sz, i) {
            var radio = document.createElement('input');
            radio.type = 'radio'; radio.name = 'size';
            radio.id = 'bs-sz-' + i; radio.value = sz;
            radio.className = 'bs-sz-radio';
            if (i === 0) radio.checked = true;
            var label = document.createElement('label');
            label.htmlFor = 'bs-sz-' + i;
            label.className = 'bs-sz-chip';
            label.textContent = sz;
            sizesDiv.appendChild(radio);
            sizesDiv.appendChild(label);
        });
    } else {
        section.style.display = 'none';
    }

    // Reset payment method ke pertama
    var pmFirst = document.getElementById('pm-bank');
    if (pmFirst) pmFirst.checked = true;

    updateBsTotal();

    // Tampilkan dengan animasi
    var overlay = document.getElementById('bs-overlay');
    var panel   = document.getElementById('bs-panel');
    overlay.style.display = 'block';
    panel.style.display   = 'block';
    document.body.style.overflow = 'hidden';
    requestAnimationFrame(function() {
        requestAnimationFrame(function() {
            overlay.classList.add('open');
            panel.classList.add('open');
        });
    });
}

function closeBuySheet() {
    var overlay = document.getElementById('bs-overlay');
    var panel   = document.getElementById('bs-panel');
    overlay.classList.remove('open');
    panel.classList.remove('open');
    setTimeout(function() {
        overlay.style.display = 'none';
        panel.style.display   = 'none';
        document.body.style.overflow = '';
    }, 320);
}

function bsQty(delta) {
    var input = document.getElementById('bs-qty');
    var val   = parseInt(input.value) || 1;
    input.value = Math.max(1, Math.min(bsMaxStock, val + delta));
    updateBsTotal();
}

document.getElementById('bs-qty')?.addEventListener('input', updateBsTotal);

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeBuySheet();
});
</script>
@endsection
