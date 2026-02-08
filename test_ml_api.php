<?php
// Quick test script for Flask ML API
// Run with: php test_ml_api.php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$ml = new \App\Services\MLPredictionService();

echo "=== ML API Health Check ===\n\n";

// Check if API is available
$available = $ml->isAvailable();
echo "API Status: " . ($available ? "✅ Flask API is RUNNING!" : "❌ Flask API is NOT available (fallback will be used)") . "\n\n";

if ($available) {
    echo "The prediction would use: flask_api (Full ML model + PKL files)\n";
} else {
    echo "The prediction would use: local_fallback (LMS formula)\n";
    echo "\nTo start the Flask API, run:\n";
    echo "  cd ml_model && python app.py\n";
}
