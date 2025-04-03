<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        Menu::create([
            'kategori_id' => 1,
            'nama' => 'Ayam Goreng Original',
            'deskripsi' => 'Ayam goreng renyah dengan bumbu rahasia yang meresap sampai ke tulang.',
            'harga' => 25000,
            'gambar' => 'https://images.unsplash.com/photo-1626645738196-c2a7c87a8f58?ixlib=rb-4.0.3',
            'badge' => 'Bestseller',
            'badge_type' => 'normal'
        ]);

        Menu::create([
            'kategori_id' => 1,
            'nama' => 'Ayam Goreng Pedas',
            'deskripsi' => 'Ayam goreng dengan balutan bumbu pedas yang menggugah selera.',
            'harga' => 27000,
            'gambar' => 'https://images.unsplash.com/photo-1562967914-608f82629710?ixlib=rb-4.0.3',
            'badge' => 'Pedas',
            'badge_type' => 'hot'
        ]);

        Menu::create([
            'kategori_id' => 2,
            'nama' => 'Ayam Bakar Madu',
            'deskripsi' => 'Ayam bakar dengan olesan madu spesial yang manis dan gurih.',
            'harga' => 28000,
            'gambar' => 'https://images.unsplash.com/photo-1432139509613-5c4255815697',
            'badge' => 'New',
            'badge_type' => 'new'
        ]);
    }
}


