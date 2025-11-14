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
        $stripe = new StripeClient(config('services.stripe.secret'));

        $vendors = User::factory()->count(5)->create(['role' => 'vendor']);

        foreach ($vendors as $vendor) {
            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));

            $account = $stripe->accounts->create([
                'type' => 'express',
                'country' => 'US',
                'email' => $vendor->email,
                'capabilities' => [
                    'card_payments' => ['requested' => true],
                    'transfers' => ['requested' => true],
                ],
            ]);

            // ✅ Correct: testHelpers accounts update
            $stripe->testHelpers->accounts->updateCapability(
                $account->id,
                'transfers',
                ['requested' => true, 'enabled' => true]
            );

            $vendor->stripe_account_id = $account->id;
            $vendor->save();
        }
    }
}
