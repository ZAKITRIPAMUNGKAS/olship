<?php

namespace App\Services;

use App\Models\{Order, OrderItem, Product, User, Review};
use Illuminate\Support\Facades\DB;

class ReportService
{
    // ── Dashboard KPIs ───────────────────────────────────────────────

    public function revenueToday(): float
    {
        return (float) Order::where('payment_status', 'paid')
            ->whereDate('paid_at', today())
            ->sum('total_amount');
    }

    public function revenueYesterday(): float
    {
        return (float) Order::where('payment_status', 'paid')
            ->whereDate('paid_at', today()->subDay())
            ->sum('total_amount');
    }

    public function ordersToday(): int
    {
        return Order::whereDate('created_at', today())->count();
    }

    public function ordersByStatus(): array
    {
        return Order::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }

    public function newUsersToday(): int
    {
        return User::whereDate('created_at', today())->count();
    }

    public function totalUsers(): int
    {
        return User::count();
    }

    public function lowStockCount(int $threshold = 5): int
    {
        return Product::where('is_active', true)->where('stock', '<=', $threshold)->count();
    }

    public function activeProductsCount(): int
    {
        return Product::where('is_active', true)->count();
    }

    // ── Dashboard Lists ───────────────────────────────────────────────

    public function recentOrders(int $limit = 8)
    {
        return Order::with('user')->latest()->take($limit)->get();
    }

    public function lowStockProducts(int $limit = 5, int $threshold = 10)
    {
        return Product::with('category')
            ->where('is_active', true)
            ->where('stock', '<=', $threshold)
            ->orderBy('stock')
            ->take($limit)
            ->get();
    }

    public function pendingReviews(int $limit = 5)
    {
        return Review::with(['user', 'product'])
            ->where('status', 'pending')
            ->latest()
            ->take($limit)
            ->get();
    }

    // ── Charts ───────────────────────────────────────────────────────

    /**
     * Revenue chart data — last N days.
     * Alias for revenueLastDays() for backward compat.
     */
    public function revenueChart(int $days = 30): array
    {
        return $this->revenueLastDays($days);
    }

    /** Revenue for the last N days, filling gaps with 0 */
    public function revenueLastDays(int $days = 30): array
    {
        $data = Order::where('payment_status', 'paid')
            ->whereDate('paid_at', '>=', now()->subDays($days))
            ->selectRaw('DATE(paid_at) as date, SUM(total_amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $result = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date     = now()->subDays($i)->format('Y-m-d');
            $result[] = ['date' => $date, 'total' => (float) ($data[$date] ?? 0)];
        }
        return $result;
    }

    /** Sales (revenue) grouped by top categories */
    public function salesByCategory(int $limit = 5): array
    {
        return OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->selectRaw('categories.name, SUM(order_items.price * order_items.quantity) as total')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /** Top-selling products */
    public function topProducts(int $limit = 10, ?string $startDate = null, ?string $endDate = null): array
    {
        $query = OrderItem::with('product')
            ->selectRaw('product_id, SUM(quantity) as qty_sold, SUM(price * quantity) as revenue')
            ->groupBy('product_id')
            ->orderByDesc('qty_sold')
            ->limit($limit);

        if ($startDate) $query->whereDate('created_at', '>=', $startDate);
        if ($endDate)   $query->whereDate('created_at', '<=', $endDate);

        return $query->get()->toArray();
    }

    /** Revenue grouped by day/week/month */
    public function revenueByRange(string $start, string $end, string $groupBy = 'day'): array
    {
        $format = match($groupBy) {
            'month' => '%Y-%m',
            'week'  => '%x-W%v',
            default => '%Y-%m-%d',
        };

        return Order::where('payment_status', 'paid')
            ->whereBetween('paid_at', [$start, $end])
            ->selectRaw("DATE_FORMAT(paid_at, '{$format}') as period,
                         SUM(total_amount) as revenue,
                         COUNT(*) as order_count")
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->toArray();
    }
}
