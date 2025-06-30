<?php

namespace Database\Factories;

use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoreFactory extends Factory
{
    protected $model = Store::class;

    public function definition(): array
    {
        // Cherche un vendeur qui n'a pas encore de store
        $user = User::where('role', 'vendor')
                    ->whereDoesntHave('store') // relation store non existante
                    ->inRandomOrder()
                    ->first();

        // Si aucun vendeur libre n'existe, en crée un
        if (!$user) {
            $user = User::factory()->create(['role' => 'vendor']);
        }

        return [
            'user_id' => $user->id,
            'name' => $this->faker->company,
            'description' => $this->faker->paragraph,
            'logo' => $this->faker->imageUrl(200, 200, 'business', true, 'store'),
        ];
    }
}
