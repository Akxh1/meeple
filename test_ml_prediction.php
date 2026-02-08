<?php
/**
 * Backend ML API Test Script
 * Tests the full prediction pipeline without touching the database
 * Run with: php test_ml_prediction.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Http;

echo "=== X-SCAFFOLD ML API FULL TEST ===\n\n";

$apiUrl = config('services.ml_api.url', 'http://localhost:5500');
echo "API URL: {$apiUrl}\n\n";

// 1. Health Check
echo "1️⃣  HEALTH CHECK\n";
echo str_repeat("-", 40) . "\n";
try {
    $healthResponse = Http::timeout(5)->get("{$apiUrl}/health");
    if ($healthResponse->successful()) {
        $health = $healthResponse->json();
        echo "✅ Status: {$health['status']}\n";
        echo "✅ Model: {$health['model']}\n";
        echo "✅ Features: {$health['features_count']}\n";
        echo "✅ Classes: " . implode(', ', $health['classes']) . "\n";
        echo "✅ SHAP Available: " . ($health['shap_available'] ? 'Yes' : 'No') . "\n";
    } else {
        echo "❌ Health check failed: HTTP " . $healthResponse->status() . "\n";
        exit(1);
    }
} catch (\Exception $e) {
    echo "❌ Health check error: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n";

// 2. Prediction Test with Sample Data
echo "2️⃣  PREDICTION TEST (Sample Student Data)\n";
echo str_repeat("-", 40) . "\n";

// Sample student performance data (11 features)
$sampleData = [
    'score_percentage' => 72.5,
    'hard_question_accuracy' => 65.0,
    'hint_usage_percentage' => 20.0,
    'avg_confidence' => 3.8,
    'answer_changes_rate' => 0.3,
    'tab_switches_rate' => 0.8,
    'avg_time_per_question' => 85.0,
    'review_percentage' => 40.0,
    'avg_first_action_latency' => 4.5,
    'clicks_per_question' => 4.2,
    'performance_trend' => 8.0
];

echo "Input Features:\n";
foreach ($sampleData as $feature => $value) {
    echo "  • {$feature}: {$value}\n";
}
echo "\n";

try {
    $predictResponse = Http::timeout(30)->post("{$apiUrl}/predict", $sampleData);
    
    if ($predictResponse->successful()) {
        $result = $predictResponse->json();
        
        echo "✅ PREDICTION RESULT\n";
        echo "  • Mastery Level: {$result['prediction']['mastery_level']} ({$result['prediction']['mastery_level_name']})\n";
        echo "  • Confidence: " . round($result['prediction']['confidence'] * 100, 1) . "%\n";
        echo "\n  Probabilities:\n";
        foreach ($result['prediction']['probabilities'] as $level => $prob) {
            $bar = str_repeat("█", (int)($prob * 20));
            echo "    {$level}: " . str_pad($bar, 20) . " " . round($prob * 100, 1) . "%\n";
        }
        
        if (isset($result['explanation'])) {
            echo "\n✅ SHAP EXPLANATION\n";
            if (isset($result['explanation']['top_positive'])) {
                echo "  Strengths: " . implode(', ', $result['explanation']['top_positive']) . "\n";
            }
            if (isset($result['explanation']['top_negative'])) {
                echo "  Areas to Improve: " . implode(', ', $result['explanation']['top_negative']) . "\n";
            }
            if (isset($result['explanation']['natural_language'])) {
                echo "\n  Summary: {$result['explanation']['natural_language']}\n";
            }
        }
        
        echo "\n" . str_repeat("=", 50) . "\n";
        echo "🎉 SUCCESS! The Flask API (Full ML Model) is working!\n";
        echo "   Predictions will show: 🟢 Flask API\n";
        echo str_repeat("=", 50) . "\n";
        
    } else {
        echo "❌ Prediction failed: HTTP " . $predictResponse->status() . "\n";
        echo "   Response: " . $predictResponse->body() . "\n";
    }
} catch (\Exception $e) {
    echo "❌ Prediction error: " . $e->getMessage() . "\n";
}
