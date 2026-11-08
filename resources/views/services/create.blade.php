@extends('layouts.app')
@section('page-title', 'Tambah Layanan')

@section('content')
<div class="max-w-lg bg-white rounded-xl shadow-sm border border-slate-200 p-6">
    <form method="POST" action="{{ route('services.store') }}">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Nama Layanan</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border border-slate-300 rounded-lg">
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Harga</label>
            <input type="number" step="0.01" name="price" value="{{ old('price') }}" required class="w-full px-3 py-2 border border-slate-300 rounded-lg">
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Satuan</label>
            <select name="unit" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                <option value="kg">Kilogram (kg)</option>
                <option value="item">Item</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Estimasi Pengerjaan</label>
            <input type="text" name="estimation" value="{{ old('estimation') }}" placeholder="mis. 2 hari" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
        </div>
        <div class="flex gap-2">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan</button>
            <a href="{{ route('services.index') }}" class="px-4 py-2 bg-slate-200 rounded-lg">Batal</a>
        </div>
    </form>
</div>
@endsection