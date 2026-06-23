@extends('layouts.app')

@section('content')
<h1>Edit Supplier</h1>
<div class="card card-narrow">
    <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="form-group">
            <label>Nama Supplier</label>
            <input type="text" name="name" value="{{ old('name', $supplier->name) }}">
            @error('name') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $supplier->email) }}">
            @error('email') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label>Telepon</label>
            <input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}">
        </div>
        <div class="form-group">
            <label>Alamat</label>
            <textarea name="address" rows="3">{{ old('address', $supplier->address) }}</textarea>
        </div>
        <div class="form-group">
            <label>Contact Person</label>
            <input type="text" name="contact_person" value="{{ old('contact_person', $supplier->contact_person) }}">
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-success">Perbarui</button>
            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
