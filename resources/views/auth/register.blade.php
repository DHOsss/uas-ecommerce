<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — Outfitku</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body { margin: 0; font-family: 'Segoe UI', system-ui, sans-serif; background: #f5f4f0; }

        .auth-page {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }
        @media (max-width: 768px) {
            .auth-page { grid-template-columns: 1fr; }
            .auth-hero  { display: none; }
        }

        .auth-hero {
            background: linear-gradient(145deg, #0a0a0a 0%, #064e3b 60%, #10b981 100%);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 48px 40px; text-align: center; position: relative; overflow: hidden;
        }
        .auth-hero::before {
            content: '';
            position: absolute; inset: 0;
            background: radial-gradient(circle at 70% 30%, rgba(16,185,129,.25) 0%, transparent 60%);
        }
        .auth-hero-logo {
            font-size: 38px; font-weight: 900; color: #fff; letter-spacing: -1px;
            position: relative; margin-bottom: 12px;
        }
        .auth-hero-logo span { color: #10b981; }
        .auth-hero-tagline {
            font-size: 15px; color: rgba(255,255,255,.55); line-height: 1.6; position: relative;
            max-width: 280px;
        }
        .auth-hero-perks {
            margin-top: 40px; position: relative; width: 100%; max-width: 300px;
            display: flex; flex-direction: column; gap: 12px;
        }
        .perk-item {
            display: flex; align-items: center; gap: 12px;
            background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.12);
            border-radius: 10px; padding: 12px 16px;
            color: #fff; font-size: 13px; font-weight: 600; text-align: left;
        }
        .perk-icon { font-size: 20px; }

        .auth-form-panel {
            display: flex; align-items: center; justify-content: center;
            padding: 48px 32px;
        }
        .auth-card { width: 100%; max-width: 380px; }
        .auth-card-brand {
            font-size: 26px; font-weight: 900; color: #0a0a0a; letter-spacing: -1px;
            margin-bottom: 4px;
        }
        .auth-card-brand span { color: #10b981; }
        .auth-card-sub { font-size: 13px; color: #9ca3af; margin-bottom: 32px; }

        .form-group { margin-bottom: 16px; }
        .form-group label {
            display: block; font-size: 12px; font-weight: 700;
            color: #374151; margin-bottom: 6px; text-transform: uppercase; letter-spacing: .4px;
        }
        .form-group input {
            width: 100%; padding: 11px 14px;
            border: 1.5px solid #e0dfd9; border-radius: 10px;
            font-size: 14px; color: #1a1a1a; background: #fff;
            transition: border-color .15s, box-shadow .15s;
        }
        .form-group input:focus {
            outline: none; border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16,185,129,.12);
        }

        .error { color: #e63946; font-size: 12px; margin-top: 4px; display: block; font-weight: 600; }

        .btn-auth {
            width: 100%; padding: 12px; margin-top: 6px;
            background: #0a0a0a; color: #fff;
            border: none; border-radius: 10px;
            font-size: 15px; font-weight: 700; cursor: pointer;
            transition: background .2s, transform .1s;
            margin-bottom: 14px;
        }
        .btn-auth:hover  { background: #1a1a1a; }
        .btn-auth:active { transform: scale(.98); }

        .auth-divider {
            display: flex; align-items: center; gap: 12px;
            margin: 4px 0 14px; color: #d1d5db; font-size: 12px;
        }
        .auth-divider::before, .auth-divider::after {
            content: ''; flex: 1; height: 1px; background: #e8e7e3;
        }

        .auth-footer { text-align: center; font-size: 13px; color: #9ca3af; }
        .auth-footer a { color: #e63946; font-weight: 700; text-decoration: none; }
        .auth-footer a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="auth-page">

    {{-- Left: hero panel --}}
    <div class="auth-hero">
        <div class="auth-hero-logo">Outfit<span>ku</span></div>
        <div class="auth-hero-tagline">
            Bergabung dan dapatkan akses ke ribuan pilihan outfit terbaik.
        </div>
        <div class="auth-hero-perks">
            <div class="perk-item">
                <span class="perk-icon">🛍️</span>
                Belanja tanpa batas kapan saja
            </div>
            <div class="perk-item">
                <span class="perk-icon">📦</span>
                Lacak pesanan secara real-time
            </div>
            <div class="perk-item">
                <span class="perk-icon">🎟️</span>
                Voucher eksklusif untuk member baru
            </div>
        </div>
    </div>

    {{-- Right: form panel --}}
    <div class="auth-form-panel">
        <div class="auth-card">
            <div class="auth-card-brand">Outfit<span>ku</span></div>
            <div class="auth-card-sub">Buat akun baru dan mulai belanja sekarang.</div>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           placeholder="Nama lengkap kamu" autofocus autocomplete="name">
                    @error('name') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           placeholder="email@contoh.com" autocomplete="email">
                    @error('email') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password"
                           placeholder="Minimal 8 karakter" autocomplete="new-password">
                    @error('password') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="password_confirmation"
                           placeholder="Ulangi password" autocomplete="new-password">
                </div>

                <button type="submit" class="btn-auth">Daftar Sekarang</button>
            </form>

            <div class="auth-divider">sudah punya akun?</div>

            <div class="auth-footer">
                <a href="{{ route('login') }}">Masuk di sini</a>
            </div>
        </div>
    </div>

</div>
</body>
</html>
