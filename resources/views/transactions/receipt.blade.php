<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Nota {{ $transaction->transaction_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-6 max-w-md mx-auto text-sm">
    <div class="text-center mb-4">
        @php $logo = $settings['logo'] ?? null; @endphp
        @if($logo)
            <img src="{{ asset('storage/'.$logo) }}" class="w-16 h-16 mx-auto object-contain" alt="logo">
        @endif
        <h1 class="text-lg font-bold">{{ $settings['laundry_name'] ?? 'Laundry' }}</h1>
        <p class="text-xs">{{ $settings['address'] ?? '' }}</p>
        <p class="text-xs">Telp: {{ $settings['phone'] ?? '' }}</p>
    </div>
    <hr class="my-2">
    <div class="mb-2">
        <div>No. Nota: {{ $transaction->transaction_number }}</div>
        <div>Tanggal: {{ $transaction->created_at->format('d M Y H:i') }}</div>
        <div>Pelanggan: {{ $transaction->customer->name }}</div>
        <div>Telepon: {{ $transaction->customer->phone }}</div>
        <div>Estimasi: {{ $transaction->estimated_finish ? $transaction->estimated_finish->format('d M Y H:i') : ($transaction->details->first()?->service?->estimation ?? '-') }}</div>
    </div>
    <hr class="my-2">
    <table class="w-full">
        <thead>
            <tr class="border-b">
                <th class="text-left py-1">Layanan</th>
                <th class="text-right py-1">Qty</th>
                <th class="text-right py-1">Harga</th>
                <th class="text-right py-1">Sub</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaction->details as $d)
                <tr>
                    <td class="py-1">{{ $d->service->name }}</td>
                    <td class="text-right py-1">{{ $d->quantity }}</td>
                    <td class="text-right py-1">{{ number_format($d->price,0,',','.') }}</td>
                    <td class="text-right py-1">{{ number_format($d->subtotal,0,',','.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <hr class="my-2">
    <div class="space-y-1">
        <div class="flex justify-between"><span>Subtotal</span><span>{{ number_format($transaction->subtotal,0,',','.') }}</span></div>
        <div class="flex justify-between"><span>Diskon</span><span>{{ number_format($transaction->discount,0,',','.') }}</span></div>
        <div class="flex justify-between"><span>Biaya Tambahan</span><span>{{ number_format($transaction->extra_charge,0,',','.') }}</span></div>
        <div class="flex justify-between font-bold"><span>Total</span><span>{{ number_format($transaction->total,0,',','.') }}</span></div>
        <div class="flex justify-between"><span>Dibayar</span><span>{{ number_format($transaction->paid_amount,0,',','.') }}</span></div>
        <div class="flex justify-between"><span>Sisa</span><span>{{ number_format($transaction->remaining_amount,0,',','.') }}</span></div>
        <div class="flex justify-between"><span>Status Bayar</span><span>{{ ucfirst($transaction->payment_status) }}</span></div>
    </div>
    <hr class="my-2">
    <p class="text-xs text-center">{{ $settings['nota_info'] ?? 'Terima kasih atas kunjungan Anda.' }}</p>
    <div class="text-center mt-4 print:hidden">
        <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded">Cetak</button>
    </div>
</body>
</html>