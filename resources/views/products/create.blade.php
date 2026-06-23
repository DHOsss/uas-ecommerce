@extends('layouts.app')

@section('content')
<h1>Tambah Produk</h1>
<div class="card">
    <form action="{{ route('products.store') }}" method="POST">
        @csrf

        @error('products') <div class="alert alert-error">{{ $message }}</div> @enderror

        <table id="product-table" style="width:100%; border-collapse:collapse; margin-bottom:12px;">
            <thead>
                <tr style="background:var(--gray-100);">
                    <th style="padding:10px 8px; text-align:left; font-size:13px;">#</th>
                    <th style="padding:10px 8px; text-align:left; font-size:13px;">Nama Produk <span style="color:var(--danger)">*</span></th>
                    <th style="padding:10px 8px; text-align:left; font-size:13px;">Deskripsi</th>
                    <th style="padding:10px 8px; text-align:left; font-size:13px;">Harga (Rp) <span style="color:var(--danger)">*</span></th>
                    <th style="padding:10px 8px; text-align:left; font-size:13px;">Stok <span style="color:var(--danger)">*</span></th>
                    <th style="padding:10px 8px; text-align:left; font-size:13px;">Kategori</th>
                    <th style="padding:10px 8px;"></th>
                </tr>
            </thead>
            <tbody id="product-rows">
                <tr class="product-row">
                    <td style="padding:8px; color:var(--gray-400); font-size:13px; width:30px;">1</td>
                    <td style="padding:4px 8px;">
                        <input type="text" name="name[]" placeholder="Nama produk" style="width:100%;">
                    </td>
                    <td style="padding:4px 8px;">
                        <input type="text" name="description[]" placeholder="Opsional" style="width:100%;">
                    </td>
                    <td style="padding:4px 8px;">
                        <input type="number" name="price[]" value="0" min="0" step="100" style="width:120px;">
                    </td>
                    <td style="padding:4px 8px;">
                        <input type="number" name="stock[]" value="0" min="0" style="width:80px;">
                    </td>
                    <td style="padding:4px 8px;">
                        <select name="category[]" style="width:100%;">
                            <option value="">-- Pilih --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td style="padding:4px 8px;">
                        <button type="button" class="btn btn-danger btn-remove" style="display:none;">✕</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <div style="margin-bottom:20px;">
            <button type="button" id="btn-add-row" class="btn btn-secondary">+ Tambah Baris</button>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-success">Simpan Semua</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
    const categoryOptions = `@foreach($categories as $cat)<option value="{{ $cat->name }}">{{ $cat->name }}</option>@endforeach`;

    function updateNumbers() {
        document.querySelectorAll('.product-row').forEach((row, i) => {
            row.querySelector('.row-num').textContent = i + 1;
        });
    }

    function updateRemoveButtons() {
        const rows = document.querySelectorAll('.product-row');
        rows.forEach(row => {
            row.querySelector('.btn-remove').style.display = rows.length > 1 ? 'inline-block' : 'none';
        });
    }

    document.getElementById('btn-add-row').addEventListener('click', function () {
        const row = document.createElement('tr');
        row.className = 'product-row';
        row.innerHTML = `
            <td style="padding:8px; color:var(--gray-400); font-size:13px; width:30px;" class="row-num"></td>
            <td style="padding:4px 8px;"><input type="text" name="name[]" placeholder="Nama produk" style="width:100%;"></td>
            <td style="padding:4px 8px;"><input type="text" name="description[]" placeholder="Opsional" style="width:100%;"></td>
            <td style="padding:4px 8px;"><input type="number" name="price[]" value="0" min="0" step="100" style="width:120px;"></td>
            <td style="padding:4px 8px;"><input type="number" name="stock[]" value="0" min="0" style="width:80px;"></td>
            <td style="padding:4px 8px;">
                <select name="category[]" style="width:100%;">
                    <option value="">-- Pilih --</option>
                    ${categoryOptions}
                </select>
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
        document.getElementById('product-rows').appendChild(row);
        updateNumbers();
        updateRemoveButtons();
    });

    // tambah row-num ke baris pertama yang sudah ada
    document.querySelector('.product-row td:first-child').classList.add('row-num');
</script>
@endsection
