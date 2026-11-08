@extends('layouts.app')
@section('page-title', 'Manajemen Stok')

@section('content')
@if($lowStock->count())
<div class="mb-4 px-4 py-3 rounded-lg bg-red-50 text-red-700 border border-red-200">
    Peringatan stok minimum: {{ $lowStock->pluck('name')->implode(', ') }}
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-1 space-y-4">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="font-semibold mb-3">Tambah Item Stok</h3>
            <form method="POST" action="{{ route('inventory.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Nama</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Satuan</label>
                    <input type="text" name="unit" value="pcs" required class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Stok Awal</label>
                    <input type="number" step="0.01" name="stock" value="0" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Stok Minimum</label>
                    <input type="number" step="0.01" name="minimum_stock" value="0" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                </div>
                <button class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan</button>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="font-semibold mb-3">Stok Masuk / Keluar</h3>
            <form method="POST" action="{{ route('inventory.movement') }}">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Item</label>
                    <select name="inventory_id" required class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                        @foreach($items as $i)
                            <option value="{{ $i->id }}">{{ $i->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Tipe</label>
                    <select name="type" required class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                        <option value="in">Masuk</option>
                        <option value="out">Keluar</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Jumlah</label>
                    <input type="number" step="0.01" name="quantity" required class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Tanggal</label>
                    <input type="date" name="date" value="{{ now()->format('Y-m-d') }}" required class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Keterangan</label>
                    <input type="text" name="description" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                </div>
                <button class="w-full px-4 py-2 bg-green-600 text-white rounded-lg">Catat</button>
            </form>
        </div>
    </div>

    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-600 border-b">
                <tr>
                    <th class="text-left px-4 py-3">Nama</th>
                    <th class="text-left px-4 py-3">Satuan</th>
                    <th class="text-left px-4 py-3">Stok</th>
                    <th class="text-left px-4 py-3">Minimum</th>
                    <th class="text-left px-4 py-3">Status</th>
                    <th class="text-left px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $i)
                    <tr class="border-b border-slate-100">
                        <td class="px-4 py-3 font-medium">{{ $i->name }}</td>
                        <td class="px-4 py-3">{{ $i->unit }}</td>
                        <td class="px-4 py-3">{{ $i->stock }}</td>
                        <td class="px-4 py-3">{{ $i->minimum_stock }}</td>
                        <td class="px-4 py-3">
                            @if((float)$i->stock <= (float)$i->minimum_stock && (float)$i->minimum_stock > 0)
                                <span class="text-red-600 font-semibold">Rendah</span>
                            @else
                                <span class="text-green-600">Aman</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <form method="POST" action="{{ route('inventory.destroy', $i) }}" onsubmit="return confirm('Hapus?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-slate-400">Belum ada stok</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection