@extends('layouts.app')
@section('page-title', 'Status Pesanan')

@section('content')
<form method="GET" class="mb-4 flex gap-2">
    <select name="status" class="px-3 py-2 border border-slate-300 rounded-lg text-sm">
        <option value="">Semua status</option>
        @foreach($statuses as $k=>$v)
            <option value="{{ $k }}" {{ $status==$k?'selected':'' }}>{{ $v }}</option>
        @endforeach
    </select>
    <button class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">Filter</button>
</form>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-600 border-b">
            <tr>
                <th class="text-left px-4 py-3">No. Nota</th>
                <th class="text-left px-4 py-3">Pelanggan</th>
                <th class="text-left px-4 py-3">Status</th>
                <th class="text-left px-4 py-3">Update</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $t)
                <tr class="border-b border-slate-100">
                    <td class="px-4 py-3">{{ $t->transaction_number }}</td>
                    <td class="px-4 py-3">{{ $t->customer->name }}</td>
                    <td class="px-4 py-3">{{ $statuses[$t->work_status] ?? $t->work_status }}</td>
                    <td class="px-4 py-3">
                        <form method="POST" action="{{ route('status.update', $t) }}" class="flex gap-2">
                            @csrf @method('PATCH')
                            <select name="work_status" class="px-2 py-1 border border-slate-300 rounded text-sm">
                                @foreach($statuses as $k=>$v)
                                    <option value="{{ $k }}" {{ $t->work_status==$k?'selected':'' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                            <button class="px-3 py-1 bg-blue-600 text-white rounded text-sm">Update</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-4 py-6 text-center text-slate-400">Belum ada transaksi</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $transactions->links() }}</div>
</div>
@endsection