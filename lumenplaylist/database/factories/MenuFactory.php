<?php

namespace Database\Factories;

use App\Models\Menu;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class MenuFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Menu::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = Category::pluck('id_kategori')->toArray();
        
        return [
            'menu' => $this->faker->unique()->words(3, true),
            'id_kategori' => $this->faker->randomElement($categories),
            'harga' => $this->faker->numberBetween(10000, 200000),
            'deskripsi' => $this->faker->paragraph(2),
            'gambar' => null, // Will be filled when actual image is uploaded
            'tersedia' => $this->faker->boolean(80),
        ];
    }
}
