<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $user = User::inRandomOrder()->first();
        $product = Product::inRandomOrder()->first();

        return [
            'user_id' => $user ? $user->id : User::factory(),
            'total' => $this->faker->randomFloat(2, 10, 1000),
            'status' => $this->faker->randomElement(['pending', 'paid', 'shipped', 'cancelled']),
            'shipping_address_id' => \App\Models\ShippingAddress::inRandomOrder()->first()->id,
            'payment_method' => $this->faker->randomElement(['credit_card', 'paypal', 'bank_transfer']),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Order $order) {
            // Attach a random product to the order as an order item
            $product = Product::inRandomOrder()->first();
            if ($product) {
                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $this->faker->numberBetween(1, 5),
                    'price' => $product->price,
                ]);
            }
        });
    }
}
