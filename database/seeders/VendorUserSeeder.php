<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class VendorUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test Vendor',
            'email' => 'vendor@example.com',
            'role' => 'vendor',
        ]);

        User::factory()->count(50)->create([
            'role' => 'vendor',
        ]);
    }
}
