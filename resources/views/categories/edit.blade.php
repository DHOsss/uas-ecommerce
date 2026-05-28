@extends('layouts.app')

@section('content')
<h1>Edit Kategori</h1>
<div class="card" style="max-width:500px">
    <form action="{{ route('categories.update', $category->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="form-group">
            <label>Nama Kategori</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}">
            @error('name') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="description" rows="3">{{ old('description', $category->description) }}</textarea>
        </div>
        <button type="submit" class="btn btn-success">Perbarui</button>
        <a href="{{ route('categories.index') }}" class="btn btn-primary">Batal</a>
    </form>
</div>
@endsection
