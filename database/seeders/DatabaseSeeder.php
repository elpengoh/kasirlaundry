<?php

namespace Database\Seeders;

use App\Models\ExpenseCategory;
use App\Models\Inventory;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['name' => 'Cuci Kering', 'price' => 7000, 'unit' => 'kg', 'estimation' => '2 hari'],
            ['name' => 'Cuci Setrika', 'price' => 9000, 'unit' => 'kg', 'estimation' => '2 hari'],
            ['name' => 'Setrika Saja', 'price' => 5000, 'unit' => 'kg', 'estimation' => '1 hari'],
            ['name' => 'Laundry Express', 'price' => 15000, 'unit' => 'kg', 'estimation' => '1 hari'],
            ['name' => 'Laundry Bed Cover', 'price' => 25000, 'unit' => 'item', 'estimation' => '3 hari'],
            ['name' => 'Laundry Selimut', 'price' => 20000, 'unit' => 'item', 'estimation' => '3 hari'],
            ['name' => 'Laundry Sepatu', 'price' => 30000, 'unit' => 'item', 'estimation' => '3 hari'],
        ];
        foreach ($services as $s) {
            Service::firstOrCreate(['name' => $s['name']], $s);
        }

        foreach (['Pembelian deterjen', 'Pewangi', 'Listrik', 'Air', 'Gaji karyawan', 'Perawatan mesin', 'Pengeluaran lainnya'] as $name) {
            ExpenseCategory::firstOrCreate(['name' => $name]);
        }

        foreach ([
            ['name' => 'Deterjen', 'unit' => 'liter', 'stock' => 10, 'minimum_stock' => 3],
            ['name' => 'Pewangi', 'unit' => 'liter', 'stock' => 5, 'minimum_stock' => 2],
            ['name' => 'Pelembut', 'unit' => 'liter', 'stock' => 5, 'minimum_stock' => 2],
            ['name' => 'Plastik Laundry', 'unit' => 'pcs', 'stock' => 100, 'minimum_stock' => 20],
        ] as $inv) {
            Inventory::firstOrCreate(['name' => $inv['name']], $inv);
        }

        Setting::set('laundry_name', 'Laundry Bersih');
        Setting::set('address', 'Jl. Contoh No. 1');
        Setting::set('phone', '08123456789');
        Setting::set('nota_info', 'Terima kasih atas kunjungan Anda.');
    }
}