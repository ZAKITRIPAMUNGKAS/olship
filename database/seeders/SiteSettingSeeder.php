<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'Listrindo Jaya'],
            ['key' => 'site_tagline', 'value' => 'Professional Tools & Industrial Supplies'],
            ['key' => 'contact_email', 'value' => 'info@listrindojayaelektrik.com'],
            ['key' => 'contact_phone', 'value' => '021-12345678'],
            ['key' => 'address', 'value' => 'Jl. Teknik Raya No. 123, Jakarta Pusat'],
            ['key' => 'currency_symbol', 'value' => 'Rp'],
            ['key' => 'free_shipping_threshold', 'value' => '500000'],
        ];

        foreach ($settings as $setting) {
            DB::table('site_settings')->updateOrInsert(['key' => $setting['key']], $setting);
        }
    }
}
