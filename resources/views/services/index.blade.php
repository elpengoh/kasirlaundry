@extends('layouts.app')
@section('page-title', 'Layanan Laundry')

@section('content')
<div class="flex items-center justify-between mb-4">
    <div></div>
    <a href="{{ route('services.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">+ Tambah Layanan</a>
</div>
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-600 border-b">
            <tr>
                <th class="text-left px-4 py-3">Nama</th>
                <th class="text-left px-4 py-3">Harga</th>
                <th class="text-left px-4 py-3">Satuan</th>
                <th class="text-left px-4 py-3">Estimasi</th>
                <th class="text-left px-4 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($services as $s)
                <tr class="border-b border-slate-100">
                    <td class="px-4 py-3 font-medium">{{ $s->name }}</td>
                    <td class="px-4 py-3">Rp {{ number_format($s->price,0,',','.') }}</td>
                    <td class="px-4 py-3">{{ $s->unit }}</td>
                    <td class="px-4 py-3">{{ $s->estimation }}</td>
                    <td class="px-4 py-3 flex gap-2">
                        <a href="{{ route('services.edit', $s) }}" class="text-amber-600 hover:underline">Edit</a>
                        <form method="POST" action="{{ route('services.destroy', $s) }}" onsubmit="return confirm('Hapus layanan?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-6 text-center text-slate-400">Belum ada layanan</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $services->links() }}</div>
</div>
@endsection