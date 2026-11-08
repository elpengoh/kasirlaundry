@extends('layouts.app')
@section('page-title', 'Pengaturan')

@section('content')
<form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data" class="space-y-6">
    @csrf @method('PUT')

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h3 class="font-semibold mb-3">Informasi Usaha</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Nama Laundry</label>
                <input type="text" name="laundry_name" value="{{ old('laundry_name', $settings['laundry_name'] ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Nomor Telepon</label>
                <input type="text" name="phone" value="{{ old('phone', $settings['phone'] ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">Alamat</label>
                <textarea name="address" class="w-full px-3 py-2 border border-slate-300 rounded-lg">{{ old('address', $settings['address'] ?? '') }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">Logo</label>
                @if(!empty($settings['logo']))
                    <img src="{{ asset('storage/'.$settings['logo']) }}" class="w-16 h-16 object-contain mb-2">
                @endif
                <input type="file" name="logo" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">Informasi Nota</label>
                <textarea name="nota_info" class="w-full px-3 py-2 border border-slate-300 rounded-lg">{{ old('nota_info', $settings['nota_info'] ?? '') }}</textarea>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h3 class="font-semibold mb-3">Harga & Estimasi Layanan</h3>
        <table class="w-full text-sm">
            <thead class="text-slate-500 border-b">
                <tr>
                    <th class="text-left py-2">Layanan</th>
                    <th class="text-left py-2">Harga</th>
                    <th class="text-left py-2">Estimasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($services as $s)
                    <tr class="border-b border-slate-100">
                        <td class="py-2">{{ $s->name }}</td>
                        <td class="py-2">
                            <input type="number" step="0.01" name="services[{{ $s->id }}][price]" value="{{ $s->price }}" class="w-full px-2 py-1 border border-slate-300 rounded">
                        </td>
                        <td class="py-2">
                            <input type="text" name="services[{{ $s->id }}][estimation]" value="{{ $s->estimation }}" class="w-full px-2 py-1 border border-slate-300 rounded">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <button class="px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold">Simpan Pengaturan</button>
</form>
@endsection