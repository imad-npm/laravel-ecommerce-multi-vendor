<?php

namespace Database\Seeders;

use App\Models\ShippingAddress;
use App\Models\User;
use Illuminate\Database\Seeder;

class ShippingAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role','customer')->get();

        // Create some addresses for existing users
        foreach ($users as $user) {
            ShippingAddress::factory()->create([
                'user_id' => $user->id,
            ]);
        }

        // Create some generic addresses without a user_id (if applicable for your app)
        ShippingAddress::factory()->count(5)->create([
            'user_id' => null,
        ]);
    }
}