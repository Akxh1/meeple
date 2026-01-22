<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\StudentProgress;

class StudentController extends Controller
{
    public function getWarnings()
    {
        $user = Auth::user();

        // Check if logged-in user is a student
        if ($user->role !== 'student') {
            return response()->json([]);
        }

        $student = Student::where('user_id', $user->id)->first();
        if (!$student) {
            return response()->json([]);
        }

        $progress = StudentProgress::where('student_id', $student->id)->first();
        $messages = [];

        if ($progress) {
            if ($progress->exam_1 < 40) {
                $messages[] = [
                    'sender' => 'System',
                    'text' => 'Warning: Your Exam 1 score is below 40%',
                    'time' => now()->diffForHumans()
                ];
            }
            if ($progress->exam_2 < 40) {
                $messages[] = [
                    'sender' => 'System',
                    'text' => 'Warning: Your Exam 2 score is below 40%',
                    'time' => now()->diffForHumans()
                ];
            }
            if ($progress->exam_3 < 40) {
                $messages[] = [
                    'sender' => 'System',
                    'text' => 'Warning: Your Exam 3 score is below 40%',
                    'time' => now()->diffForHumans()
                ];
            }
            if ($progress->attendance_rate < 40) {
                $messages[] = [
                    'sender' => 'System',
                    'text' => 'Warning: Your attendance rate is below 40%',
                    'time' => now()->diffForHumans()
                ];
            }
        }

        return response()->json($messages);
        
    }
}
