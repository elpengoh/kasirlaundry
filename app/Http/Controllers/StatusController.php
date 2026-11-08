<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        $transactions = Transaction::with('customer')
            ->when($status, fn ($q) => $q->where('work_status', $status))
            ->latest()->paginate(20)->withQueryString();
        $statuses = [
            'waiting' => 'Menunggu',
            'processing' => 'Diproses',
            'washing' => 'Dicuci',
            'drying' => 'Dikeringkan',
            'ironing' => 'Disetrika',
            'ready' => 'Siap diambil',
            'taken' => 'Sudah diambil',
        ];
        return view('status.index', compact('transactions', 'statuses', 'status'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $request->validate(['work_status' => 'required|in:waiting,processing,washing,drying,ironing,ready,taken']);
        $transaction->update(['work_status' => $request->work_status]);
        return back()->with('success', 'Status diperbarui.');
    }
}