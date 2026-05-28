@extends('layouts.app')

@section('content')
<h1>Tambah Supplier</h1>
<div class="card" style="max-width:500px">
    <form action="{{ route('suppliers.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Nama Supplier</label>
            <input type="text" name="name" value="{{ old('name') }}">
            @error('name') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}">
            @error('email') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label>Telepon</label>
            <input type="text" name="phone" value="{{ old('phone') }}">
        </div>
        <div class="form-group">
            <label>Alamat</label>
            <textarea name="address" rows="3">{{ old('address') }}</textarea>
        </div>
        <div class="form-group">
            <label>Contact Person</label>
            <input type="text" name="contact_person" value="{{ old('contact_person') }}">
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('suppliers.index') }}" class="btn btn-primary">Batal</a>
    </form>
</div>
@endsection
