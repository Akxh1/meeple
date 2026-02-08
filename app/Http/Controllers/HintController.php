<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\StudentModulePerformance;
use App\Services\MLPredictionService;

class HintController extends Controller
{
    protected MLPredictionService $mlService;

    public function __construct(MLPredictionService $mlService)
    {
        $this->mlService = $mlService;
    }

    /**
     * Generate an adaptive hint based on the question and student's ML-predicted level.
     * 
     * This controller integrates the Adaptive Scaffolding Framework:
     * 1. ML Model → Predicts student level (L1/L2/L3)
     * 2. XAI (LIME/SHAP) → Explains why the level was predicted
     * 3. LLM → Generates personalized hint based on level + XAI context
     */
    public function generate(Request $request)
    {
        // --- 1. Get Inputs ---
        $question = $request->input('question_text');
        $moduleId = $request->input('module_id');
        $isDiagnostic = $request->input('is_diagnostic', false);

        // --- 2. Validate Input ---
        if (empty($question)) {
            return response()->json(['hint' => '<p>Error: No question text provided.</p>'], 400);
        }

        // --- 3. Get XAI Context (Real or Fallback) ---
        // For DIAGNOSTIC exams (Level Indicator), use simple L2 hints
        // since we're COLLECTING data, not using prior performance data
        if ($isDiagnostic) {
            $hint_level = 2; // Fixed moderate level
            $student_level = 'Student (Diagnostic Mode)';
            $xai_analysis = "This is a diagnostic assessment. Provide a balanced hint that helps without giving away the answer.";
        } else {
            $xaiData = $this->getXAIContext($moduleId);
            $hint_level = $xaiData['hint_level'];
            $xai_analysis = $xaiData['xai_analysis'];
            $student_level = $xaiData['student_level'];
        }

        // --- 4. Build the Adaptive Prompt ---
        $promptText = $this->buildPrompt($question, $student_level, $xai_analysis);

        // --- 5. Call Gemini API ---
        $model = 'gemini-2.0-flash'; // Using stable model
        $apiKey = env('GEMINI_API_KEY');
        $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

        Log::info('Hint Generation Request:', [
            'question' => substr($question, 0, 100),
            'hint_level' => $hint_level,
            'student_level' => $student_level,
            'has_real_xai' => $xaiData['is_real_data']
        ]);

        $response = Http::timeout(30)->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post($apiUrl, [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $promptText]
                    ]
                ]
            ]
        ]);

        // --- 6. Handle API Response ---
        if ($response->failed()) {
            Log::error('Gemini API Error:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return response()->json([
                'hint' => '<p>Sorry, a hint is not available at the moment. Please try again.</p>'
            ], 500);
        }

        Log::info('Gemini API Response received');

        $text = $response->json('candidates.0.content.parts.0.text');

        if (empty($text)) {
            Log::warning('Gemini API returned empty text:', $response->json());
            return response()->json([
                'hint' => '<p>Sorry, a hint could not be generated for this question.</p>'
            ]);
        }

        // --- 7. Clean and Return ---
        $cleanedText = $this->cleanHintOutput($text);

        return response()->json([
            'hint' => $cleanedText
        ]);
    }

    /**
     * Get XAI context from database or use fallback.
     * 
     * Retrieves mastery level and SHAP explanation from the student's 
     * performance record (set by Level Indicator Exam via ML prediction).
     */
    protected function getXAIContext(?int $moduleId): array
    {
        $hint_level = 2; // Default: Developing (L2)
        $xai_analysis = $this->getDefaultXAIAnalysis();
        $is_real_data = false;

        // Try to get real ML data from database
        $user = Auth::user();
        if ($user && $moduleId) {
            $student = Student::where('user_id', $user->id)->first();
            
            if ($student) {
                $performance = StudentModulePerformance::where('student_id', $student->id)
                    ->where('module_id', $moduleId)
                    ->first();
                
                if ($performance && $performance->mastery_level) {
                    // Use mastery_level directly (set by ML prediction from Level Indicator Exam)
                    $hint_level = $this->masteryNameToHintLevel($performance->mastery_level);
                    $is_real_data = true;
                    
                    // Use stored XAI explanation if available
                    if ($performance->xai_explanation) {
                        $xai_analysis = $performance->xai_explanation;
                    } elseif ($performance->shap_values) {
                        // Generate from SHAP values if available
                        $xai_analysis = $this->formatShapValues($performance->shap_values, $performance);
                    } else {
                        // Generate analysis from available features
                        $xai_analysis = $this->generateLMSBasedAnalysis($performance);
                    }
                }
            }
        }

        // Map hint level to student level name
        $level_names = [
            1 => 'Proficient (L1)',
            2 => 'Developing (L2)', 
            3 => 'Struggling (L3)'
        ];
        $student_level = $level_names[$hint_level] ?? 'Developing (L2)';

        return [
            'hint_level' => $hint_level,
            'student_level' => $student_level,
            'xai_analysis' => $xai_analysis,
            'is_real_data' => $is_real_data
        ];
    }

    /**
     * Format SHAP values into natural language.
     */
    protected function formatShapValues(array $shapValues, StudentModulePerformance $performance): string
    {
        $parts = [];
        
        // Get top contributors
        $sorted = collect($shapValues)
            ->map(fn($v, $k) => ['name' => $k, 'value' => $v['value'] ?? 0])
            ->sortByDesc(fn($item) => abs($item['value']));
        
        $positive = $sorted->filter(fn($item) => $item['value'] > 0.03)->take(3);
        $negative = $sorted->filter(fn($item) => $item['value'] < -0.03)->take(3);
        
        if ($positive->isNotEmpty()) {
            $posText = $positive->map(fn($item) => 
                $this->humanizeFeature($item['name']) . " (+" . round($item['value'], 2) . ")"
            )->implode(', ');
            $parts[] = "Positive factors: {$posText}";
        }
        
        if ($negative->isNotEmpty()) {
            $negText = $negative->map(fn($item) => 
                $this->humanizeFeature($item['name']) . " (" . round($item['value'], 2) . ")"
            )->implode(', ');
            $parts[] = "Areas for improvement: {$negText}";
        }
        
        // Add performance context
        $parts[] = sprintf(
            "Current performance: %.1f%% score, %.1f%% hint usage, %.1f confidence",
            $performance->score_percentage,
            $performance->hint_usage_percentage,
            $performance->avg_confidence
        );
        
        return "Based on SHAP analysis: " . implode(". ", $parts) . ".";
    }

    /**
     * Generate XAI-style analysis from LMS components when ML is not available.
     */
    protected function generateLMSBasedAnalysis(StudentModulePerformance $p): string
    {
        $parts = [];
        
        // Score analysis
        if ($p->score_percentage >= 70) {
            $parts[] = "Score of {$p->score_percentage}% shows strong performance";
        } elseif ($p->score_percentage >= 50) {
            $parts[] = "Score of {$p->score_percentage}% indicates developing understanding";
        } else {
            $parts[] = "Score of {$p->score_percentage}% suggests need for additional support";
        }
        
        // Hint usage
        if ($p->hint_usage_percentage > 50) {
            $parts[] = "High hint usage ({$p->hint_usage_percentage}%) shows reliance on scaffolding";
        } elseif ($p->hint_usage_percentage > 20) {
            $parts[] = "Moderate hint usage ({$p->hint_usage_percentage}%) indicates balanced learning approach";
        } else {
            $parts[] = "Low hint usage ({$p->hint_usage_percentage}%) demonstrates independence";
        }
        
        // Confidence calibration
        $expectedConf = $p->score_percentage / 20;
        $confDiff = abs($p->avg_confidence - $expectedConf);
        if ($confDiff <= 1) {
            $parts[] = "Confidence ({$p->avg_confidence}/5) is well-calibrated with performance";
        } elseif ($p->avg_confidence > $expectedConf) {
            $parts[] = "Confidence ({$p->avg_confidence}/5) may be slightly overestimated";
        } else {
            $parts[] = "Confidence ({$p->avg_confidence}/5) could be higher given performance";
        }
        
        // Tab switches / focus
        if ($p->tab_switches_rate > 2) {
            $parts[] = "Focus patterns suggest some attention challenges";
        }
        
        return "Based on LMS analysis: " . implode(". ", $parts) . ".";
    }

    /**
     * Convert feature name to human-readable format.
     */
    protected function humanizeFeature(string $feature): string
    {
        $map = [
            'score_percentage' => 'Score',
            'hard_question_accuracy' => 'Hard Question Accuracy',
            'hint_usage_percentage' => 'Hint Usage',
            'avg_confidence' => 'Confidence',
            'answer_changes_rate' => 'Answer Stability',
            'tab_switches_rate' => 'Focus Rate',
            'avg_time_per_question' => 'Time per Question',
            'review_percentage' => 'Review Usage',
            'avg_first_action_latency' => 'Response Time',
            'clicks_per_question' => 'Engagement',
            'performance_trend' => 'Endurance Trend',
        ];
        
        return $map[$feature] ?? str_replace('_', ' ', ucfirst($feature));
    }

    /**
     * Map mastery level name to hint level.
     */
    protected function masteryNameToHintLevel(?string $masteryLevel): int
    {
        return match($masteryLevel) {
            'advanced' => 1,    // Minimal scaffolding
            'proficient' => 1,  // Minimal scaffolding
            'developing' => 2,  // Moderate scaffolding
            'at_risk' => 3,     // Intensive scaffolding
            default => 2        // Default to developing
        };
    }

    /**
     * Get default XAI analysis when no real data is available.
     */
    protected function getDefaultXAIAnalysis(): string
    {
        return "Based on general assessment: The student is working through the material " .
            "with typical patterns for their mastery level. Key areas to focus on include " .
            "building conceptual understanding and applying problem-solving strategies systematically.";
    }

    /**
     * Build the LLM prompt with XAI context.
     */
    protected function buildPrompt(string $question, string $studentLevel, string $xaiAnalysis): string
    {
        return "
You are an expert, empathetic AI tutor integrated into a Learning Management System (LMS).
You implement 'Adaptive Scaffolding' by tailoring hints to each student's predicted learning level.

=== STUDENT PROFILE ===
- Predicted Level: {$studentLevel}
- XAI Explanation (Why this level): {$xaiAnalysis}

=== QUESTION ===
\"{$question}\"

=== ADAPTIVE SCAFFOLDING STRATEGY ===

**For Proficient (L1) Students:**
- Provide a minimal Socratic prompt (a thought-provoking question)
- Avoid direct explanations; trust their ability to reason
- Example: \"What's the relationship between X and Y that might help here?\"
- Keep it under 100 words

**For Developing (L2) Students:**
- Give a structured nudge with ONE key concept reminder
- Point out a common misconception to avoid
- Provide the first step WITHOUT revealing the answer
- Example: \"Remember that [concept]. A common mistake is [X]. Start by [first step]...\"
- Keep it under 150 words

**For Struggling (L3) Students:**
- Acknowledge their effort with empathy (reference XAI insights if helpful)
- Provide a clear, step-by-step conceptual breakdown
- Use analogies or simplified examples
- Give the solution WITH explanation of why it's correct
- Format with clear sections: Concepts → Steps → Answer
- Up to 250 words

=== OUTPUT REQUIREMENTS ===
1. **HTML Format Only**: Use <p>, <ul>, <li>, <strong>, <em> tags. NO markdown (**, ```, etc.)
2. **Personalized Tone**: Reference the XAI insights naturally (e.g., \"I noticed you might be working quickly...\")
3. **Encouraging Closing**: End with a brief motivational phrase
4. **No Meta-Commentary**: Don't mention \"as an AI\" or \"based on your level\"

Generate the hint now:
";
    }

    /**
     * Clean the LLM output to ensure proper HTML format.
     */
    protected function cleanHintOutput(string $text): string
    {
        // Remove markdown code block wrappers
        $text = preg_replace('/^```html\s*|\s*```$/s', '', $text);
        
        // Convert markdown bold to HTML
        $text = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $text);
        
        // Convert markdown italic to HTML
        $text = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $text);
        
        return trim($text);
    }
}