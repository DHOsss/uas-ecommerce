@extends('layouts.app')

@section('content')
<h1>Detail Customer</h1>
<div class="card card-narrow">
    <table>
        <tr><th>ID</th><td>{{ $customer->id }}</td></tr>
        <tr><th>Nama</th><td>{{ $customer->name }}</td></tr>
        <tr><th>Email</th><td>{{ $customer->email }}</td></tr>
        <tr><th>Telepon</th><td>{{ $customer->phone ?? '-' }}</td></tr>
        <tr><th>Alamat</th><td>{{ $customer->address ?? '-' }}</td></tr>
        <tr><th>Dibuat</th><td>{{ $customer->created_at->format('d/m/Y H:i') }}</td></tr>
    </table>
    <div class="form-actions">
        <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('customers.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
@endsection
