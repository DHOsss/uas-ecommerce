<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Outfitku</title>
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

        /* Left hero panel */
        .auth-hero {
            background: linear-gradient(145deg, #0a0a0a 0%, #1a1a2e 60%, #e63946 100%);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 48px 40px; text-align: center; position: relative; overflow: hidden;
        }
        .auth-hero::before {
            content: '';
            position: absolute; inset: 0;
            background: radial-gradient(circle at 70% 30%, rgba(230,57,70,.25) 0%, transparent 60%);
        }
        .auth-hero-logo {
            font-size: 38px; font-weight: 900; color: #fff; letter-spacing: -1px;
            position: relative; margin-bottom: 12px;
        }
        .auth-hero-logo span { color: #e63946; }
        .auth-hero-tagline {
            font-size: 15px; color: rgba(255,255,255,.55); line-height: 1.6; position: relative;
            max-width: 280px;
        }
        .auth-hero-badge {
            margin-top: 40px; position: relative;
            background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.12);
            border-radius: 14px; padding: 20px 28px; width: 100%; max-width: 300px;
        }
        .auth-hero-badge .stat { color: rgba(255,255,255,.5); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .7px; margin-bottom: 4px; }
        .auth-hero-badge .val  { color: #fff; font-size: 28px; font-weight: 900; }

        /* Right form panel */
        .auth-form-panel {
            display: flex; align-items: center; justify-content: center;
            padding: 48px 32px;
        }
        .auth-card {
            width: 100%; max-width: 380px;
        }
        .auth-card-brand {
            font-size: 26px; font-weight: 900; color: #0a0a0a; letter-spacing: -1px;
            margin-bottom: 4px;
        }
        .auth-card-brand span { color: #e63946; }
        .auth-card-sub {
            font-size: 13px; color: #9ca3af; margin-bottom: 32px;
        }

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
            outline: none; border-color: #e63946;
            box-shadow: 0 0 0 3px rgba(230,57,70,.12);
        }

        .form-remember {
            display: flex; align-items: center; gap: 8px;
            font-size: 13px; color: #6b7280; margin-bottom: 22px;
        }

        .btn-auth {
            width: 100%; padding: 12px;
            background: #0a0a0a; color: #fff;
            border: none; border-radius: 10px;
            font-size: 15px; font-weight: 700; cursor: pointer;
            transition: background .2s, transform .1s;
        }
        .btn-auth:hover   { background: #1a1a1a; }
        .btn-auth:active  { transform: scale(.98); }

        .auth-divider {
            display: flex; align-items: center; gap: 12px;
            margin: 20px 0; color: #d1d5db; font-size: 12px;
        }
        .auth-divider::before, .auth-divider::after {
            content: ''; flex: 1; height: 1px; background: #e8e7e3;
        }

        .auth-footer { text-align: center; font-size: 13px; color: #9ca3af; }
        .auth-footer a { color: #e63946; font-weight: 700; text-decoration: none; }
        .auth-footer a:hover { text-decoration: underline; }

        .alert-error {
            background: #fef2f2; border: 1.5px solid #fecaca; border-radius: 10px;
            padding: 10px 14px; font-size: 13px; color: #991b1b; margin-bottom: 18px;
        }
    </style>
</head>
<body>
<div class="auth-page">

    {{-- Left: hero panel --}}
    <div class="auth-hero">
        <div class="auth-hero-logo">Outfit<span>ku</span></div>
        <div class="auth-hero-tagline">
            Temukan koleksi pakaian terbaik untuk gaya hidup kamu sehari-hari.
        </div>
        <div class="auth-hero-badge">
            <div class="stat">Koleksi tersedia</div>
            <div class="val">500+ Style</div>
        </div>
    </div>

    {{-- Right: form panel --}}
    <div class="auth-form-panel">
        <div class="auth-card">
            <div class="auth-card-brand">Outfit<span>ku</span></div>
            <div class="auth-card-sub">Masuk ke akun kamu untuk melanjutkan belanja.</div>

            @if($errors->any())
                <div class="alert-error">&#9888; {{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           placeholder="email@contoh.com" autofocus autocomplete="email">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password"
                           placeholder="••••••••" autocomplete="current-password">
                </div>

                <label class="form-remember">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    Ingat saya
                </label>

                <button type="submit" class="btn-auth">Masuk</button>
            </form>

            <div class="auth-divider">atau</div>

            <div class="auth-footer">
                Belum punya akun?
                <a href="{{ route('register') }}">Daftar gratis</a>
            </div>
        </div>
    </div>

</div>
</body>
</html>
