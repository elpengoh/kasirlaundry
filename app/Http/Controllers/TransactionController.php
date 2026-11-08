<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Payment;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');
        $workStatus = $request->get('work_status');
        $paymentStatus = $request->get('payment_status');
        $from = $request->get('from');
        $to = $request->get('to');

        $transactions = Transaction::with('customer')
            ->when($q, fn ($qr) => $qr->where('transaction_number', 'like', "%$q%")
                ->orWhereHas('customer', fn ($c) => $c->where('name', 'like', "%$q%")))
            ->when($workStatus, fn ($qr) => $qr->where('work_status', $workStatus))
            ->when($paymentStatus, fn ($qr) => $qr->where('payment_status', $paymentStatus))
            ->when($from, fn ($qr) => $qr->whereDate('created_at', '>=', $from))
            ->when($to, fn ($qr) => $qr->whereDate('created_at', '<=', $to))
            ->latest()->paginate(15)->withQueryString();

        return view('transactions.index', compact('transactions', 'q', 'workStatus', 'paymentStatus', 'from', 'to'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $services = Service::orderBy('name')->get();
        $token = Str::uuid()->toString();
        session(['txn_token' => $token]);
        return view('transactions.create', compact('customers', 'services', 'token'));
    }

    public function store(Request $request)
    {
        if ($request->input('form_token') !== session('txn_token')) {
            return redirect()->route('transactions.index')->with('error', 'Form sudah dikirim atau tidak valid.');
        }
        session()->forget('txn_token');

        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.service_id' => 'required|exists:services,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'discount' => 'nullable|numeric|min:0',
            'extra_charge' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|in:cash,transfer,qris',
            'paid_amount' => 'nullable|numeric|min:0',
            'work_status' => 'nullable|string',
            'estimated_finish' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $discount = (float) ($data['discount'] ?? 0);
        $extra = (float) ($data['extra_charge'] ?? 0);
        $paid = (float) ($data['paid_amount'] ?? 0);

        DB::transaction(function () use ($data, $discount, $extra, $paid, &$transaction) {
            $subtotal = 0;
            $items = [];
            foreach ($data['items'] as $item) {
                $service = Service::findOrFail($item['service_id']);
                $qty = (float) $item['quantity'];
                $line = $qty * (float) $service->price;
                $subtotal += $line;
                $items[] = [
                    'service_id' => $service->id,
                    'quantity' => $qty,
                    'price' => $service->price,
                    'subtotal' => $line,
                ];
            }
            $total = $subtotal + $extra - $discount;
            if ($total < 0) {
                $total = 0;
            }
            if ($paid > $total) {
                $paid = $total;
            }
            $remaining = $total - $paid;
            if ($paid <= 0) {
                $paymentStatus = 'unpaid';
            } elseif ($paid >= $total) {
                $paymentStatus = 'paid';
            } else {
                $paymentStatus = 'dp';
            }

            // unique number
            do {
                $number = 'TRX-' . now()->format('Ymd') . '-' . str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
            } while (Transaction::where('transaction_number', $number)->exists());

            $transaction = Transaction::create([
                'transaction_number' => $number,
                'customer_id' => $data['customer_id'],
                'subtotal' => $subtotal,
                'discount' => $discount,
                'extra_charge' => $extra,
                'total' => $total,
                'paid_amount' => $paid,
                'remaining_amount' => $remaining,
                'payment_status' => $paymentStatus,
                'work_status' => $data['work_status'] ?? 'waiting',
                'payment_method' => $data['payment_method'] ?? null,
                'estimated_finish' => $data['estimated_finish'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($items as $it) {
                $transaction->details()->create($it);
            }

            if ($paid > 0) {
                $transaction->payments()->create([
                    'amount' => $paid,
                    'method' => $data['payment_method'] ?? 'cash',
                    'paid_at' => now(),
                ]);
            }
        });

        return redirect()->route('transactions.show', $transaction)->with('success', 'Transaksi berhasil disimpan.');
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['customer', 'details.service', 'payments']);
        return view('transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        $transaction->load(['details.service']);
        $customers = Customer::orderBy('name')->get();
        $services = Service::orderBy('name')->get();
        return view('transactions.edit', compact('transaction', 'customers', 'services'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $data = $request->validate([
            'work_status' => 'nullable|string',
            'payment_status' => 'nullable|in:unpaid,dp,paid',
            'paid_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|in:cash,transfer,qris',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($transaction, $data) {
            if (isset($data['work_status'])) {
                $transaction->work_status = $data['work_status'];
            }
            if (isset($data['payment_status'])) {
                $transaction->payment_status = $data['payment_status'];
            }
            if (isset($data['payment_method'])) {
                $transaction->payment_method = $data['payment_method'];
            }
            if (isset($data['notes'])) {
                $transaction->notes = $data['notes'];
            }
            if (isset($data['paid_amount'])) {
                $newPaid = (float) $data['paid_amount'];
                // add payment record for increment
                $diff = $newPaid - (float) $transaction->paid_amount;
                if ($diff > 0) {
                    $transaction->payments()->create([
                        'amount' => $diff,
                        'method' => $data['payment_method'] ?? $transaction->payment_method ?? 'cash',
                        'paid_at' => now(),
                    ]);
                }
                $transaction->paid_amount = $newPaid;
                if ($newPaid >= (float) $transaction->total) {
                    $transaction->payment_status = 'paid';
                } elseif ($newPaid > 0) {
                    $transaction->payment_status = 'dp';
                } else {
                    $transaction->payment_status = 'unpaid';
                }
                $transaction->remaining_amount = (float) $transaction->total - $newPaid;
                if ($transaction->remaining_amount < 0) {
                    $transaction->remaining_amount = 0;
                }
            }
            $transaction->save();
        });

        return redirect()->route('transactions.show', $transaction)->with('success', 'Transaksi diperbarui.');
    }

    public function receipt(Transaction $transaction)
    {
        $transaction->load(['customer', 'details.service']);
        $settings = \App\Models\Setting::pluck('value', 'key');
        return view('transactions.receipt', compact('transaction', 'settings'));
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return redirect()->route('transactions.index')->with('success', 'Transaksi dihapus.');
    }
}