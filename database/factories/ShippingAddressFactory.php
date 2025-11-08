<?php

namespace Database\Factories;

use App\Models\ShippingAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShippingAddressFactory extends Factory
{
    protected $model = ShippingAddress::class;

    public function definition(): array
    {
        return [
            'address_line_1' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
                        'postal_code' => $this->faker->postcode(),
            'shipping_country' => $this->faker->country(),
            'user_id' => \App\Models\User::inRandomOrder()->first()->id ?? null, // Assign to a random user, or null
        ];
    }
}