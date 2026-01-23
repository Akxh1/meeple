<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\StudentModulePerformance;

/**
 * ML Prediction Service
 * 
 * Handles communication with the X-Scaffold ML API for:
 * - Mastery level prediction
 * - SHAP explanation retrieval
 * 
 * API Endpoint: http://localhost:5000 (Flask)
 */
class MLPredictionService
{
    /**
     * ML API Base URL (configure in .env as ML_API_URL)
     */
    protected string $apiUrl;

    /**
     * Timeout for API requests in seconds
     */
    protected int $timeout;

    public function __construct()
    {
        $this->apiUrl = config('services.ml_api.url', 'http://localhost:5000');
        $this->timeout = config('services.ml_api.timeout', 10);
    }

    /**
     * Check if the ML API is available.
     */
    public function isAvailable(): bool
    {
        try {
            $response = Http::timeout(3)->get("{$this->apiUrl}/health");
            return $response->successful();
        } catch (\Exception $e) {
            Log::warning('ML API health check failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Get prediction for a StudentModulePerformance record.
     * 
     * @param StudentModulePerformance $performance
     * @return array|null Prediction result with SHAP explanation
     */
    public function predict(StudentModulePerformance $performance): ?array
    {
        $features = $this->extractFeatures($performance);
        
        try {
            $response = Http::timeout($this->timeout)
                ->post("{$this->apiUrl}/predict", $features);
            
            if ($response->successful()) {
                $result = $response->json();
                
                Log::info('ML Prediction successful', [
                    'student_id' => $performance->student_id,
                    'predicted_level' => $result['prediction']['mastery_level_name'] ?? 'unknown'
                ]);
                
                return $result;
            }
            
            Log::error('ML API prediction failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            
            return null;
            
        } catch (\Exception $e) {
            Log::error('ML API request failed', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Get prediction and update the performance record.
     * 
     * Called after Level Indicator Exam completion.
     * Updates learning_mastery_score with ML prediction and stores XAI data.
     * Supports multiple retakes - same row is updated.
     * 
     * @param StudentModulePerformance $performance
     * @return StudentModulePerformance Updated record
     */
    public function predictAndUpdate(StudentModulePerformance $performance): StudentModulePerformance
    {
        $result = $this->predict($performance);
        
        if ($result) {
            // Map ML level (0-3) to LMS score range for consistency
            $mlLevel = $result['prediction']['mastery_level'] ?? 1;
            $confidence = $result['prediction']['confidence'] ?? 0.5;
            
            // Convert ML level to LMS score (center of each range with confidence adjustment)
            // at_risk=0-35, developing=36-55, proficient=56-75, advanced=76-100
            $lmsRanges = [
                0 => [0, 35],    // at_risk
                1 => [36, 55],   // developing
                2 => [56, 75],   // proficient
                3 => [76, 100],  // advanced
            ];
            
            $range = $lmsRanges[$mlLevel] ?? [36, 55];
            $rangeCenter = ($range[0] + $range[1]) / 2;
            $rangeSize = ($range[1] - $range[0]) / 2;
            
            // Adjust within range based on confidence
            $performance->learning_mastery_score = round($rangeCenter + ($confidence - 0.5) * $rangeSize, 1);
            
            // Set mastery level name
            $levelNames = ['at_risk', 'developing', 'proficient', 'advanced'];
            $performance->mastery_level = $levelNames[$mlLevel] ?? 'developing';
            
            // Store confidence for reference
            $performance->ml_prediction_confidence = $confidence;
            
            // Store SHAP values if available
            if (isset($result['explanation']['contributions'])) {
                $performance->shap_values = $result['explanation']['contributions'];
            }
            
            // Store natural language explanation for LLM prompts
            if (isset($result['explanation']['natural_language'])) {
                $performance->xai_explanation = $result['explanation']['natural_language'];
            }
            
            // Store top factors for quick display
            if (isset($result['explanation']['top_positive'])) {
                $performance->top_positive_factors = implode(', ', $result['explanation']['top_positive']);
            }
            if (isset($result['explanation']['top_negative'])) {
                $performance->top_negative_factors = implode(', ', $result['explanation']['top_negative']);
            }
            
            $performance->save();
            
            Log::info('ML prediction saved to performance record', [
                'student_id' => $performance->student_id,
                'module_id' => $performance->module_id,
                'lms' => $performance->learning_mastery_score,
                'level' => $performance->mastery_level
            ]);
        }
        
        return $performance;
    }

    /**
     * Get batch predictions for multiple students.
     * 
     * @param array $performances Array of StudentModulePerformance records
     * @return array Results indexed by performance ID
     */
    public function batchPredict(array $performances): array
    {
        $students = [];
        foreach ($performances as $performance) {
            $features = $this->extractFeatures($performance);
            $features['student_id'] = $performance->student_id;
            $students[] = $features;
        }
        
        try {
            $response = Http::timeout($this->timeout * 2)
                ->post("{$this->apiUrl}/batch_predict", ['students' => $students]);
            
            if ($response->successful()) {
                return $response->json()['results'] ?? [];
            }
            
            return [];
            
        } catch (\Exception $e) {
            Log::error('ML API batch prediction failed', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Extract 11 ML features from a performance record.
     */
    protected function extractFeatures(StudentModulePerformance $performance): array
    {
        return [
            'score_percentage' => (float) $performance->score_percentage,
            'hard_question_accuracy' => (float) $performance->hard_question_accuracy,
            'hint_usage_percentage' => (float) $performance->hint_usage_percentage,
            'avg_confidence' => (float) $performance->avg_confidence,
            'answer_changes_rate' => (float) $performance->answer_changes_rate,
            'tab_switches_rate' => (float) $performance->tab_switches_rate,
            'avg_time_per_question' => (float) $performance->avg_time_per_question,
            'review_percentage' => (float) $performance->review_percentage,
            'avg_first_action_latency' => (float) $performance->avg_first_action_latency,
            'clicks_per_question' => (float) $performance->clicks_per_question,
            'performance_trend' => (float) $performance->performance_trend,
        ];
    }

    /**
     * Format SHAP values into natural language for LLM prompts.
     * 
     * @param array $shapValues SHAP contribution values
     * @return string Natural language explanation
     */
    public function formatShapExplanation(array $shapValues): string
    {
        $positiveFactors = [];
        $negativeFactors = [];
        
        foreach ($shapValues as $feature => $data) {
            $value = $data['value'] ?? 0;
            $formattedFeature = str_replace('_', ' ', $feature);
            
            if ($value > 0.05) {
                $positiveFactors[] = "{$formattedFeature} (+{$value:.3f})";
            } elseif ($value < -0.05) {
                $negativeFactors[] = "{$formattedFeature} ({$value:.3f})";
            }
        }
        
        $explanation = "Based on SHAP analysis: ";
        
        if (!empty($positiveFactors)) {
            $explanation .= "Positive factors include " . implode(', ', array_slice($positiveFactors, 0, 3)) . ". ";
        }
        
        if (!empty($negativeFactors)) {
            $explanation .= "Areas needing improvement: " . implode(', ', array_slice($negativeFactors, 0, 3)) . ".";
        }
        
        return $explanation;
    }

    /**
     * Map ML mastery level (0-3) to hint level (1-3).
     * 
     * Used for HintController to determine scaffolding intensity.
     * 
     * @param int $masteryLevel 0=at_risk, 1=developing, 2=proficient, 3=advanced
     * @return int Hint level 1=proficient, 2=developing, 3=struggling
     */
    public function masteryToHintLevel(int $masteryLevel): int
    {
        // Inverse mapping: lower mastery = higher scaffolding
        return match($masteryLevel) {
            3 => 1, // advanced → minimal hints (L1)
            2 => 1, // proficient → minimal hints (L1)
            1 => 2, // developing → moderate hints (L2)
            0 => 3, // at_risk → intensive hints (L3)
            default => 2
        };
    }
}
