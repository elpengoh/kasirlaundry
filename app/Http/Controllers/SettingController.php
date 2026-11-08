<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        $services = Service::orderBy('name')->get();
        return view('settings.index', compact('settings', 'services'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'laundry_name' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'nota_info' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
        ]);
        foreach (['laundry_name', 'address', 'phone', 'nota_info'] as $k) {
            if (isset($data[$k])) {
                Setting::set($k, $data[$k]);
            }
        }
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            Setting::set('logo', $path);
        }
        // service prices/estimation updates
        if ($request->has('services')) {
            foreach ($request->input('services') as $id => $vals) {
                $s = Service::find($id);
                if ($s) {
                    $s->update([
                        'price' => $vals['price'] ?? $s->price,
                        'estimation' => $vals['estimation'] ?? $s->estimation,
                    ]);
                }
            }
        }
        return redirect()->route('settings.index')->with('success', 'Pengaturan disimpan.');
    }
}