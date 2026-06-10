@extends('layouts.app')

@section('content')
<h1>Edit Kategori</h1>
<div class="card card-narrow">
    <form action="{{ route('categories.update', $category->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="form-group">
            <label>Nama Kategori</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}">
            @error('name') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="description" rows="3">{{ old('description', $category->description) }}</textarea>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-success">Perbarui</button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
