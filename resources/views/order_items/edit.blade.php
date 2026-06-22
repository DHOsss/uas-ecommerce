@extends('layouts.app')

@section('content')
<h1>Edit Item Pesanan</h1>
<div class="card card-narrow">
    <form action="{{ route('order-items.update', $orderItem->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="form-group">
            <label>Pesanan</label>
            <select name="order_id">
                @foreach($orders as $order)
                <option value="{{ $order->id }}" {{ $orderItem->order_id == $order->id ? 'selected' : '' }}>
                    #{{ $order->id }} - {{ $order->customer_name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Produk</label>
            <select name="product_id">
                @foreach($products as $product)
                <option value="{{ $product->id }}" {{ $orderItem->product_id == $product->id ? 'selected' : '' }}>
                    {{ $product->name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Jumlah</label>
            <input type="number" name="quantity" value="{{ old('quantity', $orderItem->quantity) }}" min="1">
            @error('quantity') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-success">Perbarui</button>
            <a href="{{ route('order-items.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
