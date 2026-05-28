<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UAS E-Commerce</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f5f5f5; }
        nav { background: #333; padding: 12px 24px; display: flex; gap: 20px; }
        nav a { color: white; text-decoration: none; font-weight: bold; }
        nav a:hover { color: #aaa; }
        .container { max-width: 960px; margin: 24px auto; padding: 0 16px; }
        .alert-success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 10px 16px; margin-bottom: 16px; border-radius: 4px; }
        .alert-error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 10px 16px; margin-bottom: 16px; border-radius: 4px; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { padding: 10px 14px; border: 1px solid #ddd; text-align: left; }
        th { background: #555; color: white; }
        tr:nth-child(even) { background: #f9f9f9; }
        .btn { display: inline-block; padding: 7px 14px; border-radius: 4px; text-decoration: none; font-size: 14px; cursor: pointer; border: none; }
        .btn-primary { background: #007bff; color: white; }
        .btn-warning { background: #ffc107; color: black; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-success { background: #28a745; color: white; }
        form.inline { display: inline; }
        .card { background: white; padding: 20px; border-radius: 6px; box-shadow: 0 1px 4px rgba(0,0,0,.1); }
        .form-group { margin-bottom: 14px; }
        label { display: block; margin-bottom: 4px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .error { color: red; font-size: 13px; margin-top: 4px; }
        h1 { margin-top: 0; }
        .badge { padding: 3px 8px; border-radius: 12px; font-size: 12px; font-weight: bold; }
        .badge-pending { background: #ffc107; color: black; }
        .badge-confirmed { background: #28a745; color: white; }
        .badge-cancelled { background: #dc3545; color: white; }
    </style>
</head>
<body>
<nav>
    <a href="{{ route('products.index') }}">Produk</a>
    <a href="{{ route('categories.index') }}">Kategori</a>
    <a href="{{ route('orders.index') }}">Pesanan</a>
    <a href="{{ route('order-items.index') }}">Item Pesanan</a>
    <a href="{{ route('customers.index') }}">Customer</a>
    <a href="{{ route('carts.index') }}">Keranjang</a>
    <a href="{{ route('payments.index') }}">Pembayaran</a>
    <a href="{{ route('vouchers.index') }}">Voucher</a>
    <a href="{{ route('reviews.index') }}">Ulasan</a>
    <a href="{{ route('suppliers.index') }}">Supplier</a>
</nav>
<div class="container">
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif
    @yield('content')
</div>
</body>
</html>
