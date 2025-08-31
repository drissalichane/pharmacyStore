<?php
// Simple test script to verify the scraping functionality
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing Pharmacie de Garde scraping command...\n";

try {
    // Test the controller method
    $controller = new App\Http\Controllers\Admin\AdminLocationController();
    $request = new Illuminate\Http\Request();
    
    echo "Calling scrapePharmacieDeGarde method...\n";
    $response = $controller->scrapePharmacieDeGarde($request);
    
    echo "Response status: " . $response->getStatusCode() . "\n";
    $data = json_decode($response->getContent(), true);
    
    if ($data['success']) {
        echo "✅ SUCCESS: " . $data['message'] . "\n";
    } else {
        echo "❌ ERROR: " . $data['message'] . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ EXCEPTION: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "Test completed.\n";
