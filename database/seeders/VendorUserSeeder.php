<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;

class VendorUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        $vendors = User::factory()->count(5)->create(['role' => 'vendor']);

        foreach ($vendors as $vendor) {
          


            $vendor->stripe_account_id = "acct_1STUsnGmM33FGADJ";
            $vendor->save();
        }
    }
}
