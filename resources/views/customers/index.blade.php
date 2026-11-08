@extends('layouts.app')
@section('page-title', 'Data Pelanggan')

@section('content')
<div class="flex items-center justify-between mb-4">
    <form method="GET" class="flex gap-2">
        <input type="text" name="q" value="{{ $q }}" placeholder="Cari pelanggan..." class="px-3 py-2 border border-slate-300 rounded-lg text-sm w-64">
        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">Cari</button>
    </form>
    <a href="{{ route('customers.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">+ Tambah Pelanggan</a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-600 border-b">
            <tr>
                <th class="text-left px-4 py-3">Nama</th>
                <th class="text-left px-4 py-3">Telepon</th>
                <th class="text-left px-4 py-3">Alamat</th>
                <th class="text-left px-4 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customers as $c)
                <tr class="border-b border-slate-100">
                    <td class="px-4 py-3 font-medium">{{ $c->name }}</td>
                    <td class="px-4 py-3">{{ $c->phone }}</td>
                    <td class="px-4 py-3">{{ $c->address }}</td>
                    <td class="px-4 py-3 flex gap-2">
                        <a href="{{ route('customers.show', $c) }}" class="text-blue-600 hover:underline">Detail</a>
                        <a href="{{ route('customers.edit', $c) }}" class="text-amber-600 hover:underline">Edit</a>
                        <form method="POST" action="{{ route('customers.destroy', $c) }}" onsubmit="return confirm('Hapus pelanggan?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-4 py-6 text-center text-slate-400">Belum ada pelanggan</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $customers->links() }}</div>
</div>
@endsection