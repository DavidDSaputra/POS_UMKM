<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all()->keyBy('slug');

        $products = [
            // Minuman Panas
            [
                'category_slug' => 'minuman-panas',
                'name' => 'Kopi Hitam',
                'price' => 8000,
                'is_active' => true,
            ],
            [
                'category_slug' => 'minuman-panas',
                'name' => 'Kopi Susu',
                'price' => 12000,
                'is_active' => true,
            ],
            [
                'category_slug' => 'minuman-panas',
                'name' => 'Teh Panas',
                'price' => 5000,
                'is_active' => true,
            ],
            [
                'category_slug' => 'minuman-panas',
                'name' => 'Teh Tarik',
                'price' => 10000,
                'is_active' => true,
            ],
            [
                'category_slug' => 'minuman-panas',
                'name' => 'Coklat Panas',
                'price' => 12000,
                'is_active' => true,
            ],
            [
                'category_slug' => 'minuman-panas',
                'name' => 'Cappuccino',
                'price' => 15000,
                'is_active' => true,
            ],

            // Minuman Dingin
            [
                'category_slug' => 'minuman-dingin',
                'name' => 'Es Kopi Susu',
                'price' => 15000,
                'is_active' => true,
            ],
            [
                'category_slug' => 'minuman-dingin',
                'name' => 'Es Teh Manis',
                'price' => 6000,
                'is_active' => true,
            ],
            [
                'category_slug' => 'minuman-dingin',
                'name' => 'Es Jeruk',
                'price' => 8000,
                'is_active' => true,
            ],
            [
                'category_slug' => 'minuman-dingin',
                'name' => 'Es Coklat',
                'price' => 14000,
                'is_active' => true,
            ],
            [
                'category_slug' => 'minuman-dingin',
                'name' => 'Jus Alpukat',
                'price' => 15000,
                'is_active' => true,
            ],
            [
                'category_slug' => 'minuman-dingin',
                'name' => 'Jus Mangga',
                'price' => 12000,
                'is_active' => true,
            ],

            // Makanan Berat
            [
                'category_slug' => 'makanan-berat',
                'name' => 'Nasi Goreng Spesial',
                'price' => 20000,
                'is_active' => true,
            ],
            [
                'category_slug' => 'makanan-berat',
                'name' => 'Nasi Goreng Telur',
                'price' => 15000,
                'is_active' => true,
            ],
            [
                'category_slug' => 'makanan-berat',
                'name' => 'Mie Goreng',
                'price' => 15000,
                'is_active' => true,
            ],
            [
                'category_slug' => 'makanan-berat',
                'name' => 'Mie Rebus',
                'price' => 15000,
                'is_active' => true,
            ],
            [
                'category_slug' => 'makanan-berat',
                'name' => 'Indomie Goreng',
                'price' => 10000,
                'is_active' => true,
            ],
            [
                'category_slug' => 'makanan-berat',
                'name' => 'Indomie Rebus',
                'price' => 10000,
                'is_active' => true,
            ],

            // Snack
            [
                'category_slug' => 'snack',
                'name' => 'Kentang Goreng',
                'price' => 15000,
                'is_active' => true,
            ],
            [
                'category_slug' => 'snack',
                'name' => 'Pisang Goreng',
                'price' => 10000,
                'stock' => 20,
                'is_active' => true,
            ],
            [
                'category_slug' => 'snack',
                'name' => 'Tahu Crispy',
                'price' => 8000,
                'stock' => 15,
                'is_active' => true,
            ],
            [
                'category_slug' => 'snack',
                'name' => 'Tempe Mendoan',
                'price' => 8000,
                'stock' => 15,
                'is_active' => true,
            ],
            [
                'category_slug' => 'snack',
                'name' => 'Cireng',
                'price' => 10000,
                'stock' => 10,
                'is_active' => true,
            ],

            // Roti & Kue
            [
                'category_slug' => 'roti-kue',
                'name' => 'Roti Bakar Coklat',
                'price' => 12000,
                'is_active' => true,
            ],
            [
                'category_slug' => 'roti-kue',
                'name' => 'Roti Bakar Keju',
                'price' => 15000,
                'is_active' => true,
            ],
            [
                'category_slug' => 'roti-kue',
                'name' => 'Roti Bakar Strawberry',
                'price' => 12000,
                'is_active' => true,
            ],
            [
                'category_slug' => 'roti-kue',
                'name' => 'Pancake',
                'price' => 18000,
                'is_active' => true,
            ],
            [
                'category_slug' => 'roti-kue',
                'name' => 'Waffle',
                'price' => 20000,
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            $categorySlug = $product['category_slug'];
            unset($product['category_slug']);

            if (isset($categories[$categorySlug])) {
                $product['category_id'] = $categories[$categorySlug]->id;
                Product::create($product);
            }
        }
    }
}
