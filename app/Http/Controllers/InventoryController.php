<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\InventoryMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index()
    {
        $items = Inventory::with('movements')->latest()->get();
        $lowStock = $items->filter(fn ($i) => (float) $i->stock <= (float) $i->minimum_stock && (float) $i->minimum_stock > 0);
        return view('inventory.index', compact('items', 'lowStock'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'stock' => 'nullable|numeric|min:0',
            'minimum_stock' => 'nullable|numeric|min:0',
        ]);
        Inventory::create([
            'name' => $data['name'],
            'unit' => $data['unit'],
            'stock' => $data['stock'] ?? 0,
            'minimum_stock' => $data['minimum_stock'] ?? 0,
        ]);
        return redirect()->route('inventory.index')->with('success', 'Item stok berhasil ditambahkan.');
    }

    public function movement(Request $request)
    {
        $data = $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'type' => 'required|in:in,out',
            'quantity' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
            'date' => 'required|date',
        ]);

        DB::transaction(function () use ($data) {
            $item = Inventory::findOrFail($data['inventory_id']);
            if ($data['type'] === 'in') {
                $item->stock = (float) $item->stock + (float) $data['quantity'];
            } else {
                $item->stock = (float) $item->stock - (float) $data['quantity'];
                if ($item->stock < 0) {
                    $item->stock = 0;
                }
            }
            $item->save();
            InventoryMovement::create([
                'inventory_id' => $data['inventory_id'],
                'type' => $data['type'],
                'quantity' => $data['quantity'],
                'description' => $data['description'] ?? null,
                'date' => $data['date'],
            ]);
        });

        return redirect()->route('inventory.index')->with('success', 'Pergerakan stok berhasil dicatat.');
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        return redirect()->route('inventory.index')->with('success', 'Item stok dihapus.');
    }
}