<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Student;
use App\Models\StudentModulePerformance;

class GeminiInsightsService
{
    protected string $apiKey;
    protected string $apiUrl = 'https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash-lite:generateContent';

    public function __construct()
    {
        $this->apiKey = env('GEMINI_INSIGHTS_API', '');
    }

    /**
     * Generate comprehensive student insights using Gemini AI.
     */
    public function generateStudentInsights(Student $student): string
    {
        if (empty($this->apiKey)) {
            Log::error('GEMINI_INSIGHTS_API is missing.');
            return "AI Insights are currently unavailable (API Key missing).";
        }
        
        Log::info('Generating insights with Gemini Key: ' . substr($this->apiKey, 0, 5) . '...');

        // 1. Gather Data
        $student->load(['modulePerformances.module']);
        $performances = $student->modulePerformances;

        if ($performances->isEmpty()) {
            return "No performance data available for this student yet.";
        }

        // 2. Construct Prompt Data
        $modulesData = $performances->map(function ($p) {
            return sprintf(
                "- Module: %s\n  Score: %s%%\n  LMS: %s (%s)\n  Strengths: %s\n  Weaknesses: %s\n  AI Analysis: %s",
                $p->module->name,
                $p->score_percentage,
                $p->learning_mastery_score,
                $p->mastery_level,
                $p->top_positive_factors,
                $p->top_negative_factors,
                $p->xai_explanation
            );
        })->implode("\n\n");

        $overallStats = [
            'avg_lms' => round($performances->avg('learning_mastery_score'), 1),
            'avg_score' => round($performances->avg('score_percentage'), 1),
            'completed_modules' => $performances->count(),
        ];

        $prompt = <<<EOT
ROLE: Advanced Pedagogical Analytics Engine
TASK: Analyze student performance data to provide a high-level strategic assessment for the course instructor.

STUDENT PROFILE: "{$student->name}"

DATA DATASET:
[Overall Metrics]
- Modules Completed: {$overallStats['completed_modules']}
- Avg Learning Mastery Score (LMS): {$overallStats['avg_lms']}
- Avg Exam Score: {$overallStats['avg_score']}%

[Module-Level Performance]
{$modulesData}

ANALYSIS GUIDELINES:
1. **Objective Analysis**: Dissect the facts. Avoid conversational fillers like "Dear Instructor". Focus on patterns, correlations, and anomalies.
2. **Pattern Recognition**: Identify recurring strengths (positive factors) and persistent knowledge gaps (negative factors) across modules.
3. **Strategic Recommendations**: Provide high-impact, actionable interventions for the instructor to apply.
4. **Tone**: Clinical, data-driven, insightful, direct.

OUTPUT FORMAT (Markdown):
### Executive Analysis
[Brief data synthesis of student's current standing and trajectory]

### Performance Patterns
[Detailed breakdown of strengths versus struggling concepts, referencing specific modules]

### Strategic Recommendations
[2-3 concrete scaffolding actions for the instructor]
EOT;

        // 3. Call Gemini API
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("{$this->apiUrl}?key={$this->apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 1000,
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['candidates'][0]['content']['parts'][0]['text'] ?? "Unable to generate insights at this time.";
            }

            Log::error('Gemini API Error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return "Error from AI provider (Status " . $response->status() . "). Please check logs.";

        } catch (\Exception $e) {
            Log::error('Gemini Service Exception: ' . $e->getMessage());
            return "An unexpected error occurred while generating insights.";
        }
    }
}
