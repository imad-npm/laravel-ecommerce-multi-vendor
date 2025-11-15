<?php

namespace App\Services;


use App\DataTransferObjects\VendorEarning\VendorEarningData;
use App\Models\VendorEarning;
use Illuminate\Pagination\LengthAwarePaginator;

class VendorEarningService
{
    public function getAllVendorEarnings(): LengthAwarePaginator
    {
        return VendorEarning::with(['vendor', 'order'])->latest()->paginate(10);
    }

    public function updateVendorEarning(VendorEarning $vendorEarning, VendorEarningData $vendorEarningData): bool
    {
        return $vendorEarning->update($vendorEarningData->all());
    }

    public function getEligibleVendorEarningsForPayout()
    {
        return VendorEarning::where('is_paid', false)
            ->whereNull('payout_id')
            ->with('vendor')
            ->get();
    }
}