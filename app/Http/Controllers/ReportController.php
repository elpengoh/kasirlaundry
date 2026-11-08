<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Expense;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->get('from', now()->startOfMonth()->format('Y-m-d'));
        $to = $request->get('to', now()->format('Y-m-d'));

        $transactions = Transaction::with('customer', 'details.service')
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->get();

        $totalRevenue = $transactions->sum('total');
        $totalTransactions = $transactions->count();
        $totalExpenses = Expense::whereDate('date', '>=', $from)->whereDate('date', '<=', $to)->sum('amount');
        $netProfit = $totalRevenue - $totalExpenses;

        // most used service
        $serviceCounts = TransactionDetail::whereIn('transaction_id', $transactions->pluck('id'))
            ->selectRaw('service_id, SUM(quantity) as qty')
            ->groupBy('service_id')->orderByDesc('qty')->get();
        $topServices = $serviceCounts->map(function ($r) {
            $s = Service::find($r->service_id);
            return ['name' => $s?->name, 'qty' => $r->qty];
        });

        // top customers
        $custCounts = $transactions->groupBy('customer_id')->map(fn ($g) => [
            'customer' => $g->first()->customer,
            'count' => $g->count(),
            'total' => $g->sum('total'),
        ])->sortByDesc('count')->values();

        // daily breakdown
        $daily = $transactions->groupBy(fn ($t) => $t->created_at->format('Y-m-d'))
            ->map(fn ($g, $d) => ['date' => $d, 'count' => $g->count(), 'total' => $g->sum('total')])
            ->sortBy('date')->values();

        return view('reports.index', compact(
            'from', 'to', 'totalRevenue', 'totalTransactions', 'totalExpenses', 'netProfit',
            'topServices', 'custCounts', 'daily'
        ));
    }
}