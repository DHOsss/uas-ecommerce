@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1>Daftar Supplier</h1>
    <a href="{{ route('suppliers.create') }}" class="btn btn-primary">+ Tambah Supplier</a>
</div>

<div class="table-wrapper">
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
                    <div class="actions">
                        <a href="{{ route('suppliers.show', $supplier->id) }}" class="btn btn-primary btn-sm">Detail</a>
                        <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form class="inline" action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST"
                              onsubmit="return confirm('Hapus supplier ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center empty-state"><p>Belum ada supplier.</p></td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="pagination-wrap">{{ $suppliers->links() }}</div>
@endsection
