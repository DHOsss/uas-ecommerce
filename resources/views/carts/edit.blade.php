@extends('layouts.app')

@section('content')
<h1>Edit Keranjang</h1>
<div class="card card-narrow">
    <form action="{{ route('carts.update', $cart->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="form-group">
            <label>Customer</label>
            <select name="customer_id">
                @foreach($customers as $customer)
                <option value="{{ $customer->id }}" {{ $cart->customer_id == $customer->id ? 'selected' : '' }}>
                    {{ $customer->name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Produk</label>
            <select name="product_id">
                @foreach($products as $product)
                <option value="{{ $product->id }}" {{ $cart->product_id == $product->id ? 'selected' : '' }}>
                    {{ $product->name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Jumlah</label>
            <input type="number" name="quantity" value="{{ old('quantity', $cart->quantity) }}" min="1">
            @error('quantity') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-success">Perbarui</button>
            <a href="{{ route('carts.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
