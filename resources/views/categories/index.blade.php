@extends('layouts.app')

@section('content')
<h1>Daftar Kategori</h1>
<a href="{{ route('categories.create') }}" class="btn btn-primary" style="margin-bottom:16px">+ Tambah Kategori</a>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Deskripsi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($categories as $category)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $category->name }}</td>
            <td>{{ $category->description ?? '-' }}</td>
            <td>
                <a href="{{ route('categories.show', $category->id) }}" class="btn btn-primary">Detail</a>
                <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning">Edit</a>
                <form class="inline" action="{{ route('categories.destroy', $category->id) }}" method="POST"
                      onsubmit="return confirm('Hapus kategori ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="4" style="text-align:center">Belum ada kategori.</td></tr>
        @endforelse
    </tbody>
</table>
<div style="margin-top:12px">{{ $categories->links() }}</div>
@endsection
