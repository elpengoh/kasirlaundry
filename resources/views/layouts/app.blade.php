<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kasir Laundry') - {{ \App\Models\Setting::get('laundry_name', 'Laundry') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; }
    </style>
</head>
<body class="bg-slate-100 text-slate-800">
<div class="min-h-screen flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-slate-900 text-white flex flex-col fixed inset-y-0 left-0 z-30">
        <div class="px-6 py-5 border-b border-slate-700">
            <div class="flex items-center gap-3">
                @php $logo = \App\Models\Setting::get('logo'); @endphp
                @if($logo)
                    <img src="{{ asset('storage/'.$logo) }}" class="w-9 h-9 rounded object-cover" alt="logo">
                @else
                    <div class="w-9 h-9 rounded bg-blue-500 flex items-center justify-center font-bold">L</div>
                @endif
                <div>
                    <div class="font-semibold text-sm">{{ \App\Models\Setting::get('laundry_name', 'Laundry') }}</div>
                    <div class="text-xs text-slate-400">Kasir Laundry</div>
                </div>
            </div>
        </div>
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            @php
                $menu = [
                    ['Dashboard', route('dashboard'), 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['Transaksi Kasir', route('transactions.create'), 'M12 4v16m8-8H4'],
                    ['Data Pelanggan', route('customers.index'), 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                    ['Layanan Laundry', route('services.index'), 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                    ['Status Pesanan', route('status.index'), 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['Riwayat Transaksi', route('transactions.index'), 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0'],
                    ['Pengeluaran', route('expenses.index'), 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
                    ['Manajemen Stok', route('inventory.index'), 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                    ['Laporan', route('reports.index'), 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                    ['Pengaturan', route('settings.index'), 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
                ];
            @endphp
            @foreach($menu as [$label, $url, $icon])
                <a href="{{ $url }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->url() === $url || request()->fullUrlIs($url.'*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $icon }}"/></svg>
                    <span>{{ $label }}</span>
                </a>
            @endforeach
        </nav>
        <div class="px-4 py-3 border-t border-slate-700 text-xs text-slate-500">
            Penggunaan lokal / jaringan internal tepercaya
        </div>
    </aside>

    <!-- Main -->
    <div class="flex-1 ml-64">
        <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between sticky top-0 z-20">
            <h1 class="text-lg font-semibold text-slate-800">@yield('page-title', 'Dashboard')</h1>
            <div class="text-sm text-slate-500">{{ now()->format('d M Y, H:i') }}</div>
        </header>

        <main class="p-6">
            @if(session('success'))
                <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 text-green-700 border border-green-200">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 text-red-700 border border-red-200">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 text-red-700 border border-red-200">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
</body>
</html>