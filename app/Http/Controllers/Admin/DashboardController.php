<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use App\Models\Review;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(ReportService $report)
    {
        $stats = [
            'revenue' => [
                'today'     => $report->revenueToday(),
                'yesterday' => $report->revenueYesterday(),
            ],
            'orders' => [
                'today'     => $report->ordersToday(),
                'by_status' => $report->ordersByStatus(),
            ],
            'users' => [
                'today' => $report->newUsersToday(),
                'total' => $report->totalUsers(),
            ],
            'products' => [
                'low_stock' => $report->lowStockCount(),
                'active'    => $report->activeProductsCount(),
            ],
        ];

        $recentOrders  = $report->recentOrders(8);
        $lowStockItems = $report->lowStockProducts(5);
        $recentReviews = $report->pendingReviews(4);
        $revenueChart  = $report->revenueChart(30);
        $categoryChart = $report->salesByCategory(5);

        return view('admin.dashboard.index', compact(
            'stats', 'recentOrders', 'lowStockItems',
            'recentReviews', 'revenueChart', 'categoryChart'
        ));
    }
}
