<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class DashboardController extends Controller
{
    public function index()
    {
        // Raw students collection for normal Blade usage
        $students = Student::with('progress')->get();

        // Map to plain array for JSON / Chart.js
        $studentsArray = $students->map(function ($student) {
            return [
                'name' => $student->name,
                'exam_1' => $student->progress->exam_1 ?? 0,
                'exam_2' => $student->progress->exam_2 ?? 0,
                'exam_3' => $student->progress->exam_3 ?? 0,
                'attendance_rate' => $student->progress->attendance_rate ?? 0,
                'engagement_score' => $student->progress->engagement_score ?? 0,
                'quiz_score' => $student->progress->quiz_score ?? 0,
                'group_work_score' => $student->progress->group_work_score ?? 0,
                'revision_hours' => $student->progress->revision_hours ?? 0,
            ];
        });

        return view('dashboard', [
            'students' => $students,           // raw collection for tables
            'studentsArray' => $studentsArray  // plain array for JSON/Chart.js
        ]);
    }

    public function studentDashboard()
    {
        // Static modules for the prototype
        // In the future, these will come from: Module::where('student_id', $user->id)->get();
        $modules = [
            [
                'id' => 1,
                'title' => 'Cyber Security Essentials', // Requested module
                'category' => 'Network Security',
                'progress' => 75,
                'total_lessons' => 20,
                'completed_lessons' => 15,
                'last_accessed' => '2 hours ago',
                'risk_score' => 'Low', // X-Scaffold Context: Safe
                'image_color' => 'bg-red-500', // Just for visual variety
                'icon' => '🛡️'
            ],
            [
                'id' => 2,
                'title' => 'Advanced Python for ML',
                'category' => 'Data Science',
                'progress' => 45,
                'total_lessons' => 40,
                'completed_lessons' => 18,
                'last_accessed' => '1 day ago',
                'risk_score' => 'High', // X-Scaffold Context: Student needs intervention here
                'image_color' => 'bg-blue-500',
                'icon' => '🐍'
            ],
            [
                'id' => 3,
                'title' => 'Data Structures & Algorithms',
                'category' => 'Computer Science',
                'progress' => 10,
                'total_lessons' => 50,
                'completed_lessons' => 5,
                'last_accessed' => '3 days ago',
                'risk_score' => 'Medium', 
                'image_color' => 'bg-purple-500',
                'icon' => '🧮'
            ],
            [
                'id' => 4,
                'title' => 'Web Development with Laravel',
                'category' => 'Full Stack',
                'progress' => 90,
                'total_lessons' => 30,
                'completed_lessons' => 27,
                'last_accessed' => '5 mins ago',
                'risk_score' => 'Low',
                'image_color' => 'bg-indigo-500',
                'icon' => '🌐'
            ],
            [
                'id' => 5,
                'title' => 'Introduction to Cloud Computing',
                'category' => 'DevOps',
                'progress' => 0,
                'total_lessons' => 15,
                'completed_lessons' => 0,
                'last_accessed' => 'Never',
                'risk_score' => 'None',
                'image_color' => 'bg-gray-500',
                'icon' => '☁️'
            ],
        ];

        return view('dashboard.student', compact('modules'));
    }
}
