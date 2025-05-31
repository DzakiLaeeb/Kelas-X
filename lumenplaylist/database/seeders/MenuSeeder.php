<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Get all category IDs
        $categoryIds = \App\Models\Category::pluck('id_kategori')->toArray();

        // Create random menu items
        for ($i = 0; $i < 50; $i++) {
            Menu::create([
                'menu' => $faker->words(3, true),
                'id_kategori' => $faker->randomElement($categoryIds),
                'harga' => $faker->numberBetween(10000, 100000),
                'deskripsi' => $faker->paragraph(2),
                'tersedia' => $faker->boolean(90)
            ]);
        }

        // Create fixed menu items for testing
        $fixedMenus = [
            [
                'menu' => 'Nasi Goreng Special',
                'id_kategori' => 1,
                'harga' => 35000,
                'deskripsi' => 'Nasi goreng dengan telur, ayam, dan udang',
                'tersedia' => true
            ],
            [
                'menu' => 'Mie Goreng Seafood',
                'id_kategori' => 1,
                'harga' => 40000,
                'deskripsi' => 'Mie goreng dengan campuran seafood segar',
                'tersedia' => true
            ],
            [
                'menu' => 'Es Teh Manis',
                'id_kategori' => 2,
                'harga' => 8000,
                'deskripsi' => 'Teh manis dingin yang menyegarkan',
                'tersedia' => true
            ],
            [
                'menu' => 'Juice Alpukat',
                'id_kategori' => 2,
                'harga' => 15000,
                'deskripsi' => 'Jus alpukat segar dengan susu',
                'tersedia' => true
            ]
        ];

        foreach ($fixedMenus as $menu) {
            Menu::create($menu);
        }
    }
}
