<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use App\Models\OrderItem;
use App\Models\Product;

class ReportController extends Controller
{
    public function revenue(ReportService $report)
    {
        $revenueChart  = $report->revenueChart(30);
        $categoryChart = $report->salesByCategory(5);
        $topProducts   = collect($report->topProducts(10))->map(function($item) {
            $product = \App\Models\Product::find($item['product_id']);
            if ($product) {
                $product->total_sold    = $item['qty_sold'];
                $product->total_revenue = $item['revenue'];
            }
            return $product;
        })->filter();

        $reportData = [
            'month_revenue' => \App\Models\Order::where('payment_status', 'paid')
                ->whereMonth('paid_at', now()->month)->sum('total_amount'),
            'month_orders'  => \App\Models\Order::where('payment_status', 'paid')
                ->whereMonth('paid_at', now()->month)->count(),
            'avg_order'     => \App\Models\Order::where('payment_status', 'paid')
                ->whereMonth('paid_at', now()->month)->avg('total_amount') ?? 0,
            'items_sold'    => \App\Models\OrderItem::whereHas('order', fn($q) => $q->where('payment_status', 'paid'))
                ->whereMonth('created_at', now()->month)->sum('quantity'),
        ];

        return view('admin.reports.revenue', compact('revenueChart', 'categoryChart', 'topProducts', 'reportData'));
    }

    public function products(ReportService $report)
    {
        $topProductsRaw = $report->topProducts(20);
        $topProducts = collect($topProductsRaw)->map(function($item) {
            $product = \App\Models\Product::with('category')->find($item['product_id']);
            if ($product) {
                $product->total_sold    = $item['qty_sold'];
                $product->total_revenue = $item['revenue'];
            }
            return $product;
        })->filter();

        return view('admin.reports.products', compact('topProducts'));
    }

    public function export(ReportService $report)
    {
        $data = $report->revenueChart(30);
        $csv  = "Tanggal,Revenue\n";
        foreach ($data as $row) {
            $csv .= "{$row['date']},{$row['total']}\n";
        }

        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="revenue_'.now()->format('Y-m-d').'.csv"',
        ]);
    }
}
