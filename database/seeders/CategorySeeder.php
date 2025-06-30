<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Electronics', 'Fashion', 'Home', 'Beauty', 'Toys', 'Books', 'Sports', 'Grocery', 'Automotive', 'Garden'
        ];
        foreach ($categories as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}
