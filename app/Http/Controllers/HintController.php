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
     * Three distinct modes:
     * 1. DIAGNOSTIC  → Fixed L2 hints (collecting data, no prior context)
     * 2. GENERIC     → One-liner hint (no Level Indicator data exists)
     * 3. PERSONALIZED → Rich adaptive hint using SHAP/XAI from StudentModulePerformance
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

        // --- 3. Determine Hint Mode ---
        $hintMode = 'generic'; // default
        $studentProfile = null;

        if ($isDiagnostic) {
            $hintMode = 'diagnostic';
            $promptText = $this->buildDiagnosticPrompt($question);
        } else {
            $xaiData = $this->getXAIContext($moduleId);

            if ($xaiData['is_real_data']) {
                $hintMode = 'personalized';
                $studentProfile = $xaiData['student_profile'];
                $promptText = $this->buildPersonalizedPrompt($question, $xaiData);
            } else {
                $hintMode = 'generic';
                $promptText = $this->buildGenericOneLinerPrompt($question);
            }
        }

        // --- 4. Call Gemini API (with retry for rate limits) ---
        $apiKey = env('GEMINI_INSIGHTS_API');
        $apiUrl = "https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash-lite:generateContent?key={$apiKey}";

        $hasRealData = isset($xaiData) ? $xaiData['is_real_data'] : false;

        Log::info('Hint Generation Request:', [
            'question' => substr($question, 0, 100),
            'hint_mode' => $hintMode,
            'has_real_xai' => $hasRealData
        ]);

        // Retry up to 3 times with backoff for 429 rate limits
        $maxTokens = $hintMode === 'generic' ? 100 : ($hintMode === 'personalized' ? 600 : 300);

        $response = null;
        $maxRetries = 3;
        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
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
                ],
                'generationConfig' => [
                    'temperature' => $hintMode === 'generic' ? 0.5 : 0.7,
                    'maxOutputTokens' => $maxTokens,
                ]
            ]);

            if ($response->successful()) {
                break;
            }

            if ($response->status() === 429 && $attempt < $maxRetries) {
                $delay = $attempt * 2;
                Log::warning("Gemini API rate limited (attempt {$attempt}/{$maxRetries}), retrying in {$delay}s...");
                sleep($delay);
                continue;
            }

            break;
        }

        // --- 5. Handle API Response ---
        if (!$response || $response->failed()) {
            Log::error('Gemini API Error (after retries):', [
                'status' => $response?->status(),
                'body' => $response?->body()
            ]);

            $fallbackHint = $this->generateFallbackHint($question, $hintMode === 'personalized' ? ($xaiData['hint_level'] ?? 2) : 2);
            return response()->json([
                'hint' => $fallbackHint,
                'is_fallback' => true,
                'hint_mode' => $hintMode,
                'student_profile' => $studentProfile,
            ]);
        }

        Log::info('Gemini API Response received');

        $text = $response->json('candidates.0.content.parts.0.text');

        if (empty($text)) {
            Log::warning('Gemini API returned empty text:', $response->json());
            $fallbackHint = $this->generateFallbackHint($question, 2);
            return response()->json([
                'hint' => $fallbackHint,
                'is_fallback' => true,
                'hint_mode' => $hintMode,
                'student_profile' => $studentProfile,
            ]);
        }

        // --- 6. Clean and Return ---
        $cleanedText = $this->cleanHintOutput($text);

        return response()->json([
            'hint' => $cleanedText,
            'is_fallback' => false,
            'hint_mode' => $hintMode,
            'student_profile' => $studentProfile,
        ]);
    }

    /**
     * Get XAI context from database. Returns full student profile when available.
     */
    protected function getXAIContext(?int $moduleId): array
    {
        $result = [
            'hint_level' => 2,
            'student_level' => 'Developing (L2)',
            'xai_analysis' => '',
            'is_real_data' => false,
            'student_profile' => null,
            'performance' => null,
        ];

        $user = Auth::user();
        if (!$user || !$moduleId) {
            return $result;
        }

        $student = Student::where('user_id', $user->id)->first();
        if (!$student) {
            return $result;
        }

        $performance = StudentModulePerformance::where('student_id', $student->id)
            ->where('module_id', $moduleId)
            ->first();

        if (!$performance || !$performance->mastery_level) {
            return $result;
        }

        // --- Real data found ---
        $hint_level = $this->masteryNameToHintLevel($performance->mastery_level);
        $level_names = [1 => 'Proficient (L1)', 2 => 'Developing (L2)', 3 => 'Struggling (L3)'];

        // Build XAI analysis string for prompt
        $xai_analysis = '';
        if ($performance->xai_explanation) {
            $xai_analysis = $performance->xai_explanation;
        } elseif ($performance->shap_values) {
            $xai_analysis = $this->formatShapValues($performance->shap_values, $performance);
        } else {
            $xai_analysis = $this->generateLMSBasedAnalysis($performance);
        }

        // Build student profile for frontend display
        $studentProfile = [
            'mastery_level' => $performance->mastery_level,
            'lms_score' => round($performance->learning_mastery_score, 1),
            'ml_confidence' => round($performance->ml_prediction_confidence * 100, 0),
            'score_percentage' => round($performance->score_percentage, 1),
            'avg_confidence' => round($performance->avg_confidence, 1),
            'hint_usage' => round($performance->hint_usage_percentage, 1),
            'top_strengths' => $performance->top_positive_factors,
            'top_weaknesses' => $performance->top_negative_factors,
        ];

        return [
            'hint_level' => $hint_level,
            'student_level' => $level_names[$hint_level] ?? 'Developing (L2)',
            'xai_analysis' => $xai_analysis,
            'is_real_data' => true,
            'student_profile' => $studentProfile,
            'performance' => $performance,
        ];
    }

    // ================================================================
    // PROMPT BUILDERS
    // ================================================================

    /**
     * Build a one-liner prompt for students WITHOUT Level Indicator data.
     * Produces a brief, generic hint — no adaptive scaffolding.
     */
    protected function buildGenericOneLinerPrompt(string $question): string
    {
        return "
You are a helpful tutor. A student is practising and has NOT completed any diagnostic assessment, so you have no data about their level.

=== QUESTION ===
\"{$question}\"

=== INSTRUCTIONS ===
Provide a SHORT, one-sentence hint that nudges the student toward the right approach WITHOUT revealing the answer.
- Maximum 1-2 sentences
- Be encouraging and concise
- Point toward the key concept needed, not the solution
- Output as a single <p> tag. No markdown.

Generate the one-liner hint now:
";
    }

    /**
     * Build a diagnostic prompt (Level Indicator exam — collecting data).
     * Fixed moderate hints since we're gathering data, not personalizing.
     */
    protected function buildDiagnosticPrompt(string $question): string
    {
        return "
You are an expert tutor. This student is taking a DIAGNOSTIC assessment (Level Indicator Exam) so you do NOT know their level yet.

=== QUESTION ===
\"{$question}\"

=== INSTRUCTIONS ===
Provide a balanced, moderate hint that helps without giving away the answer:
- Give ONE key concept or approach to consider
- Point out a common mistake to avoid
- Keep it under 100 words
- Output in HTML (<p>, <strong>, <em> tags). No markdown.

Generate the hint now:
";
    }

    /**
     * Build a rich personalized prompt using all SHAP/XAI data.
     * This is the full adaptive scaffolding experience.
     */
    protected function buildPersonalizedPrompt(string $question, array $xaiData): string
    {
        $profile = $xaiData['student_profile'];
        $performance = $xaiData['performance'];
        $studentLevel = $xaiData['student_level'];
        $xaiAnalysis = $xaiData['xai_analysis'];

        // Build detailed behavioral profile for the LLM
        $behavioralContext = sprintf(
            "Score: %s%% | Hard Q Accuracy: %s%% | Confidence: %s/5 | Hint Usage: %s%% | " .
            "Answer Changes: %s/q | Tab Switches: %s/q | Time/Q: %ss | Review: %s%% | " .
            "First Action: %ss | Clicks/Q: %s | Performance Trend: %s",
            round($performance->score_percentage, 1),
            round($performance->hard_question_accuracy, 1),
            round($performance->avg_confidence, 1),
            round($performance->hint_usage_percentage, 1),
            round($performance->answer_changes_rate, 2),
            round($performance->tab_switches_rate, 2),
            round($performance->avg_time_per_question, 1),
            round($performance->review_percentage, 1),
            round($performance->avg_first_action_latency, 1),
            round($performance->clicks_per_question, 1),
            ($performance->performance_trend >= 0 ? '+' : '') . round($performance->performance_trend, 1)
        );

        $strengths = $profile['top_strengths'] ?? 'Not available';
        $weaknesses = $profile['top_weaknesses'] ?? 'Not available';

        return "
You are an expert, empathetic AI tutor integrated into a Learning Management System (LMS).
You implement 'Adaptive Scaffolding' by tailoring hints to each student's ML-predicted learning level.
You have access to rich diagnostic data from their Level Indicator Exam.

=== STUDENT PROFILE (from ML Prediction + SHAP Analysis) ===
- Predicted Level: {$studentLevel}
- Learning Mastery Score: {$profile['lms_score']}/100
- ML Prediction Confidence: {$profile['ml_confidence']}%
- SHAP Strengths: {$strengths}
- SHAP Weaknesses: {$weaknesses}
- XAI Analysis: {$xaiAnalysis}

=== BEHAVIORAL DATA (11 Features) ===
{$behavioralContext}

=== QUESTION ===
\"{$question}\"

=== ADAPTIVE SCAFFOLDING STRATEGY ===

**For Proficient (L1) Students:**
- Provide a minimal Socratic prompt (a thought-provoking question)
- Reference their strength areas positively
- Avoid direct explanations; trust their ability to reason
- Keep it under 100 words

**For Developing (L2) Students:**
- Give a structured nudge with ONE key concept reminder
- Reference specific behavioral patterns (e.g., if high answer changes, suggest being more deliberate)
- Point out a common misconception to avoid
- Provide the first step WITHOUT revealing the answer
- Keep it under 150 words

**For Struggling (L3) Students:**
- Acknowledge their effort with genuine empathy
- Reference their SHAP weaknesses to address specific gaps
- Provide a clear conceptual breakdown with an analogy
- Give step-by-step guidance toward the answer
- If their confidence is low, add encouragement
- Up to 250 words

=== OUTPUT REQUIREMENTS ===
1. **HTML Format Only**: Use <p>, <ul>, <li>, <strong>, <em> tags. NO markdown (**, \`\`\`, etc.)
2. **Personalized Tone**: Weave in references to their specific data naturally
3. **Encouraging Closing**: End with a motivational phrase that acknowledges their learning journey
4. **No Meta-Commentary**: Don't mention \"as an AI\", \"your SHAP values\", or technical model terms

Generate the personalized hint now:
";
    }

    // ================================================================
    // HELPER METHODS
    // ================================================================

    /**
     * Format SHAP values into natural language for the prompt.
     */
    protected function formatShapValues(array $shapValues, StudentModulePerformance $performance): string
    {
        $parts = [];

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

        $parts[] = sprintf(
            "Current performance: %.1f%% score, %.1f%% hint usage, %.1f confidence",
            $performance->score_percentage,
            $performance->hint_usage_percentage,
            $performance->avg_confidence
        );

        return "Based on SHAP analysis: " . implode(". ", $parts) . ".";
    }

    /**
     * Generate XAI-style analysis from LMS components.
     */
    protected function generateLMSBasedAnalysis(StudentModulePerformance $p): string
    {
        $parts = [];

        if ($p->score_percentage >= 70) {
            $parts[] = "Score of {$p->score_percentage}% shows strong performance";
        } elseif ($p->score_percentage >= 50) {
            $parts[] = "Score of {$p->score_percentage}% indicates developing understanding";
        } else {
            $parts[] = "Score of {$p->score_percentage}% suggests need for additional support";
        }

        if ($p->hint_usage_percentage > 50) {
            $parts[] = "High hint usage ({$p->hint_usage_percentage}%) shows reliance on scaffolding";
        } elseif ($p->hint_usage_percentage > 20) {
            $parts[] = "Moderate hint usage ({$p->hint_usage_percentage}%) indicates balanced learning";
        } else {
            $parts[] = "Low hint usage ({$p->hint_usage_percentage}%) demonstrates independence";
        }

        $expectedConf = $p->score_percentage / 20;
        $confDiff = abs($p->avg_confidence - $expectedConf);
        if ($confDiff <= 1) {
            $parts[] = "Confidence ({$p->avg_confidence}/5) is well-calibrated";
        } elseif ($p->avg_confidence > $expectedConf) {
            $parts[] = "Confidence ({$p->avg_confidence}/5) may be slightly overestimated";
        } else {
            $parts[] = "Confidence ({$p->avg_confidence}/5) could be higher given performance";
        }

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
            'advanced' => 1,
            'proficient' => 1,
            'developing' => 2,
            'at_risk' => 3,
            default => 2
        };
    }

    /**
     * Generate a meaningful fallback hint when Gemini API is unavailable.
     */
    protected function generateFallbackHint(string $question, int $hintLevel): string
    {
        $questionLower = strtolower($question);

        $topicHints = [
            'set' => 'Think about the elements that belong to each set and the operations being performed (union, intersection, difference).',
            'probability' => 'Consider the total number of outcomes and the favorable outcomes. Remember P(E) = favorable / total.',
            'function' => 'Check the domain and range. Try substituting a value to trace through the function step by step.',
            'graph' => 'Visualize the structure. Consider the vertices, edges, and how they connect.',
            'logic' => 'Break down the statement into smaller propositions. Evaluate each part using truth values.',
            'relation' => 'Check each property: reflexive (a→a), symmetric (a→b means b→a), transitive (a→b and b→c means a→c).',
            'matrix' => 'Work through the calculation element by element. Pay attention to the dimensions.',
            'tree' => 'Consider the root, leaves, and the path between nodes. How many edges are there?',
            'boolean' => 'Apply the Boolean algebra rules. Try simplifying using De Morgan\'s laws or distribution.',
            'proof' => 'Identify what you need to show. What assumptions can you start with?',
        ];

        $matchedHint = 'Re-read the question carefully. Identify the key terms and think about what concept they relate to.';
        foreach ($topicHints as $keyword => $hint) {
            if (str_contains($questionLower, $keyword)) {
                $matchedHint = $hint;
                break;
            }
        }

        $prefix = match($hintLevel) {
            1 => '<p><strong>Quick Nudge:</strong></p>',
            3 => '<p><strong>Step-by-Step Guidance:</strong></p>',
            default => '<p><strong>Hint:</strong></p>',
        };

        $suffix = $hintLevel === 3
            ? '<p class="text-xs text-slate-500 mt-2"><em>Try working through a simpler example first, then apply the same method here.</em></p>'
            : '';

        return $prefix . '<p>' . $matchedHint . '</p>' . $suffix;
    }

    /**
     * Clean the LLM output to ensure proper HTML format.
     */
    protected function cleanHintOutput(string $text): string
    {
        $text = preg_replace('/^```html\s*|\s*```$/s', '', $text);
        $text = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $text);
        $text = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $text);

        return trim($text);
    }
}