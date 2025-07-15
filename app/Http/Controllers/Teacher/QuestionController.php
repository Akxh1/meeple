<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Imports\QuestionsImport;
use App\Models\Question;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class QuestionController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
        // $this->middleware('teacher');
    }

    // Show the Excel upload form
    public function showUploadForm()
    {
        return view('teacher.questions.upload');
    }

    // Handle file upload, parse Excel, and show preview
    public function previewUpload(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls'
        ]);

        $import = new QuestionsImport();
        Excel::import($import, $request->file('excel_file'));

        $questions = $import->questions;

        // Pass parsed data as JSON to the preview page for form submission later
        return view('teacher.questions.preview', compact('questions'));
    }

    // Final save of questions and answers to DB after confirmation
    public function storeUploaded(Request $request)
    {
        $request->validate([
            'questions_json' => 'required'
        ]);

        $questions = json_decode($request->input('questions_json'), true);

        foreach ($questions as $q) {
            $question = Question::create([
                'question_text' => $q['question_text'],
                'type' => $q['type'],
            ]);

            foreach ($q['answers'] as $answer) {
                $question->answers()->create([
                    'answer_text' => $answer['answer_text'],
                    'is_correct' => $answer['is_correct'],
                ]);
            }
        }

        return redirect()->route('teacher.questions.upload')->with('success', 'Questions uploaded successfully!');
    }
}
