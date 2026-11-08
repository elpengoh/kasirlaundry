@extends('layouts.app')
@section('page-title', 'Transaksi Kasir')

@section('content')
<form method="POST" action="{{ route('transactions.store') }}" id="txnForm">
    @csrf
    <input type="hidden" name="form_token" value="{{ $token }}">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <label class="block text-sm font-medium mb-1">Pilih Pelanggan</label>
                <select name="customer_id" required class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                    <option value="">-- Pilih Pelanggan --</option>
                    @foreach($customers as $c)
                        <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->phone }})</option>
                    @endforeach
                </select>
                <a href="{{ route('customers.create') }}" class="text-xs text-blue-600 hover:underline mt-1 inline-block">+ Tambah pelanggan baru</a>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-semibold">Layanan</h3>
                    <button type="button" id="addRow" class="px-3 py-1 bg-blue-600 text-white rounded text-sm">+ Tambah Item</button>
                </div>
                <table class="w-full text-sm" id="itemsTable">
                    <thead class="text-slate-500 border-b">
                        <tr>
                            <th class="text-left py-2">Layanan</th>
                            <th class="text-left py-2">Qty</th>
                            <th class="text-left py-2">Harga</th>
                            <th class="text-left py-2">Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody"></tbody>
                </table>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <label class="block text-sm font-medium mb-1">Catatan</label>
                <textarea name="notes" class="w-full px-3 py-2 border border-slate-300 rounded-lg"></textarea>
            </div>
        </div>

        <div class="space-y-4">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="font-semibold mb-3">Pembayaran</h3>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Diskon</label>
                    <input type="number" step="0.01" name="discount" id="discount" value="0" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Biaya Tambahan</label>
                    <input type="number" step="0.01" name="extra_charge" id="extra" value="0" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Metode Pembayaran</label>
                    <select name="payment_method" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                        <option value="cash">Tunai</option>
                        <option value="transfer">Transfer Bank</option>
                        <option value="qris">QRIS</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Jumlah Dibayar</label>
                    <input type="number" step="0.01" name="paid_amount" id="paid" value="0" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Status Pengerjaan</label>
                    <select name="work_status" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                        <option value="waiting">Menunggu</option>
                        <option value="processing">Diproses</option>
                        <option value="washing">Dicuci</option>
                        <option value="drying">Dikeringkan</option>
                        <option value="ironing">Disetrika</option>
                        <option value="ready">Siap diambil</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Estimasi Selesai</label>
                    <input type="datetime-local" name="estimated_finish" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                </div>

                <div class="border-t pt-3 space-y-1 text-sm">
                    <div class="flex justify-between"><span>Subtotal</span><span id="subtotalText">0</span></div>
                    <div class="flex justify-between"><span>Diskon</span><span id="discountText">0</span></div>
                    <div class="flex justify-between"><span>Biaya Tambahan</span><span id="extraText">0</span></div>
                    <div class="flex justify-between font-bold text-lg"><span>Total</span><span id="totalText">0</span></div>
                    <div class="flex justify-between text-red-600"><span>Sisa Tagihan</span><span id="remainingText">0</span></div>
                </div>

                <button type="submit" class="w-full mt-4 px-4 py-2 bg-green-600 text-white rounded-lg font-semibold">Simpan Transaksi</button>
            </div>
        </div>
    </div>
</form>

<script>
    const services = @json($services);
    let rowIndex = 0;
    function addRow() {
        const tbody = document.getElementById('itemsBody');
        const tr = document.createElement('tr');
        tr.className = 'border-b border-slate-100';
        tr.dataset.index = rowIndex;
        tr.innerHTML = `
            <td class="py-2">
                <select name="items[${rowIndex}][service_id]" class="svc w-full px-2 py-1 border border-slate-300 rounded" required>
                    <option value="">--</option>
                    ${services.map(s => `<option value="${s.id}" data-price="${s.price}">${s.name} (${s.unit})</option>`).join('')}
                </select>
            </td>
            <td class="py-2"><input type="number" step="0.01" name="items[${rowIndex}][quantity]" class="qty w-20 px-2 py-1 border border-slate-300 rounded" value="1" required></td>
            <td class="py-2 price">0</td>
            <td class="py-2 sub">0</td>
            <td class="py-2"><button type="button" class="remove text-red-600">x</button></td>
        `;
        tbody.appendChild(tr);
        rowIndex++;
        bindRow(tr);
        calc();
    }
    function bindRow(tr) {
        const svc = tr.querySelector('.svc');
        const qty = tr.querySelector('.qty');
        svc.addEventListener('change', () => {
            const opt = svc.options[svc.selectedIndex];
            const price = opt ? opt.dataset.price : 0;
            tr.querySelector('.price').textContent = Number(price).toLocaleString('id-ID');
            calc();
        });
        qty.addEventListener('input', calc);
        tr.querySelector('.remove').addEventListener('click', () => { tr.remove(); calc(); });
    }
    function calc() {
        let subtotal = 0;
        document.querySelectorAll('#itemsBody tr').forEach(tr => {
            const svc = tr.querySelector('.svc');
            const opt = svc.options[svc.selectedIndex];
            const price = opt ? parseFloat(opt.dataset.price || 0) : 0;
            const qty = parseFloat(tr.querySelector('.qty').value || 0);
            const line = price * qty;
            tr.querySelector('.sub').textContent = line.toLocaleString('id-ID');
            subtotal += line;
        });
        const discount = parseFloat(document.getElementById('discount').value || 0);
        const extra = parseFloat(document.getElementById('extra').value || 0);
        const total = subtotal + extra - discount;
        const paid = parseFloat(document.getElementById('paid').value || 0);
        const remaining = total - paid;
        document.getElementById('subtotalText').textContent = subtotal.toLocaleString('id-ID');
        document.getElementById('discountText').textContent = discount.toLocaleString('id-ID');
        document.getElementById('extraText').textContent = extra.toLocaleString('id-ID');
        document.getElementById('totalText').textContent = (total < 0 ? 0 : total).toLocaleString('id-ID');
        document.getElementById('remainingText').textContent = (remaining < 0 ? 0 : remaining).toLocaleString('id-ID');
    }
    document.getElementById('addRow').addEventListener('click', addRow);
    ['discount','extra','paid'].forEach(id => document.getElementById(id).addEventListener('input', calc));
    // initial row
    addRow();
</script>
@endsection