<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Http\Requests\UpdateSettingsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::all()->groupBy('group');
        
        // Ensure defaults if empty
        if ($settings->isEmpty()) {
            $this->seedDefaults();
            $settings = SiteSetting::all()->groupBy('group');
        }

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * [FIX BUG 7] Validasi input menggunakan Form Request.
     */
    public function update(UpdateSettingsRequest $request)
    {
        $data = $request->validated();
        
        // Tambahkan input boolean yang tidak masuk validated() jika checkbox tidak dicentang
        $allSettings = SiteSetting::all();
        
        foreach ($allSettings as $setting) {
            $key = $setting->key;
            
            if ($setting->type === 'image' && $request->hasFile($key)) {
                // Hapus file lama
                if ($setting->value) {
                    Storage::disk('public')->delete($setting->value);
                }
                $value = $request->file($key)->store('settings', 'public');
                $setting->update(['value' => $value]);
            } 
            elseif ($setting->type === 'boolean') {
                $setting->update(['value' => $request->has($key) ? '1' : '0']);
            } 
            elseif (array_key_exists($key, $data)) {
                $value = $data[$key];
                $setting->update(['value' => is_array($value) ? json_encode($value) : $value]);
            }
        }

        return back()->with('success', 'Pengaturan berhasil diperbarui.');
    }

    private function seedDefaults()
    {
        $defaults = [
            ['key' => 'site_name', 'value' => 'LISTRINDO JAYA ELEKTRIK', 'group' => 'general', 'label' => 'Nama Situs'],
            ['key' => 'site_email', 'value' => 'info@listrindojayaelektrik.com', 'group' => 'general', 'label' => 'Email Kontak'],
            ['key' => 'enable_reviews', 'value' => '1', 'group' => 'general', 'type' => 'boolean', 'label' => 'Aktifkan Review'],
            ['key' => 'maintenance_mode', 'value' => '0', 'group' => 'general', 'type' => 'boolean', 'label' => 'Mode Maintenance'],
            
            ['key' => 'currency_symbol', 'value' => 'Rp', 'group' => 'payment', 'label' => 'Simbol Mata Uang'],
            ['key' => 'midtrans_client_key', 'value' => '', 'group' => 'payment', 'label' => 'Midtrans Client Key'],
            
            ['key' => 'shipping_origin_city', 'value' => 'Jakarta', 'group' => 'shipping', 'label' => 'Kota Asal Pengiriman'],
            ['key' => 'rajaongkir_api_key', 'value' => '', 'group' => 'shipping', 'label' => 'RajaOngkir API Key'],
        ];

        foreach ($defaults as $d) {
            SiteSetting::create($d);
        }
    }
}
