<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'OutfitKu')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<nav class="navbar">
    <a href="{{ url('/') }}" class="navbar-brand">Outfit<span>ku</span></a>

    @auth
        <div class="navbar-links">
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a href="{{ route('products.index') }}">Produk</a>
                <a href="{{ route('orders.index') }}">Pesanan</a>
                <a href="{{ route('payments.index') }}">Pembayaran</a>
                <a href="{{ route('customers.index') }}">Customer</a>
                <a href="{{ route('reviews.index') }}">Ulasan</a>
                <a href="{{ route('categories.index') }}">Kategori</a>
                <a href="{{ route('vouchers.index') }}">Voucher</a>
                <a href="{{ route('suppliers.index') }}">Supplier</a>
            @else
                <a href="{{ route('products.index') }}">Produk</a>
                <a href="{{ route('customer.orders') }}">Pesanan Saya</a>
                <a href="{{ route('customer.carts') }}">Keranjang</a>
                <a href="{{ route('customer.payments') }}">Pembayaran</a>
                <a href="{{ route('customer.reviews') }}">Ulasan</a>
            @endif
        </div>

        @php
            $initials = collect(explode(' ', auth()->user()->name))
                ->take(2)->map(fn($w) => strtoupper($w[0]))->implode('');
            $isAdmin  = auth()->user()->isAdmin();
            $color    = $isAdmin ? '#e63946' : '#2a9d6a';
        @endphp

        <div class="nav-profile" id="navProfile">
            <button class="nav-profile-trigger" id="navProfileBtn" type="button">
                <div class="nav-avatar" style="background:{{ $color }}">{{ $initials }}</div>
                <div class="nav-profile-info">
                    <span class="nav-profile-name">{{ auth()->user()->name }}</span>
                    <span class="nav-profile-role" style="background:{{ $color }}">
                        {{ $isAdmin ? 'Admin' : 'Customer' }}
                    </span>
                </div>
                <span class="nav-chevron">▾</span>
            </button>

            <div class="nav-dropdown" id="navDropdown">
                <div class="nav-dropdown-header">
                    <div class="nav-avatar nav-avatar-lg" style="background:{{ $color }}">{{ $initials }}</div>
                    <div>
                        <div style="font-weight:700; color:#111; font-size:14px;">{{ auth()->user()->name }}</div>
                        <div style="font-size:12px; color:#9ca3af; margin-top:2px;">{{ auth()->user()->email }}</div>
                        <span class="nav-profile-role" style="background:{{ $color }}; margin-top:6px; display:inline-block;">
                            {{ $isAdmin ? 'Admin' : 'Customer' }}
                        </span>
                    </div>
                </div>
                <div class="nav-dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-dropdown-item">
                        <span>🔄</span> Ganti Akun
                    </button>
                </form>
                <div class="nav-dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-dropdown-item nav-dropdown-logout">
                        <span>⏻</span> Keluar
                    </button>
                </form>
            </div>
        </div>
    @endauth
</nav>

<div class="container">
    @if(session('success'))
        <div class="alert alert-success">&#10003; {{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-error">&#9888; {{ $errors->first() }}</div>
    @endif
    @yield('content')
</div>

<script>
    var btn      = document.getElementById('navProfileBtn');
    var profile  = document.getElementById('navProfile');
    var dropdown = document.getElementById('navDropdown');

    if (btn) {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            var isOpen = profile.classList.toggle('open');
            btn.setAttribute('aria-expanded', isOpen);
        });

        document.addEventListener('click', function (e) {
            if (!profile.contains(e.target)) {
                profile.classList.remove('open');
                btn.setAttribute('aria-expanded', 'false');
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                profile.classList.remove('open');
                btn.setAttribute('aria-expanded', 'false');
            }
        });
    }
</script>
</body>
</html>
