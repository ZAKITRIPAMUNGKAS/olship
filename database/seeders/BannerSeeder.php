<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banner;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        Banner::query()->delete();

        $banners = [
            [
                'title' => 'DISKON HINGGA 70%',
                'image' => 'images/banner_perkakas_nasional.png',
                'link' => '/category/power-tools',
                'position' => 'hero',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'LISTRIK & PENCAHAYAAN',
                'image' => 'images/banner_kelistrikan_lampu.png',
                'link' => '/category/kelistrikan',
                'position' => 'hero',
                'sort_order' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::updateOrCreate(['title' => $banner['title']], $banner);
        }
    }
}
