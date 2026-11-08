@extends('layouts.app')
@section('page-title', 'Edit Pelanggan')

@section('content')
<div class="max-w-lg bg-white rounded-xl shadow-sm border border-slate-200 p-6">
    <form method="POST" action="{{ route('customers.update', $customer) }}">
        @csrf @method('PUT')
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Nama</label>
            <input type="text" name="name" value="{{ old('name', $customer->name) }}" required class="w-full px-3 py-2 border border-slate-300 rounded-lg">
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Nomor Telepon</label>
            <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Alamat</label>
            <textarea name="address" class="w-full px-3 py-2 border border-slate-300 rounded-lg">{{ old('address', $customer->address) }}</textarea>
        </div>
        <div class="flex gap-2">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan</button>
            <a href="{{ route('customers.index') }}" class="px-4 py-2 bg-slate-200 rounded-lg">Batal</a>
        </div>
    </form>
</div>
@endsection