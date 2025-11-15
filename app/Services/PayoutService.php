<?php

namespace App\Services;

use App\DataTransferObjects\Payout\PayoutDTO;
use App\Models\Payout;
use Illuminate\Pagination\LengthAwarePaginator;

class PayoutService
{
    public function getAllPayouts(): LengthAwarePaginator
    {
        return Payout::with('vendor')->latest()->paginate(10);
    }

    public function createPayout(PayoutDTO $payoutData): Payout
    {
        return Payout::create($payoutData->toArray());
    }

    public function updatePayout(Payout $payout, PayoutDTO $payoutData): bool
    {
        return $payout->update($payoutData->toArray());
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