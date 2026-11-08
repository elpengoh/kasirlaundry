@extends('layouts.app')
@section('page-title', 'Detail Pelanggan')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
    <h2 class="text-xl font-semibold mb-2">{{ $customer->name }}</h2>
    <p class="text-sm text-slate-600">Telepon: {{ $customer->phone ?? '-' }}</p>
    <p class="text-sm text-slate-600">Alamat: {{ $customer->address ?? '-' }}</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
    <h3 class="font-semibold mb-3">Riwayat Transaksi</h3>
    <table class="w-full text-sm">
        <thead class="text-slate-500 border-b">
            <tr>
                <th class="text-left py-2">No. Nota</th>
                <th class="text-left py-2">Total</th>
                <th class="text-left py-2">Pembayaran</th>
                <th class="text-left py-2">Status</th>
                <th class="text-left py-2">Tanggal</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($customer->transactions as $t)
                <tr class="border-b border-slate-100">
                    <td class="py-2">{{ $t->transaction_number }}</td>
                    <td class="py-2">Rp {{ number_format($t->total,0,',','.') }}</td>
                    <td class="py-2">{{ ucfirst($t->payment_status) }}</td>
                    <td class="py-2">{{ $t->work_status }}</td>
                    <td class="py-2">{{ $t->created_at->format('d M Y') }}</td>
                    <td class="py-2"><a href="{{ route('transactions.show', $t) }}" class="text-blue-600 hover:underline">Detail</a></td>
                </tr>
            @empty
                <tr><td colspan="6" class="py-4 text-center text-slate-400">Belum ada transaksi</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection