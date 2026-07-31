<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Log in as Super Admin
$admin = \App\Models\User::where('email', 'admin@listrindojaya.com')->first();
if ($admin) {
    \Illuminate\Support\Facades\Auth::login($admin);
}

$routes = [
    '/admin/dashboard',
    '/admin/products',
    '/admin/products/create',
    '/admin/categories',
    '/admin/brands',
    '/admin/orders',
    '/admin/users',
    '/admin/flash-sales',
    '/admin/coupons',
    '/admin/reviews',
    '/admin/discussions',
    '/admin/banners',
    '/admin/reports/revenue',
    '/admin/settings',
    '/admin/audit-logs',
    '/admin/failed-sync-logs',
];

echo "===========================================================\n";
echo "      COMPREHENSIVE ADMIN ROUTES SYSTEM HEALTH CHECK      \n";
echo "===========================================================\n";

$passCount = 0;
$failCount = 0;

foreach ($routes as $path) {
    try {
        $request = Illuminate\Http\Request::create($path, 'GET');
        $response = $app->handle($request);
        $status = $response->getStatusCode();
        
        if ($status === 200) {
            echo "[PASS 200 OK] " . str_pad($path, 30) . " (Length: " . strlen($response->getContent()) . " bytes)\n";
            $passCount++;
        } else {
            echo "[FAIL $status] " . str_pad($path, 30) . "\n";
            $failCount++;
        }
    } catch (Throwable $e) {
        echo "[ERROR EXCEPTION] " . str_pad($path, 30) . " -> " . $e->getMessage() . "\n";
        $failCount++;
    }
}

echo "===========================================================\n";
echo "SUMMARY: " . $passCount . " PASSED, " . $failCount . " FAILED\n";
echo "===========================================================\n";
