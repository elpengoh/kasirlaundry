<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('customers', CustomerController::class);
Route::resource('services', ServiceController::class);

Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
Route::get('transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
Route::post('transactions', [TransactionController::class, 'store'])->name('transactions.store');
Route::get('transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
Route::get('transactions/{transaction}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
Route::put('transactions/{transaction}', [TransactionController::class, 'update'])->name('transactions.update');
Route::delete('transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
Route::get('transactions/{transaction}/receipt', [TransactionController::class, 'receipt'])->name('transactions.receipt');

Route::get('status', [StatusController::class, 'index'])->name('status.index');
Route::patch('status/{transaction}', [StatusController::class, 'update'])->name('status.update');

Route::get('expenses', [ExpenseController::class, 'index'])->name('expenses.index');
Route::post('expenses', [ExpenseController::class, 'store'])->name('expenses.store');
Route::delete('expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
Route::post('expenses/categories', [ExpenseController::class, 'storeCategory'])->name('expenses.categories.store');

Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.index');
Route::post('inventory', [InventoryController::class, 'store'])->name('inventory.store');
Route::delete('inventory/{inventory}', [InventoryController::class, 'destroy'])->name('inventory.destroy');
Route::post('inventory/movement', [InventoryController::class, 'movement'])->name('inventory.movement');

Route::get('reports', [ReportController::class, 'index'])->name('reports.index');

Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
Route::put('settings', [SettingController::class, 'update'])->name('settings.update');