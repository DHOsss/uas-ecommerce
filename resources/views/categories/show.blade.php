@extends('layouts.app')

@section('content')
<h1>Detail Kategori</h1>
<div class="card" style="max-width:500px">
    <table>
        <tr><th>ID</th><td>{{ $category->id }}</td></tr>
        <tr><th>Nama</th><td>{{ $category->name }}</td></tr>
        <tr><th>Deskripsi</th><td>{{ $category->description ?? '-' }}</td></tr>
        <tr><th>Dibuat</th><td>{{ $category->created_at->format('d/m/Y H:i') }}</td></tr>
    </table>
    <div style="margin-top:12px">
        <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('categories.index') }}" class="btn btn-primary">Kembali</a>
    </div>
</div>
@endsection
