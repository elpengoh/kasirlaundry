@extends('layouts.app')
@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200">
        <div class="text-sm text-slate-500">Total Transaksi</div>
        <div class="text-3xl font-bold text-slate-800 mt-1">{{ $totalTransactions }}</div>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200">
        <div class="text-sm text-slate-500">Total Pelanggan</div>
        <div class="text-3xl font-bold text-slate-800 mt-1">{{ $totalCustomers }}</div>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200">
        <div class="text-sm text-slate-500">Total Pendapatan</div>
        <div class="text-3xl font-bold text-blue-600 mt-1">Rp {{ number_format($totalRevenue,0,',','.') }}</div>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200">
        <div class="text-sm text-slate-500">Pesanan Sedang Diproses</div>
        <div class="text-3xl font-bold text-amber-500 mt-1">{{ $processing }}</div>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200">
        <div class="text-sm text-slate-500">Pesanan Selesai</div>
        <div class="text-3xl font-bold text-green-600 mt-1">{{ $finished }}</div>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200">
        <div class="text-sm text-slate-500">Pesanan Belum Dibayar</div>
        <div class="text-3xl font-bold text-red-500 mt-1">{{ $unpaid }}</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200">
        <h2 class="font-semibold mb-3">Pendapatan Harian (7 hari)</h2>
        <canvas id="dailyChart" height="120"></canvas>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200">
        <h2 class="font-semibold mb-3">Pendapatan Bulanan (6 bulan)</h2>
        <canvas id="monthlyChart" height="120"></canvas>
    </div>
</div>

<div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200">
    <h2 class="font-semibold mb-3">Transaksi Terbaru</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-slate-500 border-b">
                <tr>
                    <th class="text-left py-2">No. Nota</th>
                    <th class="text-left py-2">Pelanggan</th>
                    <th class="text-left py-2">Total</th>
                    <th class="text-left py-2">Pembayaran</th>
                    <th class="text-left py-2">Status</th>
                    <th class="text-left py-2">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recent as $t)
                    <tr class="border-b border-slate-100">
                        <td class="py-2"><a href="{{ route('transactions.show', $t) }}" class="text-blue-600 hover:underline">{{ $t->transaction_number }}</a></td>
                        <td class="py-2">{{ $t->customer->name }}</td>
                        <td class="py-2">Rp {{ number_format($t->total,0,',','.') }}</td>
                        <td class="py-2">{{ ucfirst($t->payment_status) }}</td>
                        <td class="py-2">{{ $t->work_status }}</td>
                        <td class="py-2">{{ $t->created_at->format('d M Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-4 text-center text-slate-400">Belum ada transaksi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const dailyCtx = document.getElementById('dailyChart').getContext('2d');
    new Chart(dailyCtx, {
        type: 'bar',
        data: {
            labels: @json($dailyLabels),
            datasets: [{ label: 'Pendapatan', data: @json($dailyData), backgroundColor: '#3b82f6' }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: @json($monthlyLabels),
            datasets: [{ label: 'Pendapatan', data: @json($monthlyData), borderColor: '#1e40af', backgroundColor: 'rgba(30,64,175,0.1)', fill: true, tension: 0.3 }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });
</script>
@endsection