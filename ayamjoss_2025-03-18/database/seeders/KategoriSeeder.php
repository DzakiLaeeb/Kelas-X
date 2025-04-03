<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            ['nama' => 'Ayam Goreng', 'slug' => 'ayam-goreng'],
            ['nama' => 'Ayam Bakar', 'slug' => 'ayam-bakar'],
            ['nama' => 'Minuman', 'slug' => 'minuman'],
        ];

        foreach ($kategoris as $kategori) {
            Kategori::create($kategori);
        }
    }
}


