<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HintController extends Controller
{
    public function generate(Request $request)
    {
        $question = $request->input('question_text');

        $response = Http::withHeaders([
    'Content-Type' => 'application/json',
])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . env('GEMINI_API_KEY'), [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => "You are a kind, knowledgeable teacher. Your job is to give a motivational hint for this question without revealing the answer.

Question: {$question}

include these but dont Number them:
1. A helpful nudge in the right direction (don't give the answer).
2. A sentence explaining why learning this is important.
3. Encourage the student to think deeper instead of copying.

Make it feel supportive and engaging like a human teacher.dont use asterics or any special characters, just plain text. and organzie the content "
                        ]
                    ]
                ]
            ]
        ]);

        Log::info('Gemini response:', $response->json());

        $text = $response->json('candidates.0.content.parts.0.text');

        return response()->json([
            'hint' => $text ?? "Sorry, hint not available at the moment."
        ]);
    }
}
