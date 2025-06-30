<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CustomerUserSeeder extends Seeder
{
    public function run(): void
    {
        // Example: 5 customers
        User::factory()->count(100)->create([
            'role' => 'customer',
        ]);
  
       
    }
}
