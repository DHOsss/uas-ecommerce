@extends('layouts.app')

@section('title', 'Data Customer — OutfitKu')

@section('content')
<div class="page-header">
    <h1>Data Customer</h1>
    <a href="{{ route('customers.create') }}" class="btn btn-primary">+ Tambah Customer</a>
</div>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Kontak</th>
                <th>Alamat</th>
                <th>Bergabung</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customers as $customer)
            @php
                $words    = explode(' ', trim($customer->name));
                $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                $colors   = ['#e63946','#3b82f6','#8b5cf6','#10b981','#f59e0b','#0ea5e9'];
                $color    = $colors[$customer->id % count($colors)];
            @endphp
            <tr>
                <td style="color:#9ca3af; font-size:12px;">#{{ str_pad($customer->id, 3, '0', STR_PAD_LEFT) }}</td>
                <td>
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div style="width:36px; height:36px; border-radius:50%; background:{{ $color }};
                                    display:flex; align-items:center; justify-content:center;
                                    font-size:13px; font-weight:800; color:#fff; flex-shrink:0;">
                            {{ $initials }}
                        </div>
                        <div>
                            <div style="font-weight:700; font-size:13px; color:#1a1a1a;">{{ $customer->name }}</div>
                            <div style="font-size:11px; color:#9ca3af;">{{ $customer->email }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    @if($customer->phone)
                        <span style="font-size:13px;">📞 {{ $customer->phone }}</span>
                    @else
                        <span style="font-size:12px; color:#d1d5db;">—</span>
                    @endif
                </td>
                <td style="font-size:12px; color:#6b7280; max-width:180px;">
                    {{ $customer->address ? Str::limit($customer->address, 40) : '—' }}
                </td>
                <td style="font-size:12px; color:#9ca3af;">
                    {{ $customer->created_at->format('d M Y') }}
                </td>
                <td>
                    <div class="actions">
                        <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-primary btn-sm">Detail</a>
                        <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form class="inline" action="{{ route('customers.destroy', $customer->id) }}" method="POST"
                              onsubmit="return confirm('Hapus customer {{ addslashes($customer->name) }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center empty-state"><p>Belum ada customer terdaftar.</p></td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="pagination-wrap">{{ $customers->links() }}</div>
@endsection
