<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::with(['items.product', 'customer'])->get();

        $count = 0;

        foreach ($orders as $order) {
            $user = $order->customer; // Corrected: use customer relationship

            foreach ($order->items as $item) {
                $product = $item->product;

                // Skip if review already exists for this user and product
                if ($user && $user->reviews()->where('product_id', $product->id)->exists()) {
                    continue;
                }

                // Create review using factory
                Review::factory()->create([
                    'user_id'    => $user->id,
                    'product_id' => $product->id,
                    'stars'      => rand(3, 5), // Avis réalistes
                    'comment'    => fake()->optional()->sentence(),
                ]);

                $count++;
            }
        }

        $this->command->info("✅ $count reviews inserted.");
    }
}
