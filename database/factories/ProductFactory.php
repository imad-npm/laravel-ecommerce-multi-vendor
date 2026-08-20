<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'store_id' => Store::inRandomOrder()->first()->id ?? Store::factory(),
            "category_id" => Category::inRandomOrder()->first()->id, // Pas de catégorie par défaut
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 5, 500), // entre 5.00 et 500.00
            'stock' => $this->faker->numberBetween(0, 100),
            'image' => "products/random_img.jpg", // faux lien image
        ];
    }
}
