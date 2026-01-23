<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Module;
use App\Models\StudentModulePerformance;
use Illuminate\Database\Seeder;

class StudentModulePerformanceSeeder extends Seeder
{
    /**
     * Seed 5 random students per module with mock exam performance data.
     * Some students may have performance records for multiple modules.
     */
    public function run(): void
    {
        $modules = Module::all();
        $students = Student::all();

        if ($students->count() < 5) {
            $this->command->error('Less than 5 students exist. Please seed students first.');
            return;
        }

        if ($modules->isEmpty()) {
            $this->command->error('No modules found. Please seed modules first.');
            return;
        }

        $this->command->info("Seeding performance data for {$modules->count()} modules...");

        foreach ($modules as $module) {
            // Select 5 random students for this module
            $selectedStudents = $students->random(min(5, $students->count()));

            $this->command->line("\n📚 Module: {$module->name}");

            foreach ($selectedStudents as $student) {
                // Check if record already exists (upsert behavior)
                $existing = StudentModulePerformance::where('student_id', $student->id)
                    ->where('module_id', $module->id)
                    ->first();

                if ($existing) {
                    $this->command->line("   ↻ Skipping (already exists): {$student->name}");
                    continue;
                }

                // Generate performance data
                $performanceData = $this->generatePerformanceData($student->id, $module->id);
                
                StudentModulePerformance::create($performanceData);
                
                $this->command->line("   ✓ Created: {$student->name} (LMS: {$performanceData['learning_mastery_score']}, Level: {$performanceData['mastery_level']})");
            }
        }

        $totalRecords = StudentModulePerformance::count();
        $this->command->info("\n✅ Seeding complete! Total performance records: {$totalRecords}");
    }

    /**
     * Generate realistic performance data for a student-module pair.
     */
    private function generatePerformanceData(int $studentId, int $moduleId): array
    {
        // Generate a base performance tier to create realistic correlations
        $performanceTier = fake()->randomElement(['high', 'medium', 'low']);
        
        switch ($performanceTier) {
            case 'high':
                $baseScore = fake()->randomFloat(2, 75, 100);
                $baseConfidence = fake()->randomFloat(2, 3.5, 5.0);
                $hintUsage = fake()->randomFloat(2, 0, 15);
                break;
            case 'medium':
                $baseScore = fake()->randomFloat(2, 50, 75);
                $baseConfidence = fake()->randomFloat(2, 2.5, 4.0);
                $hintUsage = fake()->randomFloat(2, 15, 40);
                break;
            default: // low
                $baseScore = fake()->randomFloat(2, 20, 50);
                $baseConfidence = fake()->randomFloat(2, 1.5, 3.0);
                $hintUsage = fake()->randomFloat(2, 40, 80);
        }

        // Calculate derived features
        $hardQuestionAccuracy = max(0, min(100, $baseScore - fake()->randomFloat(2, 5, 20)));
        $answerChangesRate = fake()->randomFloat(4, 0.0, 0.5);
        $tabSwitchesRate = fake()->randomFloat(4, 0.0, 0.8);
        
        // Tier 2 features
        $avgTimePerQuestion = fake()->randomFloat(2, 15, 120);
        $reviewPercentage = fake()->randomFloat(2, 0, 50);
        $firstActionLatency = fake()->randomFloat(2, 1, 15);
        $clicksPerQuestion = fake()->randomFloat(2, 1, 8);
        $performanceTrend = fake()->randomFloat(2, -20, 20);

        // Calculate LMS
        $lms = $this->calculateLMS($baseScore, $hardQuestionAccuracy, $hintUsage, $baseConfidence, $answerChangesRate, $tabSwitchesRate);
        $masteryLevel = $this->classifyMasteryLevel($lms);

        return [
            'student_id' => $studentId,
            'module_id' => $moduleId,
            'score_percentage' => round($baseScore, 2),
            'hard_question_accuracy' => round($hardQuestionAccuracy, 2),
            'hint_usage_percentage' => round($hintUsage, 2),
            'avg_confidence' => round($baseConfidence, 2),
            'answer_changes_rate' => round($answerChangesRate, 4),
            'tab_switches_rate' => round($tabSwitchesRate, 4),
            'avg_time_per_question' => round($avgTimePerQuestion, 2),
            'review_percentage' => round($reviewPercentage, 2),
            'avg_first_action_latency' => round($firstActionLatency, 2),
            'clicks_per_question' => round($clicksPerQuestion, 2),
            'performance_trend' => round($performanceTrend, 2),
            'learning_mastery_score' => round($lms, 2),
            'mastery_level' => $masteryLevel,
        ];
    }

    private function calculateLMS(float $score, float $hardAccuracy, float $hintUsage, float $confidence, float $answerChanges, float $tabSwitches): float
    {
        $Hu = $hintUsage / 100;
        $expectedConfidence = $score / 20;
        $Ccal = max(0, 1 - (abs($confidence - $expectedConfidence) / 5));
        $Ks = max(0, 1 - $answerChanges);
        $Af = max(0, 1 - min(1, $tabSwitches / 2));
        
        $lms = (0.50 * $score) + (0.15 * $hardAccuracy) + (10 * $Ccal) + (10 * $Ks) + (10 * $Af) - (15 * pow($Hu, 1.5));
        
        return max(0, min(100, $lms));
    }

    private function classifyMasteryLevel(float $lms): string
    {
        if ($lms >= 76) return 'advanced';
        if ($lms >= 56) return 'proficient';
        if ($lms >= 36) return 'developing';
        return 'at_risk';
    }
}
