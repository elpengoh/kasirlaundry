@extends('layouts.app')
@section('page-title', 'Riwayat Transaksi')

@section('content')
<form method="GET" class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 mb-4 grid grid-cols-1 md:grid-cols-5 gap-3">
    <input type="text" name="q" value="{{ $q }}" placeholder="No. nota / nama pelanggan" class="px-3 py-2 border border-slate-300 rounded-lg text-sm">
    <input type="date" name="from" value="{{ $from }}" class="px-3 py-2 border border-slate-300 rounded-lg text-sm">
    <input type="date" name="to" value="{{ $to }}" class="px-3 py-2 border border-slate-300 rounded-lg text-sm">
    <select name="work_status" class="px-3 py-2 border border-slate-300 rounded-lg text-sm">
        <option value="">Semua status kerja</option>
        @foreach(['waiting'=>'Menunggu','processing'=>'Diproses','washing'=>'Dicuci','drying'=>'Dikeringkan','ironing'=>'Disetrika','ready'=>'Siap diambil','taken'=>'Sudah diambil'] as $k=>$v)
            <option value="{{ $k }}" {{ $workStatus==$k?'selected':'' }}>{{ $v }}</option>
        @endforeach
    </select>
    <select name="payment_status" class="px-3 py-2 border border-slate-300 rounded-lg text-sm">
        <option value="">Semua pembayaran</option>
        @foreach(['unpaid'=>'Belum dibayar','dp'=>'DP','paid'=>'Lunas'] as $k=>$v)
            <option value="{{ $k }}" {{ $paymentStatus==$k?'selected':'' }}>{{ $v }}</option>
        @endforeach
    </select>
    <div class="md:col-span-5 flex gap-2">
        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">Filter</button>
        <a href="{{ route('transactions.index') }}" class="px-4 py-2 bg-slate-200 rounded-lg text-sm">Reset</a>
    </div>
</form>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-600 border-b">
            <tr>
                <th class="text-left px-4 py-3">No. Nota</th>
                <th class="text-left px-4 py-3">Pelanggan</th>
                <th class="text-left px-4 py-3">Total</th>
                <th class="text-left px-4 py-3">Pembayaran</th>
                <th class="text-left px-4 py-3">Status</th>
                <th class="text-left px-4 py-3">Tanggal</th>
                <th class="text-left px-4 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $t)
                <tr class="border-b border-slate-100">
                    <td class="px-4 py-3 font-medium">{{ $t->transaction_number }}</td>
                    <td class="px-4 py-3">{{ $t->customer->name }}</td>
                    <td class="px-4 py-3">Rp {{ number_format($t->total,0,',','.') }}</td>
                    <td class="px-4 py-3">{{ ucfirst($t->payment_status) }}</td>
                    <td class="px-4 py-3">{{ $t->work_status }}</td>
                    <td class="px-4 py-3">{{ $t->created_at->format('d M Y H:i') }}</td>
                    <td class="px-4 py-3 flex gap-2">
                        <a href="{{ route('transactions.show', $t) }}" class="text-blue-600 hover:underline">Detail</a>
                        <a href="{{ route('transactions.receipt', $t) }}" target="_blank" class="text-green-600 hover:underline">Cetak</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-4 py-6 text-center text-slate-400">Belum ada transaksi</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $transactions->links() }}</div>
</div>
@endsection