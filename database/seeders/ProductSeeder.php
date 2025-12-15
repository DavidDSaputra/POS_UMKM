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
        // Hapus data lama jika perlu (optional)
        // Product::truncate();
        
        $categories = Category::all()->keyBy('slug');
        
        // Data template produk per kategori
        $productTemplates = [
            'minuman-panas' => [
                ['Kopi Hitam', 8000],
                ['Kopi Susu', 12000],
                ['Teh Panas', 5000],
                ['Teh Tarik', 10000],
                ['Coklat Panas', 12000],
                ['Cappuccino', 15000],
                ['Latte', 18000],
                ['Espresso', 10000],
                ['Macchiato', 13000],
                ['Americano', 9000],
                ['Mocha', 16000],
                ['Matcha Latte', 17000],
                ['Red Velvet Latte', 20000],
                ['Hazelnut Coffee', 16000],
                ['Caramel Macchiato', 18000],
            ],
            'minuman-dingin' => [
                ['Es Kopi Susu', 15000],
                ['Es Teh Manis', 6000],
                ['Es Jeruk', 8000],
                ['Es Coklat', 14000],
                ['Jus Alpukat', 15000],
                ['Jus Mangga', 12000],
                ['Jus Strawberry', 13000],
                ['Jus Melon', 10000],
                ['Smoothie Berry', 18000],
                ['Smoothie Banana', 16000],
                ['Milk Shake Coklat', 20000],
                ['Thai Tea', 15000],
                ['Es Taro', 17000],
                ['Es Kelapa Muda', 15000],
                ['Es Campur', 12000],
            ],
            'makanan-berat' => [
                ['Nasi Goreng Spesial', 20000],
                ['Nasi Goreng Telur', 15000],
                ['Mie Goreng', 15000],
                ['Mie Rebus', 15000],
                ['Indomie Goreng', 10000],
                ['Indomie Rebus', 10000],
                ['Nasi Goreng Seafood', 25000],
                ['Nasi Goreng Ayam', 18000],
                ['Mie Goreng Seafood', 22000],
                ['Nasi Ayam Penyet', 22000],
                ['Nasi Soto Ayam', 20000],
                ['Nasi Rawon', 25000],
                ['Nasi Gudeg', 22000],
                ['Nasi Padang', 25000],
                ['Mie Ayam', 15000],
            ],
            'snack' => [
                ['Kentang Goreng', 15000],
                ['Pisang Goreng', 10000],
                ['Tahu Crispy', 8000],
                ['Tempe Mendoan', 8000],
                ['Cireng', 10000],
                ['Batagor', 15000],
                ['Siomay', 18000],
                ['Risol Mayo', 12000],
                ['Martabak Mini', 15000],
                ['Donat', 8000],
                ['Nugget Ayam', 15000],
                ['Sosis Goreng', 10000],
                ['Chicken Wings', 25000],
                ['Cheese Stick', 15000],
                ['Popcorn Chicken', 18000],
            ],
            'roti-kue' => [
                ['Roti Bakar Coklat', 12000],
                ['Roti Bakar Keju', 15000],
                ['Roti Bakar Strawberry', 12000],
                ['Pancake', 18000],
                ['Waffle', 20000],
                ['Roti Bakar Nutella', 22000],
                ['Crepes Coklat', 15000],
                ['Brownies', 15000],
                ['Muffin Coklat', 12000],
                ['Croissant', 15000],
                ['Donat Coklat', 10000],
                ['Bagel', 15000],
                ['Toast Avocado', 18000],
                ['French Toast', 20000],
                ['Banana Bread', 15000],
            ],
        ];
        
        $variations = ['Spesial', 'Premium', 'Original', 'Deluxe', 'Extra', 'Gold', 'Special', 'Super'];
        $imagePath = 'products/a3F11DGsqXBBvrNp7StgfiqNGYoJ8Dxi9e6ICry6.jpg';
        
        $products = [];
        $count = 0;
        
        while ($count < 100) {
            foreach ($categories as $slug => $category) {
                if (!isset($productTemplates[$slug])) continue;
                
                $templates = $productTemplates[$slug];
                $template = $templates[array_rand($templates)];
                
                $variation = $variations[array_rand($variations)];
                $productName = $template[0] . ' ' . $variation;
                
                // Random harga ±15%
                $basePrice = $template[1];
                $variationPercent = rand(-15, 15) / 100;
                $price = round($basePrice * (1 + $variationPercent) / 500) * 500;
                
                // Untuk snack, beri stock random, untuk lainnya null
                $stock = null;
                
                $products[] = [
                    'category_id' => $category->id,
                    'name' => $productName,
                    'price' => $price,
                    'stock' => $stock,
                    'image' => $imagePath,
                    'description' => 'Deskripsi untuk ' . $productName,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                $count++;
                if ($count >= 100) break 2;
            }
        }
        
        Product::insert($products);
        $this->command->info("✅ Berhasil menambahkan {$count} produk dummy dengan gambar!");
    }
}