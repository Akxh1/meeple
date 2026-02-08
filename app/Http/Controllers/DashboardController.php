<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\StudentModulePerformance;
use App\Models\Module;

class DashboardController extends Controller
{
    /**
     * Instructor Dashboard - shows all students with their multi-module performance
     */
    public function index(Request $request)
    {
        // Get all modules for the filter
        $modules = Module::withCount(['studentPerformances', 'questions'])->get();
        
        // Get selected module filter (default: show all)
        $selectedModuleId = $request->get('module', 'all');
        
        // Get all students with their module performance data
        $students = Student::with(['modulePerformances.module', 'user'])->get();
        
        // Calculate overall statistics
        $totalStudents = $students->count();
        
        // All performance records
        $allPerformances = StudentModulePerformance::with(['student', 'module'])->get();
        
        // Students who have taken at least one exam
        $studentsWithAnyPerformance = $students->filter(fn($s) => $s->modulePerformances->isNotEmpty());
        $studentsWithoutAnyPerformance = $students->filter(fn($s) => $s->modulePerformances->isEmpty());
        
        // Calculate metrics
        $examTakenCount = $studentsWithAnyPerformance->count();
        $examPendingCount = $studentsWithoutAnyPerformance->count();
        $totalPerformanceRecords = $allPerformances->count();
        
        // LMS Statistics
        $averageLMS = $allPerformances->count() > 0 ? $allPerformances->avg('learning_mastery_score') : 0;
        
        // Mastery level distribution across all modules
        $masteryDistribution = [
            'advanced' => $allPerformances->where('mastery_level', 'advanced')->count(),
            'proficient' => $allPerformances->where('mastery_level', 'proficient')->count(),
            'developing' => $allPerformances->where('mastery_level', 'developing')->count(),
            'at_risk' => $allPerformances->where('mastery_level', 'at_risk')->count(),
        ];
        
        // Per-module statistics
        $moduleStats = $modules->map(function ($module) use ($allPerformances) {
            $modulePerfs = $allPerformances->where('module_id', $module->id);
            return [
                'id' => $module->id,
                'name' => $module->name,
                'student_count' => $modulePerfs->count(),
                'questions_count' => $module->questions_count,
                'avg_lms' => $modulePerfs->count() > 0 ? round($modulePerfs->avg('learning_mastery_score'), 1) : 0,
                'avg_score' => $modulePerfs->count() > 0 ? round($modulePerfs->avg('score_percentage'), 1) : 0,
            ];
        });
        
        // Average feature values for insights
        $featureAverages = [];
        if ($allPerformances->count() > 0) {
            $featureAverages = [
                'score_percentage' => round($allPerformances->avg('score_percentage'), 1),
                'hard_question_accuracy' => round($allPerformances->avg('hard_question_accuracy'), 1),
                'hint_usage_percentage' => round($allPerformances->avg('hint_usage_percentage'), 1),
                'avg_confidence' => round($allPerformances->avg('avg_confidence'), 2),
                'avg_time_per_question' => round($allPerformances->avg('avg_time_per_question'), 1),
                'review_percentage' => round($allPerformances->avg('review_percentage'), 1),
            ];
        }

        return view('dashboard', compact(
            'students',
            'modules',
            'selectedModuleId',
            'studentsWithAnyPerformance',
            'studentsWithoutAnyPerformance',
            'totalStudents',
            'examTakenCount',
            'examPendingCount',
            'totalPerformanceRecords',
            'averageLMS',
            'masteryDistribution',
            'moduleStats',
            'featureAverages',
            'allPerformances'
        ));
    }

    /**
     * Student Dashboard - shows modules available to the student with their performance
     */
    public function studentDashboard()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();
        
        // Fetch all modules with question counts
        $modules = Module::withCount('questions')->get();
        
        // Get student's performance data
        $studentPerformances = collect();
        if ($student) {
            $studentPerformances = StudentModulePerformance::where('student_id', $student->id)
                ->get()
                ->keyBy('module_id');
        }
        
        // Module styling maps
        $colorMap = [
            1 => 'from-red-500 to-rose-600',
            2 => 'from-blue-500 to-cyan-600', 
            3 => 'from-green-500 to-emerald-600',
            4 => 'from-purple-500 to-violet-600',
            5 => 'from-amber-500 to-orange-600',
            6 => 'from-indigo-500 to-blue-600',
        ];
        
        $iconMap = [
            1 => 'fa-shield-halved',
            2 => 'fa-cloud',
            3 => 'fa-chart-line',
            4 => 'fa-network-wired',
            5 => 'fa-tasks',
            6 => 'fa-code',
        ];
        
