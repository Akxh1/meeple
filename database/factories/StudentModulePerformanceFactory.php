<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\Module;
use App\Models\StudentModulePerformance;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory for generating realistic mock exam performance data.
 */
class StudentModulePerformanceFactory extends Factory
{
    protected $model = StudentModulePerformance::class;

    /**
     * Define the model's default state with realistic correlated values.
     */
    public function definition(): array
    {
        // Generate a base performance tier to create realistic correlations
        $performanceTier = $this->faker->randomElement(['high', 'medium', 'low']);
        
        // Set base values based on tier
        switch ($performanceTier) {
            case 'high':
                $baseScore = $this->faker->randomFloat(2, 75, 100);
                $baseConfidence = $this->faker->randomFloat(2, 3.5, 5.0);
                $hintUsage = $this->faker->randomFloat(2, 0, 15);
                break;
            case 'medium':
                $baseScore = $this->faker->randomFloat(2, 50, 75);
                $baseConfidence = $this->faker->randomFloat(2, 2.5, 4.0);
                $hintUsage = $this->faker->randomFloat(2, 15, 40);
                break;
            default: // low
                $baseScore = $this->faker->randomFloat(2, 20, 50);
                $baseConfidence = $this->faker->randomFloat(2, 1.5, 3.0);
                $hintUsage = $this->faker->randomFloat(2, 40, 80);
        }

        // Calculate derived features with realistic correlations
        $hardQuestionAccuracy = max(0, min(100, $baseScore - $this->faker->randomFloat(2, 5, 20)));
        $answerChangesRate = $this->faker->randomFloat(4, 0.0, 0.5);
        $tabSwitchesRate = $this->faker->randomFloat(4, 0.0, 0.8);
        
        // Tier 2 features
        $avgTimePerQuestion = $this->faker->randomFloat(2, 15, 120); // 15s to 2min
        $reviewPercentage = $this->faker->randomFloat(2, 0, 50);
        $firstActionLatency = $this->faker->randomFloat(2, 1, 15); // 1-15 seconds
        $clicksPerQuestion = $this->faker->randomFloat(2, 1, 8);
        $performanceTrend = $this->faker->randomFloat(2, -20, 20); // 1st half vs 2nd half difference

        // Calculate LMS using the formula
        $lms = $this->calculateLMS(
            $baseScore, 
            $hardQuestionAccuracy, 
            $hintUsage, 
            $baseConfidence, 
            $answerChangesRate, 
            $tabSwitchesRate
        );

        return [
            // student_id and module_id are passed explicitly via seeder
            
            // Tier 1: Core 6 Features
            'score_percentage' => $baseScore,
            'hard_question_accuracy' => $hardQuestionAccuracy,
            'hint_usage_percentage' => $hintUsage,
            'avg_confidence' => $baseConfidence,
            'answer_changes_rate' => $answerChangesRate,
            'tab_switches_rate' => $tabSwitchesRate,
            
            // Tier 2: 5 ML Predictor Features
            'avg_time_per_question' => $avgTimePerQuestion,
            'review_percentage' => $reviewPercentage,
            'avg_first_action_latency' => $firstActionLatency,
            'clicks_per_question' => $clicksPerQuestion,
            'performance_trend' => $performanceTrend,
            
            // Calculated Outputs
            'learning_mastery_score' => $lms,
            'mastery_level' => $this->classifyMasteryLevel($lms),
        ];
    }

    /**
     * Calculate LMS using the research-backed formula.
     */
    private function calculateLMS(
        float $score, 
        float $hardAccuracy, 
        float $hintUsage, 
        float $confidence, 
        float $answerChanges, 
        float $tabSwitches
    ): float {
        $Hu = $hintUsage / 100;
        
        // Calibration bonus
        $expectedConfidence = $score / 20;
        $Ccal = max(0, 1 - (abs($confidence - $expectedConfidence) / 5));
        
        // Stability bonus
        $Ks = max(0, 1 - $answerChanges);
        
        // Attention bonus
        $Af = max(0, 1 - min(1, $tabSwitches / 2));
        
        $lms = (0.50 * $score) + (0.15 * $hardAccuracy) + (10 * $Ccal) + (10 * $Ks) + (10 * $Af) - (15 * pow($Hu, 1.5));
        
        return max(0, min(100, $lms));
    }

    /**
     * Classify mastery level based on LMS.
     */
    private function classifyMasteryLevel(float $lms): string
    {
        if ($lms >= 76) return 'advanced';
        if ($lms >= 56) return 'proficient';
        if ($lms >= 36) return 'developing';
        return 'at_risk';
    }
}
