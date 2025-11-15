<?php

namespace App\Services;

use App\DataTransferObjects\Payout\PayoutData;
use App\Models\Payout;
use Illuminate\Pagination\LengthAwarePaginator;

class PayoutService
{
    public function getAllPayouts(): LengthAwarePaginator
    {
        return Payout::with('vendor')->latest()->paginate(10);
    }

    public function createPayout(PayoutData $payoutData): Payout
    {
        return Payout::create($payoutData->all());
    }

    public function updatePayout(Payout $payout, PayoutData $payoutData): bool
    {
        return $payout->update($payoutData->all());
    }

    public function deletePayout(Payout $payout): bool
    {
        return $payout->delete();
    }

    public function getAll()
    {
        return Payout::all();
    }
}