        // Enrich modules with student performance data
        $modules = $modules->map(function ($module) use ($studentPerformances, $colorMap, $iconMap) {
            $performance = $studentPerformances->get($module->id);
            $hasPerformance = !is_null($performance);
            
            return [
                'id' => $module->id,
                'title' => $module->name,
                'description' => $module->description,
                'questions_count' => $module->questions_count,
                'gradient' => $colorMap[$module->id] ?? 'from-gray-500 to-slate-600',
                'icon' => $iconMap[$module->id] ?? 'fa-book',
                // Performance data
                'has_performance' => $hasPerformance,
                'lms' => $hasPerformance ? round($performance->learning_mastery_score, 1) : null,
                'mastery_level' => $hasPerformance ? $performance->mastery_level : null,
                'score' => $hasPerformance ? round($performance->score_percentage, 1) : null,
                'level_indicator_completed' => $hasPerformance,
            ];
        });
        
        // Calculate overall stats
        $totalModules = $modules->count();
        $completedModules = $studentPerformances->count();
        $avgLMS = $studentPerformances->count() > 0 ? round($studentPerformances->avg('learning_mastery_score'), 1) : 0;
        $avgScore = $studentPerformances->count() > 0 ? round($studentPerformances->avg('score_percentage'), 1) : 0;

