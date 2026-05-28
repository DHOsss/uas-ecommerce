@extends('layouts.app')

@section('content')
<h1>Buat Pesanan</h1>
<div class="card" style="max-width:500px">
    <form action="{{ route('orders.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Nama Pelanggan</label>
            <input type="text" name="customer_name" value="{{ old('customer_name') }}">
            @error('customer_name') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label>Email Pelanggan</label>
            <input type="email" name="customer_email" value="{{ old('customer_email') }}">
            @error('customer_email') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label>Pilih Produk</label>
            <select name="product_id">
                <option value="">-- Pilih Produk --</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                        {{ $product->name }} (Stok: {{ $product->stock }}) - Rp {{ number_format($product->price, 0, ',', '.') }}
                    </option>
                @endforeach
            </select>
            @error('product_id') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label>Jumlah</label>
            <input type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1">
            @error('quantity') <div class="error">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn btn-success">Pesan</button>
        <a href="{{ route('orders.index') }}" class="btn btn-primary">Batal</a>
    </form>
</div>
@endsection
