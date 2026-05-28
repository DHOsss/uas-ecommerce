@extends('layouts.app')

@section('content')
<h1>Tambah Item Pesanan</h1>
<div class="card" style="max-width:500px">
    <form action="{{ route('order-items.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Pesanan</label>
            <select name="order_id">
                <option value="">-- Pilih Pesanan --</option>
                @foreach($orders as $order)
                <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>
                    #{{ $order->id }} - {{ $order->customer_name }}
                </option>
                @endforeach
            </select>
            @error('order_id') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label>Produk</label>
            <select name="product_id">
                <option value="">-- Pilih Produk --</option>
                @foreach($products as $product)
                <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                    {{ $product->name }} (Stok: {{ $product->stock }})
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
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('order-items.index') }}" class="btn btn-primary">Batal</a>
    </form>
</div>
@endsection
