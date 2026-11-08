@extends('layouts.app')
@section('page-title', 'Detail Transaksi')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-lg font-semibold mb-3">{{ $transaction->transaction_number }}</h2>
        <p class="text-sm">Pelanggan: <strong>{{ $transaction->customer->name }}</strong></p>
        <p class="text-sm">Telepon: {{ $transaction->customer->phone }}</p>
        <p class="text-sm">Tanggal: {{ $transaction->created_at->format('d M Y H:i') }}</p>
        <p class="text-sm">Status Pengerjaan: {{ $transaction->work_status }}</p>
        <p class="text-sm">Status Pembayaran: {{ ucfirst($transaction->payment_status) }}</p>
        <p class="text-sm">Metode: {{ $transaction->payment_method ?? '-' }}</p>
        <p class="text-sm">Estimasi Selesai: {{ $transaction->estimated_finish ? $transaction->estimated_finish->format('d M Y H:i') : '-' }}</p>

        <table class="w-full text-sm mt-4 border-t">
            <thead class="text-slate-500">
                <tr>
                    <th class="text-left py-2">Layanan</th>
                    <th class="text-left py-2">Qty</th>
                    <th class="text-left py-2">Harga</th>
                    <th class="text-left py-2">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaction->details as $d)
                    <tr class="border-b border-slate-100">
                        <td class="py-2">{{ $d->service->name }}</td>
                        <td class="py-2">{{ $d->quantity }}</td>
                        <td class="py-2">Rp {{ number_format($d->price,0,',','.') }}</td>
                        <td class="py-2">Rp {{ number_format($d->subtotal,0,',','.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-3 text-sm space-y-1">
            <div class="flex justify-between"><span>Subtotal</span><span>Rp {{ number_format($transaction->subtotal,0,',','.') }}</span></div>
            <div class="flex justify-between"><span>Diskon</span><span>Rp {{ number_format($transaction->discount,0,',','.') }}</span></div>
            <div class="flex justify-between"><span>Biaya Tambahan</span><span>Rp {{ number_format($transaction->extra_charge,0,',','.') }}</span></div>
            <div class="flex justify-between font-bold"><span>Total</span><span>Rp {{ number_format($transaction->total,0,',','.') }}</span></div>
            <div class="flex justify-between"><span>Dibayar</span><span>Rp {{ number_format($transaction->paid_amount,0,',','.') }}</span></div>
            <div class="flex justify-between text-red-600"><span>Sisa</span><span>Rp {{ number_format($transaction->remaining_amount,0,',','.') }}</span></div>
        </div>

        <div class="mt-4 flex gap-2">
            <a href="{{ route('transactions.receipt', $transaction) }}" target="_blank" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm">Cetak Nota</a>
            <form method="POST" action="{{ route('transactions.destroy', $transaction) }}" onsubmit="return confirm('Hapus transaksi?')">
                @csrf @method('DELETE')
                <button class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm">Hapus</button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h3 class="font-semibold mb-3">Update Status & Pembayaran</h3>
        <form method="POST" action="{{ route('transactions.update', $transaction) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Status Pengerjaan</label>
                <select name="work_status" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                    @foreach(['waiting'=>'Menunggu','processing'=>'Diproses','washing'=>'Dicuci','drying'=>'Dikeringkan','ironing'=>'Disetrika','ready'=>'Siap diambil','taken'=>'Sudah diambil'] as $k=>$v)
                        <option value="{{ $k }}" {{ $transaction->work_status==$k?'selected':'' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Status Pembayaran</label>
                <select name="payment_status" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                    @foreach(['unpaid'=>'Belum dibayar','dp'=>'DP','paid'=>'Lunas'] as $k=>$v)
                        <option value="{{ $k }}" {{ $transaction->payment_status==$k?'selected':'' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Total Dibayar (Rp)</label>
                <input type="number" step="0.01" name="paid_amount" value="{{ $transaction->paid_amount }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Metode Pembayaran</label>
                <select name="payment_method" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                    <option value="cash" {{ $transaction->payment_method=='cash'?'selected':'' }}>Tunai</option>
                    <option value="transfer" {{ $transaction->payment_method=='transfer'?'selected':'' }}>Transfer Bank</option>
                    <option value="qris" {{ $transaction->payment_method=='qris'?'selected':'' }}>QRIS</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Catatan</label>
                <textarea name="notes" class="w-full px-3 py-2 border border-slate-300 rounded-lg">{{ $transaction->notes }}</textarea>
            </div>
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection