<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->get('from');
        $to = $request->get('to');
        $expenses = Expense::with('category')
            ->when($from, fn ($q) => $q->whereDate('date', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('date', '<=', $to))
            ->latest('date')->paginate(15)->withQueryString();
        $categories = ExpenseCategory::orderBy('name')->get();
        $dailyTotal = Expense::whereDate('date', today())->sum('amount');
        $monthlyTotal = Expense::whereMonth('date', now()->month)->whereYear('date', now()->year)->sum('amount');
        return view('expenses.index', compact('expenses', 'categories', 'dailyTotal', 'monthlyTotal', 'from', 'to'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'expense_category_id' => 'nullable|exists:expense_categories,id',
            'new_category' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'date' => 'required|date',
        ]);
        if (!empty($data['new_category'])) {
            $cat = ExpenseCategory::firstOrCreate(['name' => $data['new_category']]);
            $data['expense_category_id'] = $cat->id;
        }
        Expense::create([
            'expense_category_id' => $data['expense_category_id'] ?? null,
            'amount' => $data['amount'],
            'description' => $data['description'] ?? null,
            'date' => $data['date'],
        ]);
        return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil dicatat.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Pengeluaran dihapus.');
    }

    public function storeCategory(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:255|unique:expense_categories,name']);
        ExpenseCategory::create($data);
        return redirect()->route('expenses.index')->with('success', 'Kategori pengeluaran ditambahkan.');
    }
}