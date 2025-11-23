<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use App\Services\UserService;
use App\Services\ProductService;
use App\Services\VendorEarningService;
use App\Services\StoreService;

class DashboardController extends Controller
{
    protected $orderService;
    protected $userService;
    protected $productService;
    protected $vendorEarningService;
    protected $storeService;

    public function __construct(
        OrderService $orderService,
        UserService $userService,
        ProductService $productService,
        VendorEarningService $vendorEarningService,
        StoreService $storeService
    ) {
        $this->orderService = $orderService;
        $this->userService = $userService;
        $this->productService = $productService;
        $this->vendorEarningService = $vendorEarningService;
        $this->storeService = $storeService;
    }

    public function index()
    {
        $totalSales = $this->orderService->getTotalSales();
        $totalCommission = $this->vendorEarningService->getTotalCommission();
        $totalVendors = $this->userService->getTotalVendors();
        $totalCustomers = $this->userService->getTotalCustomers();
        $totalOrders = $this->orderService->getTotalOrders();
        $topVendors = $this->storeService->getTopVendors();
        $topProducts = $this->productService->getTopProducts();

        return view('admin.dashboard', compact(
            'totalSales',
            'totalCommission',
            'totalVendors',
            'totalCustomers',
            'totalOrders',
            'topVendors',
            'topProducts'
        ));
    }
}
