@extends('layouts.app')

@section('content')
<h1>Daftar Customer</h1>
<a href="{{ route('customers.create') }}" class="btn btn-primary" style="margin-bottom:16px">+ Tambah Customer</a>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Telepon</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($customers as $customer)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $customer->name }}</td>
            <td>{{ $customer->email }}</td>
            <td>{{ $customer->phone ?? '-' }}</td>
            <td>
                <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-primary">Detail</a>
                <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning">Edit</a>
                <form class="inline" action="{{ route('customers.destroy', $customer->id) }}" method="POST"
                      onsubmit="return confirm('Hapus customer ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="5" style="text-align:center">Belum ada customer.</td></tr>
        @endforelse
    </tbody>
</table>
<div style="margin-top:12px">{{ $customers->links() }}</div>
@endsection
