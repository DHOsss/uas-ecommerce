@extends('layouts.app')

@section('content')
<h1>Bayar Pesanan</h1>
<div class="card card-narrow">
    @if($orders->isEmpty())
        <p>Tidak ada pesanan yang perlu dibayar.</p>
        <a href="{{ route('customer.orders') }}" class="btn btn-secondary">Lihat Pesanan</a>
    @else
    <form action="{{ route('customer.payments.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Pilih Pesanan</label>
            <select name="order_id" id="order_select">
                <option value="">-- Pilih Pesanan --</option>
                @foreach($orders as $order)
                <option value="{{ $order->id }}"
                        data-total="{{ $order->total_price }}"
                        {{ old('order_id') == $order->id ? 'selected' : '' }}>
                    #{{ $order->id }} — Rp {{ number_format($order->total_price, 0, ',', '.') }} ({{ ucfirst($order->status) }})
                </option>
                @endforeach
            </select>
            @error('order_id') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Total yang Harus Dibayar</label>
            <input type="text" id="amount_display" readonly placeholder="Pilih pesanan dulu" style="background:var(--gray-100)">
        </div>

        <div class="form-group">
            <label>Metode Pembayaran</label>
            <select name="method">
                <option value="transfer" {{ old('method') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                <option value="cash"     {{ old('method') == 'cash'     ? 'selected' : '' }}>Cash</option>
                <option value="ewallet"  {{ old('method') == 'ewallet'  ? 'selected' : '' }}>E-Wallet</option>
            </select>
            @error('method') <span class="error">{{ $message }}</span> @enderror
        </div>

        <p style="color:var(--gray-500); font-size:0.875rem;">
            Pembayaran akan diverifikasi oleh admin. Status awal: <strong>Menunggu Konfirmasi</strong>.
        </p>

        <div class="form-actions">
            <button type="submit" class="btn btn-success">Ajukan Pembayaran</button>
            <a href="{{ route('customer.payments') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
    @endif
</div>

<script>
    document.getElementById('order_select').addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        const total = selected.dataset.total;
        const display = document.getElementById('amount_display');
        if (total) {
            display.value = 'Rp ' + parseFloat(total).toLocaleString('id-ID');
        } else {
            display.value = '';
        }
    });
</script>
@endsection
