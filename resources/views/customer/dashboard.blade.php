@extends('layouts.app')

@section('content')

<div class="shop-hero">
    <div>
        <h2>Halo, {{ auth()->user()->name }}! 👋<br>Selamat datang di <span>OUTFITKU</span></h2>
        <p>Temukan koleksi outfit terbaik — baju, celana, jaket, sepatu, dan lebih banyak lagi.</p>
        <a href="{{ route('products.index') }}" class="btn btn-accent btn-xl">Mulai Belanja</a>
    </div>
</div>

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-card-label">Menu</div>
        <div style="display:flex; flex-direction:column; gap:10px; margin-top:12px;">
            <a href="{{ route('products.index') }}" class="btn btn-primary">🛍️ &nbsp;Lihat Produk</a>
            <a href="{{ route('customer.carts') }}" class="btn btn-secondary">🛒 &nbsp;Keranjang</a>
            <a href="{{ route('customer.orders') }}" class="btn btn-secondary">📦 &nbsp;Pesanan Saya</a>
            <a href="{{ route('customer.payments') }}" class="btn btn-secondary">💳 &nbsp;Pembayaran</a>
            <a href="{{ route('customer.reviews') }}" class="btn btn-secondary">⭐ &nbsp;Ulasan Saya</a>
        </div>
    </div>
    <div class="stat-card" style="grid-column: span 2;">
        <div class="stat-card-label">Tentang Outfitku</div>
        <div style="margin-top:12px; color:var(--gray-600); line-height:1.8;">
            <p style="margin-bottom:8px;">
                <strong>OUTFITKU</strong> menghadirkan koleksi fashion streetwear & casual untuk pria dan wanita.
                Dari kaos polos hingga sepatu sneakers, semua ada di sini.
            </p>
            <div style="display:flex; gap:8px; flex-wrap:wrap; margin-top:12px;">
                @foreach([['Baju','👕'],['Kemeja','👔'],['Celana','👖'],['Jaket','🧥'],['Topi','🧢'],['Sepatu','👟']] as [$cat,$emoji])
                <a href="{{ route('products.index', ['category' => $cat]) }}"
                   style="background:#f0efeb; padding:5px 14px; border-radius:999px; font-size:12px; font-weight:600;
                          text-decoration:none; color:#1a1a1a; border:1.5px solid #e0dfd9; transition:all .15s;"
                   onmouseover="this.style.background='#0a0a0a';this.style.color='#fff';this.style.borderColor='#0a0a0a';"
                   onmouseout="this.style.background='#f0efeb';this.style.color='#1a1a1a';this.style.borderColor='#e0dfd9';">
                    {{ $emoji }} {{ $cat }}
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection
