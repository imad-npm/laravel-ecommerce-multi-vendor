<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class AssignCategoryToExistingProductsSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::pluck('id')->all();
        Product::whereNull('category_id')->get()->each(function ($product) use ($categories) {
            $product->category_id = fake()->randomElement($categories);
            $product->save();
        });
    }
}
