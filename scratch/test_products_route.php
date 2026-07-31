<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $request = Illuminate\Http\Request::create('/admin/products', 'GET');
    $response = $app->handle($request);
    echo "STATUS CODE: " . $response->getStatusCode() . "\n";
    $content = $response->getContent();
    echo "CONTENT LENGTH: " . strlen($content) . " bytes\n";
    
    // Check if main content section exists or is empty
    if (strpos($content, '<main class="content-area">') !== false) {
        echo "Found <main class=\"content-area\">\n";
        $pos = strpos($content, '<main class="content-area">');
        echo substr($content, $pos, 500);
    } else {
        echo "NOT FOUND <main class=\"content-area\">\n";
    }
} catch (Throwable $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
