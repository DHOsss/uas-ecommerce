@extends('layouts.app')

@section('content')
<h1>Tambah ke Keranjang</h1>
<div class="card">
    <form action="{{ route('carts.store') }}" method="POST">
        @csrf

        <div class="form-group" style="max-width:400px; margin-bottom:20px;">
            <label>Customer</label>
            <select name="customer_id">
                <option value="">-- Pilih Customer --</option>
                @foreach($customers as $customer)
                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                    {{ $customer->name }}
                </option>
                @endforeach
            </select>
            @error('customer_id') <span class="error">{{ $message }}</span> @enderror
        </div>

        <table style="width:100%; border-collapse:collapse; margin-bottom:12px;">
            <thead>
                <tr style="background:var(--gray-100);">
                    <th style="padding:10px 8px; text-align:left; font-size:13px;">#</th>
                    <th style="padding:10px 8px; text-align:left; font-size:13px;">Produk <span style="color:var(--danger)">*</span></th>
                    <th style="padding:10px 8px; text-align:left; font-size:13px;">Jumlah <span style="color:var(--danger)">*</span></th>
                    <th style="padding:10px 8px;"></th>
                </tr>
            </thead>
            <tbody id="cart-rows">
                <tr class="cart-row">
                    <td style="padding:8px; color:var(--gray-400); font-size:13px; width:30px;" class="row-num">1</td>
                    <td style="padding:4px 8px;">
                        <select name="product_id[]" style="width:100%;">
                            <option value="">-- Pilih Produk --</option>
                            @foreach($products as $product)
                            <option value="{{ $product->id }}">
                                {{ $product->name }} (Stok: {{ $product->stock }}) - Rp {{ number_format($product->price, 0, ',', '.') }}
                            </option>
                            @endforeach
                        </select>
                    </td>
                    <td style="padding:4px 8px;">
                        <input type="number" name="quantity[]" value="1" min="1" style="width:80px;">
                    </td>
                    <td style="padding:4px 8px;">
                        <button type="button" class="btn btn-danger btn-remove" style="display:none;">✕</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <div style="margin-bottom:20px;">
            <button type="button" id="btn-add-row" class="btn btn-secondary">+ Tambah Produk</button>
        </div>

        @error('product_id') <div class="alert alert-error">{{ $message }}</div> @enderror
        @error('product_id.*') <div class="alert alert-error">{{ $message }}</div> @enderror
        @error('quantity.*') <div class="alert alert-error">{{ $message }}</div> @enderror

        <div class="form-actions">
            <button type="submit" class="btn btn-success">Simpan Semua</button>
            <a href="{{ route('carts.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
    const productOptions = `@foreach($products as $product)<option value="{{ $product->id }}">{{ $product->name }} (Stok: {{ $product->stock }}) - Rp {{ number_format($product->price, 0, ',', '.') }}</option>@endforeach`;

    function updateNumbers() {
        document.querySelectorAll('.cart-row').forEach((row, i) => {
            row.querySelector('.row-num').textContent = i + 1;
        });
    }

    function updateRemoveButtons() {
        const rows = document.querySelectorAll('.cart-row');
        rows.forEach(row => {
            row.querySelector('.btn-remove').style.display = rows.length > 1 ? 'inline-block' : 'none';
        });
    }

    document.getElementById('btn-add-row').addEventListener('click', function () {
        const row = document.createElement('tr');
        row.className = 'cart-row';
        row.innerHTML = `
            <td style="padding:8px; color:var(--gray-400); font-size:13px; width:30px;" class="row-num"></td>
            <td style="padding:4px 8px;">
                <select name="product_id[]" style="width:100%;">
                    <option value="">-- Pilih Produk --</option>
                    ${productOptions}
                </select>
            </td>
            <td style="padding:4px 8px;">
                <input type="number" name="quantity[]" value="1" min="1" style="width:80px;">
            </td>
            <td style="padding:4px 8px;">
                <button type="button" class="btn btn-danger btn-remove">✕</button>
            </td>
        `;
        row.querySelector('.btn-remove').addEventListener('click', function () {
            row.remove();
            updateNumbers();
            updateRemoveButtons();
        });
        document.getElementById('cart-rows').appendChild(row);
        updateNumbers();
        updateRemoveButtons();
    });
</script>
@endsection