        return view('dashboard.student', compact(
            'modules',
            'student',
            'totalModules',
            'completedModules',
            'avgLMS',
            'avgScore'
        ));
    }

    /**
     * Show individual student detail page with all module performances (for instructors)
     */
    public function showStudent(Student $student)
    {
        // Load all performance data for this student
        $student->load(['modulePerformances.module', 'user']);
        
        // Get all modules for reference
        $allModules = Module::all();
        
        // Group performances by module
        $modulePerformances = $student->modulePerformances->keyBy('module_id');
        
        // Calculate overall stats
        $hasPerformance = $student->modulePerformances->isNotEmpty();
        $overallStats = [];
        $aggregatedXAI = [];
        
        if ($hasPerformance) {
            $performances = $student->modulePerformances;
            $overallStats = [
                'modules_completed' => $performances->count(),
                'total_modules' => $allModules->count(),
                'best_lms' => $performances->max('learning_mastery_score'),
                'avg_lms' => round($performances->avg('learning_mastery_score'), 1),
                'avg_score' => round($performances->avg('score_percentage'), 1),
                'avg_confidence' => round($performances->avg('avg_confidence'), 2),
                'avg_hint_usage' => round($performances->avg('hint_usage_percentage'), 1),
                'avg_time_per_question' => round($performances->avg('avg_time_per_question'), 1),
                'total_answer_changes' => round($performances->avg('answer_changes_rate'), 4),
                'avg_review_percentage' => round($performances->avg('review_percentage'), 1),
            ];
            
            // Determine overall mastery level based on best LMS
            $bestLMS = $overallStats['best_lms'];
            $overallStats['mastery_level'] = $bestLMS >= 76 ? 'advanced' : 
                ($bestLMS >= 56 ? 'proficient' : ($bestLMS >= 36 ? 'developing' : 'at_risk'));
            
            // Aggregate XAI data across all modules
            $aggregatedXAI = [
                'avg_lms' => $overallStats['avg_lms'],
                'avg_confidence' => round($performances->avg('ml_prediction_confidence') * 100, 1),
                'modules_count' => $performances->count(),
                'positive_factors' => $this->aggregateFactors($performances->pluck('top_positive_factors')),
                'negative_factors' => $this->aggregateFactors($performances->pluck('top_negative_factors')),
                'level_distribution' => $performances->groupBy('mastery_level')->map->count()->toArray(),
            ];
        }
        
        // Get warning history
        $warnings = \App\Models\StudentNotification::where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('dashboard.instructor.student-detail', compact(
            'student',
            'allModules',
            'modulePerformances',
            'hasPerformance',
            'overallStats',
            'aggregatedXAI',
            'warnings'
        ));
    }

    /**
     * Aggregate XAI factors across multiple modules, counting occurrences
     */
    private function aggregateFactors($factorStrings): array
    {
        $counts = [];
        foreach ($factorStrings as $str) {
            foreach (explode(', ', $str ?? '') as $factor) {
                $factor = trim($factor);
                if ($factor) {
                    $counts[$factor] = ($counts[$factor] ?? 0) + 1;
                }
            }
        }
        arsort($counts);
        return array_slice($counts, 0, 5, true);
    }

    /**
     * Show module detail page for students
     */
    public function showModule(Module $module)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();
        
        // Get performance for this module if exists
        $performance = null;
        if ($student) {
            $performance = StudentModulePerformance::where('student_id', $student->id)
                ->where('module_id', $module->id)
                ->first();
        }
        
        // Module styling
        $colorMap = [
            1 => 'from-red-500 to-rose-600',
            2 => 'from-blue-500 to-cyan-600', 
            3 => 'from-green-500 to-emerald-600',
            4 => 'from-purple-500 to-violet-600',
            5 => 'from-amber-500 to-orange-600',
            6 => 'from-indigo-500 to-blue-600',
        ];
        
        $iconMap = [
            1 => 'fa-shield-halved',
            2 => 'fa-cloud',
            3 => 'fa-chart-line',
            4 => 'fa-network-wired',
            5 => 'fa-tasks',
            6 => 'fa-code',
        ];
        
        $moduleData = [
            'id' => $module->id,
            'title' => $module->name,
            'description' => $module->description,
            'questions_count' => $module->questions()->count(),
            'gradient' => $colorMap[$module->id] ?? 'from-gray-500 to-slate-600',
            'icon' => $iconMap[$module->id] ?? 'fa-book',
        ];
        
        return view('dashboard.module-detail', compact('module', 'moduleData', 'performance', 'student'));
    }

    /**
     * Send a warning notification to a student
     */
    public function sendWarning(Request $request, Student $student)
    {
        $request->validate([
            'warning_type' => 'required|in:performance,attendance,engagement',
            'message' => 'required|string|max:1000',
        ]);
        
        $student->load('user');
        
        // Get the student's email
        $email = $student->user->email ?? null;
        
        if (!$email) {
            return back()->with('error', 'Student email not found.');
        }
        
        // Create notification in database
        \App\Models\StudentNotification::create([
            'student_id' => $student->id,
            'sender_id' => Auth::id(),
            'type' => $request->warning_type === 'performance' ? 'warning' : 'info',
            'title' => 'Academic Warning: ' . ucfirst($request->warning_type),
            'message' => $request->message,
        ]);
        
        // Send the warning email (using Laravel's Mail facade)
        try {
            \Illuminate\Support\Facades\Mail::raw(
                $request->message,
                function ($mail) use ($email, $student, $request) {
                    $mail->to($email)
                         ->subject("Academic Warning: {$request->warning_type}")
                         ->from(config('mail.from.address', 'noreply@meeple.test'), 'Meeple Learning System');
                }
            );
            
            return back()->with('success', "Warning notification sent to {$student->name} ({$email})");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }
    /**
     * Generate comprehensive AI insights for a student
     */
    public function generateAIInsights(\Illuminate\Http\Request $request, Student $student, \App\Services\GeminiInsightsService $geminiService)
    {
        try {
            $insight = $geminiService->generateStudentInsights($student);
            
            return response()->json([
                'success' => true,
                'insight' => \Illuminate\Support\Str::markdown($insight)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate insights: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show module settings page for instructors
     */
    public function showModuleSettings(Module $module)
    {
        // Get module statistics
        $stats = [
            'questions_count' => $module->questions()->count(),
            'students_completed' => StudentModulePerformance::where('module_id', $module->id)->count(),
            'avg_lms' => round(StudentModulePerformance::where('module_id', $module->id)->avg('learning_mastery_score') ?? 0, 1),
            'total_attempts' => \App\Models\LevelIndicatorAttempt::where('module_id', $module->id)->count(),
        ];
        
        // Module styling
        $colorMap = [
            1 => 'from-red-500 to-rose-600',
            2 => 'from-blue-500 to-cyan-600', 
            3 => 'from-green-500 to-emerald-600',
            4 => 'from-purple-500 to-violet-600',
            5 => 'from-amber-500 to-orange-600',
            6 => 'from-indigo-500 to-blue-600',
        ];
        
        $moduleData = [
            'id' => $module->id,
            'title' => $module->name,
            'gradient' => $colorMap[$module->id] ?? 'from-gray-500 to-slate-600',
        ];
        
        return view('dashboard.instructor.module-settings', compact('module', 'moduleData', 'stats'));
    }

    /**
     * Update module settings (max attempts, etc.)
     */
    public function updateModuleSettings(Request $request, Module $module)
    {
        $validated = $request->validate([
            'max_level_indicator_attempts' => 'required|integer|min:1|max:10',
            'description' => 'nullable|string|max:500',
        ]);
        
        $module->max_level_indicator_attempts = $validated['max_level_indicator_attempts'];
        
        if (isset($validated['description'])) {
            $module->description = $validated['description'];
        }
        
        $module->save();
        
        return back()->with('success', 'Module settings updated successfully!');
    }
}
