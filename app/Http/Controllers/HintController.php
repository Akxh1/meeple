<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HintController extends Controller
{
    /**
     * Generate an adaptive hint based on the question and student context.
     */
    public function generate(Request $request)
    {
        // --- 1. Get Inputs ---
        $question = $request->input('question_text');

        // --- STATIC PLACEHOLDERS for Adaptive Scaffolding ---
        // These variables are not passed yet but are CRUCIAL for an adaptive system.
        // The frontend (or another service) will need to track and send these.

        /**
         * (int) The number of times the student has requested a hint for THIS question.
         * This is the *most important* adaptive variable.
         * Level 1 = First hint (minimal)
         * Level 2 = Second hint (more specific)
         * Level 3+ = Third hint (more direct)
         */
        $hint_level = $request->input('hint_level', 3); // Default to 1 if not provided.

        /**
         * (string) A brief summary of the student's performance or context.
         * This helps the AI tailor the motivational message.
         */
        $student_context = $request->input(
            'student_context',
            "The student is a beginner and has made 2 previous incorrect attempts on this quiz."
        ); // Static default.

        // --- 2. Validate Input ---
        if (empty($question)) {
            return response()->json(['hint' => '<p>Error: No question text provided.</p>'], 400);
        }

        // --- 3. Build the Adaptive Prompt ---
        $promptText = "
You are an expert, empathetic AI tutor integrated into a Learning Management System (LMS).
Your purpose is to provide 'adaptive scaffolding'. This means your hints must be tiered and adapt to the student's context.

Here is the data you have:
- Question: \"{$question}\"
- Hint Level Requested: {$hint_level}
- Student's Context: \"{$student_context}\"

--- HINTING STRATEGY (Adaptive Scaffolding) ---
Follow these rules strictly based on the 'Hint Level Requested':

- **If Hint Level = 1 (First Hint):**
    - Be minimal. Provide a high-level nudge or a Socratic question.
    - Remind the student of the general concept (e.g., \"Remember how we defined...?\").
    - DO NOT give any specific steps or definitions.

- **If Hint Level = 2 (Second Hint):**
    - Be more direct, as the student is stuck.
    - Clearly explain the *first step* required to solve the problem.
    - Identify a common mistake to avoid.
    - You can be more explicit, but *still* do not reveal the final answer.

- **If Hint Level = 3 or more (Later Hints):**
    - The student is struggling significantly and needs remediation.
    - Provide a complete, direct instruction.
    - **Part 1: Concepts:** Give a \"whole rundown of all concepts related to the question.\" Explain the underlying principles, definitions, and steps needed.
    - **Part 2: Solution:** After explaining the concepts, provide the final \"question answer as well.\"
    - **Formatting:** Use clear HTML formatting. For example:
      \"<p>It looks like you're stuck on this, let's review the concepts.</p>
      <p><strong>Key Concepts:</strong></p>
      <ul><li>Concept A is...</li><li>Concept B is...</li></ul>
      <p><strong>Solution:</strong></p>
      <p>Based on that, the answer is [Provide clear answer] because... </p>\"

--- MANDATORY FORMATTING & TONE RULES ---
1.  **Tone:** Be kind, knowledgeable, and motivational.
2.  **HTML Format:** ALWAYS format your response in HTML. Use <p> tags for paragraphs. Use <ul><li> for bullet points. Use <strong> or <em> for emphasis.
3.  **No Answer:** Never reveal the final answer. The goal is to help them *think*, not to give them the solution.
4.  **Include:** Your response MUST include these three parts (do not number them):
    * A helpful nudge (based on the Hint Level Strategy above).
    * A brief sentence explaining *why* this concept is important.
    * A short, encouraging closing (e.g., \"You can do this!\", \"Keep thinking!\").
5.  **Length:** Be concise. Aim for a maximum of 300 characters.
6.  **Clean HTML:** Do not use asterisks, triple backticks, or any non-HTML formatting.
";

        // --- 4. Call Gemini API ---
        $model = 'gemini-2.5-flash-preview-09-2025'; // Using the recommended model
        $apiKey = env('GEMINI_API_KEY');
        $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

        Log::info('Gemini API Request Payload:', ['prompt' => $promptText]);

        $response = Http::withHeaders([
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

        // --- 5. Handle API Response ---
        if ($response->failed()) {
            Log::error('Gemini API Error:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return response()->json([
                'hint' => '<p>Sorry, a hint is not available at the moment. Please try again.</p>'
            ], 500); // Internal Server Error
        }

        Log::info('Gemini API Response:', $response->json());

        $text = $response->json('candidates.0.content.parts.0.text');

        if (empty($text)) {
            Log::warning('Gemini API returned empty text:', $response->json());
            return response()->json([
                'hint' => '<p>Sorry, a hint could not be generated for this question.</p>'
            ]);
        }

        // --- 6. Clean and Return ---
        // Remove triple backticks and "html" tag if the model accidentally adds them
        $cleanedText = preg_replace('/^```html\s*|\s*```$/s', '', $text);
        $cleanedText = trim($cleanedText);

        return response()->json([
            'hint' => $cleanedText
        ]);
    }
}