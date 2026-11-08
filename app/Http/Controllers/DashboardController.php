<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalTransactions = Transaction::count();
        $totalCustomers = Customer::count();
        $totalRevenue = Transaction::sum('total');
        $processing = Transaction::where('work_status', '!=', 'taken')->where('work_status', '!=', 'ready')->count();
        // "sedang diproses" = not finished/taken? Let's define processing = work_status in processing,washing,drying,ironing
        $processing = Transaction::whereIn('work_status', ['processing', 'washing', 'drying', 'ironing', 'waiting'])->count();
        $finished = Transaction::where('work_status', 'taken')->count() + Transaction::where('work_status', 'ready')->count();
        $unpaid = Transaction::where('payment_status', 'unpaid')->count() + Transaction::where('payment_status', 'dp')->count();

        // daily revenue last 7 days
        $dailyRows = Transaction::where('created_at', '>=', now()->subDays(6)->startOfDay())->get();
        $dailyLabels = [];
        $dailyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i);
            $dailyLabels[] = $day->format('d M');
            $dailyData[] = (float) $dailyRows->filter(fn ($t) => $t->created_at->isSameDay($day))->sum('total');
        }

        // monthly revenue last 6 months
        $monthlyRows = Transaction::where('created_at', '>=', now()->subMonths(5)->startOfMonth())->get();
        $monthlyLabels = [];
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = now()->subMonths($i);
            $monthlyLabels[] = $m->format('M Y');
            $monthlyData[] = (float) $monthlyRows->filter(fn ($t) => $t->created_at->format('Y-m') === $m->format('Y-m'))->sum('total');
        }

        $recent = Transaction::with('customer')->latest()->limit(8)->get();

        return view('dashboard', compact(
            'totalTransactions', 'totalCustomers', 'totalRevenue', 'processing', 'finished', 'unpaid',
            'dailyLabels', 'dailyData', 'monthlyLabels', 'monthlyData', 'recent'
        ));
    }
}