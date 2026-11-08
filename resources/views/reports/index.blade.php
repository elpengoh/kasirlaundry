@extends('layouts.app')
@section('page-title', 'Laporan Keuangan')

@section('content')
<form method="GET" class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 mb-4 flex flex-wrap gap-3 items-end">
    <div>
        <label class="block text-xs text-slate-500 mb-1">Dari</label>
        <input type="date" name="from" value="{{ $from }}" class="px-3 py-2 border border-slate-300 rounded-lg text-sm">
    </div>
    <div>
        <label class="block text-xs text-slate-500 mb-1">Sampai</label>
        <input type="date" name="to" value="{{ $to }}" class="px-3 py-2 border border-slate-300 rounded-lg text-sm">
    </div>
    <button class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">Tampilkan</button>
</form>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200">
        <div class="text-sm text-slate-500">Total Pendapatan</div>
        <div class="text-2xl font-bold text-blue-600 mt-1">Rp {{ number_format($totalRevenue,0,',','.') }}</div>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200">
        <div class="text-sm text-slate-500">Total Transaksi</div>
        <div class="text-2xl font-bold text-slate-800 mt-1">{{ $totalTransactions }}</div>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200">
        <div class="text-sm text-slate-500">Total Pengeluaran</div>
        <div class="text-2xl font-bold text-red-600 mt-1">Rp {{ number_format($totalExpenses,0,',','.') }}</div>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200">
        <div class="text-sm text-slate-500">Keuntungan Bersih</div>
        <div class="text-2xl font-bold text-green-600 mt-1">Rp {{ number_format($netProfit,0,',','.') }}</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h3 class="font-semibold mb-3">Layanan Paling Banyak Digunakan</h3>
        <table class="w-full text-sm">
            <thead class="text-slate-500 border-b"><tr><th class="text-left py-2">Layanan</th><th class="text-right py-2">Qty</th></tr></thead>
            <tbody>
                @foreach($topServices as $s)
                    <tr class="border-b border-slate-100"><td class="py-2">{{ $s['name'] }}</td><td class="text-right py-2">{{ $s['qty'] }}</td></tr>
                @endforeach
                @if($topServices->isEmpty())
                    <tr><td colspan="2" class="py-4 text-center text-slate-400">Tidak ada data</td></tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h3 class="font-semibold mb-3">Pelanggan Paling Sering Bertransaksi</h3>
        <table class="w-full text-sm">
            <thead class="text-slate-500 border-b"><tr><th class="text-left py-2">Pelanggan</th><th class="text-right py-2">Transaksi</th><th class="text-right py-2">Total</th></tr></thead>
            <tbody>
                @foreach($custCounts as $c)
                    <tr class="border-b border-slate-100">
                        <td class="py-2">{{ $c['customer']->name ?? '-' }}</td>
                        <td class="text-right py-2">{{ $c['count'] }}</td>
                        <td class="text-right py-2">Rp {{ number_format($c['total'],0,',','.') }}</td>
                    </tr>
                @endforeach
                @if($custCounts->isEmpty())
                    <tr><td colspan="3" class="py-4 text-center text-slate-400">Tidak ada data</td></tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
    <h3 class="font-semibold mb-3">Pendapatan Harian</h3>
    <table class="w-full text-sm">
        <thead class="text-slate-500 border-b"><tr><th class="text-left py-2">Tanggal</th><th class="text-right py-2">Transaksi</th><th class="text-right py-2">Pendapatan</th></tr></thead>
        <tbody>
            @foreach($daily as $d)
                <tr class="border-b border-slate-100">
                    <td class="py-2">{{ $d['date'] }}</td>
                    <td class="text-right py-2">{{ $d['count'] }}</td>
                    <td class="text-right py-2">Rp {{ number_format($d['total'],0,',','.') }}</td>
                </tr>
            @endforeach
            @if($daily->isEmpty())
                <tr><td colspan="3" class="py-4 text-center text-slate-400">Tidak ada data</td></tr>
            @endif
        </tbody>
    </table>
</div>
@endsection