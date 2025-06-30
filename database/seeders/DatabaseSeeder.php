<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create a few admin and vendor users
        $this->call([
            AdminUserSeeder::class,
            CustomerUserSeeder::class,
            VendorUserSeeder::class,
        ]);

        // Create a large number of stores, products, orders, and reviews
        $this->call(StoreSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(OrderSeeder::class);
        $this->call(ReviewSeeder::class);

        $this->call([
            AssignCategoryToExistingProductsSeeder::class,
        ]);
    }
}
