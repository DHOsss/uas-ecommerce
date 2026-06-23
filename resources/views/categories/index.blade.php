@extends('layouts.app')

@section('title', 'Kategori — OutfitKu')

@section('content')
<div class="page-header">
    <h1>Daftar Kategori</h1>
    <a href="{{ route('categories.create') }}" class="btn btn-primary">+ Tambah Kategori</a>
</div>

<div class="table-wrapper">
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
                    <div class="actions">
                        <a href="{{ route('categories.show', $category->id) }}" class="btn btn-primary btn-sm">Detail</a>
                        <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form class="inline" action="{{ route('categories.destroy', $category->id) }}" method="POST"
                              onsubmit="return confirm('Hapus kategori ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center empty-state"><p>Belum ada kategori.</p></td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="pagination-wrap">{{ $categories->links() }}</div>
@endsection
