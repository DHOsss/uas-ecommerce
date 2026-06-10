@extends('layouts.app')

@section('content')
<h1>Buat Pesanan</h1>
<form action="{{ auth()->user()->isAdmin() ? route('orders.store') : route('customer.orders.store') }}" method="POST">
    @csrf
    <div style="display:flex; gap:20px; align-items:flex-start;">

        {{-- Kolom Kiri: Pilih Pelanggan + Tabel Produk --}}
        <div class="card" style="flex:1;">
            @if(auth()->user()->isAdmin())
            <div class="form-group" style="max-width:420px; margin-bottom:20px;">
                <label>Pelanggan</label>
                <select name="customer_id">
                    <option value="">-- Pilih Pelanggan --</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }} — {{ $customer->email }}
                        </option>
                    @endforeach
                </select>
                @error('customer_id') <span class="error">{{ $message }}</span> @enderror
            </div>
            @else
            <input type="hidden" name="customer_email" value="{{ auth()->user()->email }}">
            <div style="background:var(--gray-100); border-radius:var(--radius-sm); padding:10px 14px; margin-bottom:20px; font-size:14px; max-width:420px;">
                Pesan sebagai: <strong>{{ auth()->user()->name }}</strong>
            </div>
            @endif

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
                <tbody id="order-rows">
                    <tr class="order-row">
                        <td style="padding:8px; color:var(--gray-400); font-size:13px; width:30px;" class="row-num">1</td>
                        <td style="padding:4px 8px;">
                            <select name="product_id[]" class="product-select" style="width:100%;">
                                <option value="">-- Pilih Produk --</option>
                                @foreach($products as $product)
                                <option value="{{ $product->id }}" data-price="{{ $product->price }}"
                                        data-sizes="{{ json_encode($product->sizes ?? []) }}">
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
                            <input type="number" name="quantity[]" value="1" min="1" class="qty-input" style="width:80px;">
                        </td>
                        <td style="padding:4px 8px;">
                            <button type="button" class="btn btn-danger btn-remove" style="display:none;">✕</button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <button type="button" id="btn-add-row" class="btn btn-secondary">+ Tambah Produk</button>

            @error('product_id') <div class="alert alert-error" style="margin-top:10px;">{{ $message }}</div> @enderror
            @error('product_id.*') <div class="alert alert-error" style="margin-top:10px;">{{ $message }}</div> @enderror
        </div>

        {{-- Kolom Kanan: Ringkasan + Voucher --}}
        <div class="card" style="width:280px; min-width:260px;">
            <h3 style="font-size:15px; font-weight:700; margin-bottom:16px;">Ringkasan Pesanan</h3>

            <div style="display:flex; justify-content:space-between; margin-bottom:8px; font-size:14px;">
                <span style="color:var(--gray-600);">Subtotal</span>
                <span id="summary-subtotal">Rp 0</span>
            </div>

            <hr style="border:none; border-top:1px solid var(--gray-200); margin:12px 0;">

            <label style="font-size:13px; font-weight:600; display:block; margin-bottom:6px;">Kode Voucher</label>
            <div style="display:flex; gap:6px; margin-bottom:8px;">
                <input type="text" id="voucher-input" placeholder="Masukkan kode" style="flex:1; text-transform:uppercase;">
                <button type="button" id="btn-apply-voucher" class="btn btn-secondary" style="white-space:nowrap;">Terapkan</button>
            </div>
            <div id="voucher-msg" style="font-size:12px; min-height:18px;"></div>
            <input type="hidden" name="voucher_code" id="voucher-code-hidden">

            <div id="discount-row" style="display:none; justify-content:space-between; margin-top:10px; font-size:14px; color:var(--success);">
                <span>Diskon (<span id="discount-pct"></span>%)</span>
                <span id="summary-discount">- Rp 0</span>
            </div>

            <hr style="border:none; border-top:2px solid var(--gray-200); margin:12px 0;">

            <div style="display:flex; justify-content:space-between; font-size:15px; font-weight:700;">
                <span>Total</span>
                <span id="summary-total" style="color:var(--primary);">Rp 0</span>
            </div>

            <div class="form-actions" style="margin-top:20px; flex-direction:column; gap:8px;">
                <button type="submit" class="btn btn-success" style="width:100%;">Pesan</button>
                <a href="{{ route('orders.index') }}" class="btn btn-secondary" style="width:100%; text-align:center;">Batal</a>
            </div>
        </div>

    </div>
</form>

