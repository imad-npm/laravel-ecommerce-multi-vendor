<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\DataTransferObjects\VendorEarning\VendorEarningDTO;
use App\Http\Requests\VendorEarning\UpdateVendorEarningRequest;
use App\Models\VendorEarning;
use App\Services\PayoutService;
use App\Services\VendorEarningService;
use Illuminate\Http\Request;

class VendorEarningController extends Controller
{
    public function __construct(
        protected VendorEarningService $vendorEarningService,
        protected PayoutService $payoutService
    ) {
    }

    public function index()
    {
        $vendorEarnings = $this->vendorEarningService->getAllVendorEarnings();
        return view('admin.vendor-earnings.index', compact('vendorEarnings'));
    }

    public function edit(VendorEarning $vendorEarning)
    {
        $payouts = $this->payoutService->getAll();
        return view('admin.vendor-earnings.edit', compact('vendorEarning', 'payouts'));
    }

    public function update(UpdateVendorEarningRequest $request, VendorEarning $vendorEarning)
    {
        $vendorEarningData = VendorEarningDTO::fromArray($request->validated());
        $this->vendorEarningService->updateVendorEarning($vendorEarning, $vendorEarningData);

        return redirect()->route('admin.vendor-earnings.index')->with('success', 'Vendor earning updated successfully.');
    }
}