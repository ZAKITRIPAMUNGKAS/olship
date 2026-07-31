<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Power Tools', 'icon_class' => 'fas fa-drill'],
            ['name' => 'Hand Tools', 'icon_class' => 'fas fa-wrench'],
            ['name' => 'Alat Ukur', 'icon_class' => 'fas fa-ruler'],
            ['name' => 'Kelistrikan', 'icon_class' => 'fas fa-plug'],
            ['name' => 'Lampu', 'icon_class' => 'fas fa-lightbulb'],
            ['name' => 'Pneumatic', 'icon_class' => 'fas fa-wind'],
            ['name' => 'Perlengkapan Las', 'icon_class' => 'fas fa-fire'],
            ['name' => 'Alat Safety', 'icon_class' => 'fas fa-hard-hat'],
            ['name' => 'Frozen Food', 'icon_class' => 'fas fa-snowflake'],
        ];

        foreach ($categories as $cat) {
            $cat['slug'] = Str::slug($cat['name']);
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
