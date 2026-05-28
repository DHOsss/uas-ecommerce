@extends('layouts.app')

@section('content')
<h1>Edit Customer</h1>
<div class="card" style="max-width:500px">
    <form action="{{ route('customers.update', $customer->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="name" value="{{ old('name', $customer->name) }}">
            @error('name') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $customer->email) }}">
            @error('email') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label>Telepon</label>
            <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}">
        </div>
        <div class="form-group">
            <label>Alamat</label>
            <textarea name="address" rows="3">{{ old('address', $customer->address) }}</textarea>
        </div>
        <button type="submit" class="btn btn-success">Perbarui</button>
        <a href="{{ route('customers.index') }}" class="btn btn-primary">Batal</a>
    </form>
</div>
@endsection
