@extends('layouts.app')

@section('content')
<h1>Edit Voucher</h1>
<div class="card card-narrow">
    <form action="{{ route('vouchers.update', $voucher->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="form-group">
            <label>Kode Voucher</label>
            <input type="text" name="code" value="{{ old('code', $voucher->code) }}">
            @error('code') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label>Diskon (%)</label>
            <input type="number" name="discount" value="{{ old('discount', $voucher->discount) }}" min="1" max="100" step="0.01">
            @error('discount') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label>Minimum Pembelian (Rp)</label>
            <input type="number" name="min_purchase" value="{{ old('min_purchase', $voucher->min_purchase) }}" min="0" step="1000">
        </div>
        <div class="form-group">
            <label>Kadaluarsa</label>
            <input type="date" name="expired_at" value="{{ old('expired_at', $voucher->expired_at->format('Y-m-d')) }}">
            @error('expired_at') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="is_active" value="1" {{ $voucher->is_active ? 'checked' : '' }}>
                Aktif
            </label>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-success">Perbarui</button>
            <a href="{{ route('vouchers.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
