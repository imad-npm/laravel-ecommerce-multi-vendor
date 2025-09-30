<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Services\PayoutService;
use Illuminate\Http\Request;

class PayoutController extends Controller
{
    protected $payoutService;

    public function __construct(PayoutService $payoutService)
    {
        $this->payoutService = $payoutService;
    }

    public function index()
    {
        $vendor = auth()->user();
        $earnings = $vendor->vendorEarnings()->latest()->paginate(10);
        $unpaidEarnings = $vendor->vendorEarnings()->where('is_paid', false)->sum('net_earnings');
        return view('vendor.payouts.index', compact('payouts', 'unpaidEarnings'));
    }

    
}