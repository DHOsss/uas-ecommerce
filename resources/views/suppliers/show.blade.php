@extends('layouts.app')

@section('content')
<h1>Detail Supplier</h1>
<div class="card" style="max-width:500px">
    <table>
        <tr><th>ID</th><td>{{ $supplier->id }}</td></tr>
        <tr><th>Nama</th><td>{{ $supplier->name }}</td></tr>
        <tr><th>Email</th><td>{{ $supplier->email }}</td></tr>
        <tr><th>Telepon</th><td>{{ $supplier->phone ?? '-' }}</td></tr>
        <tr><th>Alamat</th><td>{{ $supplier->address ?? '-' }}</td></tr>
        <tr><th>Contact Person</th><td>{{ $supplier->contact_person ?? '-' }}</td></tr>
        <tr><th>Dibuat</th><td>{{ $supplier->created_at->format('d/m/Y H:i') }}</td></tr>
    </table>
    <div style="margin-top:12px">
        <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('suppliers.index') }}" class="btn btn-primary">Kembali</a>
    </div>
</div>
@endsection