<script>
    const productOptions = `@foreach($products as $product)<option value="{{ $product->id }}" data-price="{{ $product->price }}" data-sizes='{{ json_encode($product->sizes ?? []) }}'>{{ $product->name }} (Stok: {{ $product->stock }}) - Rp {{ number_format($product->price, 0, ',', '.') }}</option>@endforeach`;

    function populateSizes(productSelect) {
        const opt      = productSelect.options[productSelect.selectedIndex];
        const sizes    = JSON.parse(opt?.dataset?.sizes || '[]');
        const sizeSelect = productSelect.closest('tr').querySelector('.size-select');
        sizeSelect.innerHTML = '<option value="">-- Pilih --</option>';
        sizes.forEach(s => {
            const o = document.createElement('option');
            o.value = s; o.textContent = s;
            sizeSelect.appendChild(o);
        });
        if (sizes.length === 1) sizeSelect.value = sizes[0];
    }

    document.getElementById('order-rows').addEventListener('change', function(e) {
        if (e.target.classList.contains('product-select')) populateSizes(e.target);
        recalculate();
    });
    const voucherCheckUrl = "{{ route('voucher.check') }}";

    let discountPct = 0;
    let minPurchase = 0;

    function formatRp(num) {
        return 'Rp ' + Math.round(num).toLocaleString('id-ID');
    }

    function recalculate() {
        let subtotal = 0;
        document.querySelectorAll('.order-row').forEach(row => {
            const sel = row.querySelector('.product-select');
            const qty = parseInt(row.querySelector('.qty-input').value) || 0;
            const opt = sel.options[sel.selectedIndex];
            const price = parseFloat(opt?.dataset?.price || 0);
            subtotal += price * qty;
        });

        document.getElementById('summary-subtotal').textContent = formatRp(subtotal);

        let discount = 0;
        const discountRow = document.getElementById('discount-row');
        if (discountPct > 0 && subtotal >= minPurchase) {
            discount = subtotal * discountPct / 100;
            discountRow.style.display = 'flex';
            document.getElementById('summary-discount').textContent = '- ' + formatRp(discount);
        } else {
            discountRow.style.display = 'none';
        }

        document.getElementById('summary-total').textContent = formatRp(subtotal - discount);
    }

    document.getElementById('order-rows').addEventListener('input', recalculate);

    // Tambah baris
    function updateNumbers() {
        document.querySelectorAll('.order-row').forEach((row, i) => {
            row.querySelector('.row-num').textContent = i + 1;
        });
    }

    function updateRemoveButtons() {
        const rows = document.querySelectorAll('.order-row');
        rows.forEach(row => {
            row.querySelector('.btn-remove').style.display = rows.length > 1 ? 'inline-block' : 'none';
        });
    }

    document.getElementById('btn-add-row').addEventListener('click', function () {
        const row = document.createElement('tr');
        row.className = 'order-row';
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
                <input type="number" name="quantity[]" value="1" min="1" class="qty-input" style="width:80px;">
            </td>
            <td style="padding:4px 8px;">
                <button type="button" class="btn btn-danger btn-remove">✕</button>
            </td>
        `;
        row.querySelector('.btn-remove').addEventListener('click', function () {
            row.remove();
            updateNumbers();
            updateRemoveButtons();
            recalculate();
        });
        document.getElementById('order-rows').appendChild(row);
        updateNumbers();
        updateRemoveButtons();
    });

    // Cek voucher
    document.getElementById('btn-apply-voucher').addEventListener('click', function () {
        const code = document.getElementById('voucher-input').value.trim().toUpperCase();
        const msg  = document.getElementById('voucher-msg');

        if (!code) {
            msg.style.color = 'var(--danger)';
            msg.textContent = 'Masukkan kode voucher terlebih dahulu.';
            return;
        }

        fetch(`${voucherCheckUrl}?code=${code}`)
            .then(r => r.json())
            .then(data => {
                if (data.valid) {
                    discountPct = data.discount;
                    minPurchase = data.min_purchase;
                    document.getElementById('discount-pct').textContent = data.discount;
                    document.getElementById('voucher-code-hidden').value = code;
                    msg.style.color = 'var(--success)';
                    msg.textContent = data.message;
                } else {
                    discountPct = 0;
                    minPurchase = 0;
                    document.getElementById('voucher-code-hidden').value = '';
                    msg.style.color = 'var(--danger)';
                    msg.textContent = data.message;
                }
                recalculate();
            });
    });

    // Reset voucher jika kode dihapus manual
    document.getElementById('voucher-input').addEventListener('input', function () {
        if (!this.value.trim()) {
            discountPct = 0;
            minPurchase = 0;
            document.getElementById('voucher-code-hidden').value = '';
            document.getElementById('voucher-msg').textContent = '';
            recalculate();
        }
    });
</script>
@endsection
