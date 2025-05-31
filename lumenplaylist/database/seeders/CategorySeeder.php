<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Create 100 category records
        for ($i = 0; $i < 100; $i++) {
            $data = [
                'kategori' => $faker->unique()->word . ' ' . $faker->word, // Generate unique category names
                'keterangan' => $faker->sentence(4) // Generate a description
            ];

            Category::create($data);
        }
    }
}
