@extends('layouts.app')
@section('page-title', 'Pengeluaran')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200">
        <div class="text-sm text-slate-500">Total Pengeluaran Hari Ini</div>
        <div class="text-2xl font-bold text-red-600 mt-1">Rp {{ number_format($dailyTotal,0,',','.') }}</div>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200">
        <div class="text-sm text-slate-500">Total Pengeluaran Bulan Ini</div>
        <div class="text-2xl font-bold text-red-600 mt-1">Rp {{ number_format($monthlyTotal,0,',','.') }}</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-1 bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h3 class="font-semibold mb-3">Tambah Pengeluaran</h3>
        <form method="POST" action="{{ route('expenses.store') }}">
            @csrf
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Kategori</label>
                <select name="expense_category_id" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                    <option value="">-- Pilih --</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
                <input type="text" name="new_category" placeholder="atau kategori baru" class="w-full mt-2 px-3 py-2 border border-slate-300 rounded-lg text-sm">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Jumlah</label>
                <input type="number" step="0.01" name="amount" required class="w-full px-3 py-2 border border-slate-300 rounded-lg">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Tanggal</label>
                <input type="date" name="date" value="{{ now()->format('Y-m-d') }}" required class="w-full px-3 py-2 border border-slate-300 rounded-lg">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Keterangan</label>
                <textarea name="description" class="w-full px-3 py-2 border border-slate-300 rounded-lg"></textarea>
            </div>
            <button class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan</button>
        </form>
    </div>

    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <form method="GET" class="p-4 flex gap-2 border-b">
            <input type="date" name="from" value="{{ $from }}" class="px-3 py-2 border border-slate-300 rounded-lg text-sm">
            <input type="date" name="to" value="{{ $to }}" class="px-3 py-2 border border-slate-300 rounded-lg text-sm">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">Filter</button>
        </form>
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-600 border-b">
                <tr>
                    <th class="text-left px-4 py-3">Tanggal</th>
                    <th class="text-left px-4 py-3">Kategori</th>
                    <th class="text-left px-4 py-3">Keterangan</th>
                    <th class="text-left px-4 py-3">Jumlah</th>
                    <th class="text-left px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $e)
                    <tr class="border-b border-slate-100">
                        <td class="px-4 py-3">{{ $e->date->format('d M Y') }}</td>
                        <td class="px-4 py-3">{{ $e->category->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $e->description }}</td>
                        <td class="px-4 py-3">Rp {{ number_format($e->amount,0,',','.') }}</td>
                        <td class="px-4 py-3">
                            <form method="POST" action="{{ route('expenses.destroy', $e) }}" onsubmit="return confirm('Hapus?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-6 text-center text-slate-400">Belum ada pengeluaran</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $expenses->links() }}</div>
    </div>
</div>
@endsection