@extends('layouts.app')

@section('content')
<h1>Edit Customer</h1>
<div class="card card-narrow">
    <form action="{{ route('customers.update', $customer->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="name" value="{{ old('name', $customer->name) }}">
            @error('name') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $customer->email) }}">
            @error('email') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label>Telepon</label>
            <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}">
        </div>
        <div class="form-group">
            <label>Alamat</label>
            <textarea name="address" rows="3">{{ old('address', $customer->address) }}</textarea>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-success">Perbarui</button>
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
