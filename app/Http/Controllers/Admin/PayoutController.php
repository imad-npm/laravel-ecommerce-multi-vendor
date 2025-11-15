<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\DataTransferObjects\Payout\PayoutData;
use App\Http\Requests\Admin\StorePayoutRequest;
use App\Http\Requests\Admin\UpdatePayoutRequest;
use App\Jobs\DispatchVendorPayouts;
use App\Models\Payout;
use App\Services\PayoutService;
use App\Services\UserService;
use Illuminate\Http\Request;

class PayoutController extends Controller
{
    public function __construct(
        protected PayoutService $payoutService,
        protected UserService $userService
    ) {
    }

    public function index()
    {
        $payouts = $this->payoutService->getAllPayouts();
        return view('admin.payouts.index', compact('payouts'));
    }

    

    public function edit(Payout $payout)
    {
        $vendors = $this->userService->getAllVendors();
        return view('admin.payouts.edit', compact('payout', 'vendors'));
    }

    public function update(UpdatePayoutRequest $request, Payout $payout)
    {
        $payoutData = PayoutData::from($request->validated());
        $this->payoutService->updatePayout($payout, $payoutData);

        return redirect()->route('admin.payouts.index')->with('success', 'Payout updated successfully.');
    }

    public function show(Payout $payout)
    {
        return view('admin.payouts.show', compact('payout'));
    }

    public function destroy(Payout $payout)
    {
        $this->payoutService->deletePayout($payout);

        return redirect()->route('admin.payouts.index')->with('success', 'Payout deleted successfully.');
    }

    public function payAll()
    {
        // Dispatch the job to handle payouts for all vendors
DispatchVendorPayouts::dispatchSync();

        return redirect()->route('admin.payouts.index')->with('success', 'Payouts for all vendors initiated successfully.');
    }
}
