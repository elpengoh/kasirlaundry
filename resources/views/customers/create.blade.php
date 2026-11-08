@extends('layouts.app')
@section('page-title', 'Tambah Pelanggan')

@section('content')
<div class="max-w-lg bg-white rounded-xl shadow-sm border border-slate-200 p-6">
    <form method="POST" action="{{ route('customers.store') }}">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Nama</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border border-slate-300 rounded-lg">
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Nomor Telepon</label>
            <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Alamat</label>
            <textarea name="address" class="w-full px-3 py-2 border border-slate-300 rounded-lg">{{ old('address') }}</textarea>
        </div>
        <div class="flex gap-2">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan</button>
            <a href="{{ route('customers.index') }}" class="px-4 py-2 bg-slate-200 rounded-lg">Batal</a>
        </div>
    </form>
</div>
@endsection