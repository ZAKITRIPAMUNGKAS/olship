<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Log in as Super Admin
$admin = \App\Models\User::where('email', 'admin@listrindojaya.com')->first();
if ($admin) {
    \Illuminate\Support\Facades\Auth::login($admin);
    echo "Logged in as: " . $admin->name . " (Roles: " . implode(',', $admin->getRoleNames()->toArray()) . ")\n";
}

try {
    $request = Illuminate\Http\Request::create('/admin/products', 'GET');
    $response = $app->handle($request);
    echo "STATUS CODE: " . $response->getStatusCode() . "\n";
    $content = $response->getContent();
    echo "CONTENT LENGTH: " . strlen($content) . " bytes\n";
    echo "CONTAIN TABLE: " . (strpos($content, '<table') !== false ? 'YES' : 'NO') . "\n";
} catch (Throwable $e) {
    echo "EXCEPTION THROWN: " . get_class($e) . " - " . $e->getMessage() . "\n";
}
