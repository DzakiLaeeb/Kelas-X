<?php

namespace Database\Seeders;

use App\Models\Pelanggan;
use Illuminate\Database\Seeder;

class PelangganSeeder extends Seeder
{
    public function run(): void
    {
        Pelanggan::factory()->count(10)->create(); // Buat 10 data pelanggan untuk testing
    }
}

