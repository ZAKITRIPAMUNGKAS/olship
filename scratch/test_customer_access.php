<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Log in as Customer
$customer = \App\Models\User::where('email', 'user@listrindojaya.com')->first();
if ($customer) {
    \Illuminate\Support\Facades\Auth::login($customer);
    echo "Logged in as: " . $customer->name . " (Roles: " . implode(',', $customer->getRoleNames()->toArray()) . ")\n";
}

try {
    $request = Illuminate\Http\Request::create('/admin/products', 'GET');
    $response = $app->handle($request);
    echo "STATUS CODE: " . $response->getStatusCode() . "\n";
    echo "CONTENT (first 500 chars):\n" . substr($response->getContent(), 0, 500) . "\n";
} catch (Throwable $e) {
    echo "EXCEPTION THROWN: " . get_class($e) . " - " . $e->getMessage() . "\n";
}
