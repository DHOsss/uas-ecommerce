@extends('layouts.app')

@section('content')
<h1>Daftar Supplier</h1>
<a href="{{ route('suppliers.create') }}" class="btn btn-primary" style="margin-bottom:16px">+ Tambah Supplier</a>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Telepon</th>
            <th>Contact Person</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($suppliers as $supplier)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $supplier->name }}</td>
            <td>{{ $supplier->email }}</td>
            <td>{{ $supplier->phone ?? '-' }}</td>
            <td>{{ $supplier->contact_person ?? '-' }}</td>
            <td>
                <a href="{{ route('suppliers.show', $supplier->id) }}" class="btn btn-primary">Detail</a>
                <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-warning">Edit</a>
                <form class="inline" action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST"
                      onsubmit="return confirm('Hapus supplier ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center">Belum ada supplier.</td></tr>
        @endforelse
    </tbody>
</table>
<div style="margin-top:12px">{{ $suppliers->links() }}</div>
@endsection
