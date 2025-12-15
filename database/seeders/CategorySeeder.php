<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Minuman Panas',
                'slug' => 'minuman-panas',
                'description' => 'Kopi, teh, dan minuman hangat lainnya',
                'is_active' => true,
            ],
            [
                'name' => 'Minuman Dingin',
                'slug' => 'minuman-dingin',
                'description' => 'Es kopi, es teh, jus, dan minuman segar',
                'is_active' => true,
            ],
            [
                'name' => 'Makanan Berat',
                'slug' => 'makanan-berat',
                'description' => 'Nasi goreng, mie, dan makanan utama',
                'is_active' => true,
            ],
            [
                'name' => 'Snack',
                'slug' => 'snack',
                'description' => 'Gorengan, kentang, dan cemilan ringan',
                'is_active' => true,
            ],
            [
                'name' => 'Roti & Kue',
                'slug' => 'roti-kue',
                'description' => 'Roti bakar, pancake, dan kue-kue',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
