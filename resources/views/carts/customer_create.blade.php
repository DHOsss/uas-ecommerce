@extends('layouts.app')

@section('content')
<h1>Tambah ke Keranjang</h1>
<div class="card">
    <div style="background:var(--gray-100); border-radius:var(--radius-sm); padding:10px 14px; margin-bottom:20px; font-size:14px; max-width:500px;">
        Keranjang milik: <strong>{{ auth()->user()->name }}</strong>
    </div>

    {{-- Isi dari pesanan lama --}}
    @if($orders->count())
    <div style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:var(--radius-sm); padding:14px 16px; margin-bottom:20px; max-width:500px;">
        <label style="font-size:13px; font-weight:600; display:block; margin-bottom:8px; color:#1d4ed8;">
            Isi dari Pesanan Lama (opsional)
        </label>
        <div style="display:flex; gap:8px;">
            <select id="order-history-select" style="flex:1;">
                <option value="">-- Pilih pesanan --</option>
                @foreach($orders as $order)
                <option value="{{ $order->id }}"
                    data-items="{{ json_encode($order->orderItems->map(fn($i) => ['product_id' => $i->product_id, 'name' => $i->product->name ?? '-', 'qty' => $i->quantity])) }}">
                    Pesanan #{{ $order->id }} — {{ $order->created_at->format('d/m/Y') }}
                    ({{ $order->orderItems->count() }} produk)
                </option>
                @endforeach
            </select>
            <button type="button" id="btn-fill-from-order" class="btn btn-primary">Isi Otomatis</button>
        </div>
        <p style="font-size:12px; color:#3b82f6; margin-top:6px; margin-bottom:0;">
            Pilih pesanan lama lalu klik "Isi Otomatis" untuk mengisi produk dari pesanan tersebut.
        </p>
    </div>
    @endif

    <form action="{{ route('customer.carts.store') }}" method="POST">
        @csrf

        <table style="width:100%; border-collapse:collapse; margin-bottom:12px;">
            <thead>
                <tr style="background:var(--gray-100);">
                    <th style="padding:10px 8px; text-align:left; font-size:13px;">#</th>
                    <th style="padding:10px 8px; text-align:left; font-size:13px;">Produk <span style="color:var(--danger)">*</span></th>
                    <th style="padding:10px 8px; text-align:left; font-size:13px;">Ukuran <span style="color:var(--danger)">*</span></th>
                    <th style="padding:10px 8px; text-align:left; font-size:13px;">Jumlah <span style="color:var(--danger)">*</span></th>
                    <th style="padding:10px 8px;"></th>
                </tr>
            </thead>
            <tbody id="cart-rows">
                <tr class="cart-row">
                    <td style="padding:8px; color:var(--gray-400); font-size:13px; width:30px;" class="row-num">1</td>
                    <td style="padding:4px 8px;">
                        <select name="product_id[]" class="product-select" style="width:100%;">
                            <option value="">-- Pilih Produk --</option>
                            @foreach($products as $product)
                            <option value="{{ $product->id }}" data-sizes='{{ json_encode($product->sizes ?? []) }}'>
                                {{ $product->name }} (Stok: {{ $product->stock }}) - Rp {{ number_format($product->price, 0, ',', '.') }}
                            </option>
                            @endforeach
                        </select>
                    </td>
                    <td style="padding:4px 8px;">
                        <select name="size[]" class="size-select" style="width:110px;">
                            <option value="">-- Pilih --</option>
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

        @error('product_id') <div class="alert alert-error">{{ $message }}</div> @enderror

        <div style="margin-bottom:20px;">
            <button type="button" id="btn-add-row" class="btn btn-secondary">+ Tambah Produk</button>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-success">Simpan ke Keranjang</button>
            <a href="{{ route('customer.carts') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
    const productOptions = `@foreach($products as $product)<option value="{{ $product->id }}" data-sizes='{{ json_encode($product->sizes ?? []) }}'>{{ $product->name }} (Stok: {{ $product->stock }}) - Rp {{ number_format($product->price, 0, ',', '.') }}</option>@endforeach`;

    function populateSizes(productSelect) {
        const opt        = productSelect.options[productSelect.selectedIndex];
        const sizes      = JSON.parse(opt?.dataset?.sizes || '[]');
        const sizeSelect = productSelect.closest('tr').querySelector('.size-select');
        sizeSelect.innerHTML = '<option value="">-- Pilih --</option>';
        sizes.forEach(s => {
            const o = document.createElement('option');
            o.value = s; o.textContent = s;
            sizeSelect.appendChild(o);
        });
        if (sizes.length === 1) sizeSelect.value = sizes[0];
    }

    document.getElementById('cart-rows').addEventListener('change', function(e) {
        if (e.target.classList.contains('product-select')) populateSizes(e.target);
    });

    function makeRow(productId, qty) {
        const row = document.createElement('tr');
        row.className = 'cart-row';
        row.innerHTML = `
            <td style="padding:8px; color:var(--gray-400); font-size:13px; width:30px;" class="row-num"></td>
            <td style="padding:4px 8px;">
                <select name="product_id[]" class="product-select" style="width:100%;">
                    <option value="">-- Pilih Produk --</option>
                    ${productOptions}
                </select>
            </td>
            <td style="padding:4px 8px;">
                <select name="size[]" class="size-select" style="width:110px;">
                    <option value="">-- Pilih --</option>
                </select>
            </td>
            <td style="padding:4px 8px;">
                <input type="number" name="quantity[]" value="${qty || 1}" min="1" style="width:80px;">
            </td>
            <td style="padding:4px 8px;">
                <button type="button" class="btn btn-danger btn-remove">✕</button>
            </td>
        `;
        if (productId) {
            const sel = row.querySelector('select');
            [...sel.options].forEach(o => { if (o.value == productId) o.selected = true; });
        }
        row.querySelector('.btn-remove').addEventListener('click', function () {
            row.remove();
            updateNumbers();
            updateRemoveButtons();
        });
        return row;
    }

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

    // Isi otomatis dari pesanan lama
    const btnFill = document.getElementById('btn-fill-from-order');
    if (btnFill) {
        btnFill.addEventListener('click', function () {
            const sel   = document.getElementById('order-history-select');
            const opt   = sel.options[sel.selectedIndex];
            if (!opt.value) { alert('Pilih pesanan terlebih dahulu.'); return; }

            const items = JSON.parse(opt.dataset.items || '[]');
            if (!items.length) { alert('Pesanan ini tidak memiliki item.'); return; }

            // Kosongkan baris yang ada
            document.getElementById('cart-rows').innerHTML = '';

            // Isi ulang dari item pesanan
            items.forEach(item => {
                document.getElementById('cart-rows').appendChild(makeRow(item.product_id, item.qty));
            });

            updateNumbers();
            updateRemoveButtons();
        });
    }

    // Tambah baris manual
    document.getElementById('btn-add-row').addEventListener('click', function () {
        document.getElementById('cart-rows').appendChild(makeRow(null, 1));
        updateNumbers();
        updateRemoveButtons();
    });
</script>
@endsection
