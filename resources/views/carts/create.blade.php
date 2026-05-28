@extends('layouts.app')

@section('content')
<h1>Tambah ke Keranjang</h1>
<div class="card" style="max-width:500px">
    <form action="{{ route('carts.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Customer</label>
            <select name="customer_id">
                <option value="">-- Pilih Customer --</option>
                @foreach($customers as $customer)
                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                    {{ $customer->name }}
                </option>
                @endforeach
            </select>
            @error('customer_id') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label>Produk</label>
            <select name="product_id">
                <option value="">-- Pilih Produk --</option>
                @foreach($products as $product)
                <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                    {{ $product->name }} - Rp {{ number_format($product->price, 0, ',', '.') }}
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
        <button type="submit" class="btn btn-success">Tambah</button>
        <a href="{{ route('carts.index') }}" class="btn btn-primary">Batal</a>
    </form>
</div>
@endsection
