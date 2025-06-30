<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Services\VendorReviewService;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class ReviewController extends Controller
{
    public function __construct(protected VendorReviewService $vendorReviewService)
    {}

    public function index()
    {
        $reviews = $this->vendorReviewService->getReviewsForAuthenticatedStore(Auth::user());

        return view('vendor.reviews.index', compact('reviews'));
    }
}
