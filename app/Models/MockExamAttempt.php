<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Mock Exam Attempt Model
 * 
 * Tracks individual practice exam attempts (unlimited).
 * Unlike LevelIndicatorAttempt, this does NOT store ML predictions —
 * it is purely for practice with adaptive scaffolding.
 */
class MockExamAttempt extends Model
{
    use HasFactory;

    protected $table = 'mock_exam_attempts';

    protected $fillable = [
        'student_id',
        'module_id',
        'attempt_number',
        'total_correct',
        'total_questions',
        'score_percentage',
        // 11 Behavioral Features
        'hard_question_accuracy',
        'hint_usage_percentage',
        'avg_confidence',
        'answer_changes_rate',
        'tab_switches_rate',
        'avg_time_per_question',
        'review_percentage',
        'avg_first_action_latency',
        'clicks_per_question',
        'performance_trend',
        // Raw data
        'question_ids',
        'answers_data',
    ];

    protected $casts = [
        'score_percentage' => 'decimal:2',
        'hard_question_accuracy' => 'decimal:2',
        'hint_usage_percentage' => 'decimal:2',
        'avg_confidence' => 'decimal:2',
        'answer_changes_rate' => 'decimal:4',
        'tab_switches_rate' => 'decimal:4',
        'avg_time_per_question' => 'decimal:2',
        'review_percentage' => 'decimal:2',
        'avg_first_action_latency' => 'decimal:2',
        'clicks_per_question' => 'decimal:2',
        'performance_trend' => 'decimal:2',
        'question_ids' => 'array',
        'answers_data' => 'array',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Get the latest attempt for a student and module.
     */
    public static function getLatestAttempt(int $studentId, int $moduleId): ?self
    {
        return self::where('student_id', $studentId)
            ->where('module_id', $moduleId)
            ->orderByDesc('attempt_number')
            ->first();
    }

    /**
     * Get the attempt count for a student and module.
     */
    public static function getAttemptCount(int $studentId, int $moduleId): int
    {
        return self::where('student_id', $studentId)
            ->where('module_id', $moduleId)
            ->count();
    }

    /**
     * Get the next attempt number.
     */
    public static function getNextAttemptNumber(int $studentId, int $moduleId): int
    {
        $latest = self::getLatestAttempt($studentId, $moduleId);
        return $latest ? $latest->attempt_number + 1 : 1;
    }

    /**
     * Get all attempts for a student and module.
     */
    public static function getAttemptHistory(int $studentId, int $moduleId)
    {
        return self::where('student_id', $studentId)
            ->where('module_id', $moduleId)
            ->orderByDesc('attempt_number')
            ->get();
    }

    /**
     * Get score badge styling.
     */
    public function getScoreBadge(): array
    {
        $score = $this->score_percentage;

        if ($score >= 80) {
            return [
                'bg' => 'bg-emerald-100 dark:bg-emerald-500/10',
                'text' => 'text-emerald-700 dark:text-emerald-400',
                'icon' => '🏆',
                'label' => 'Excellent',
            ];
        } elseif ($score >= 60) {
            return [
                'bg' => 'bg-blue-100 dark:bg-blue-500/10',
                'text' => 'text-blue-700 dark:text-blue-400',
                'icon' => '📘',
                'label' => 'Good',
            ];
        } elseif ($score >= 40) {
            return [
                'bg' => 'bg-amber-100 dark:bg-amber-500/10',
                'text' => 'text-amber-700 dark:text-amber-400',
                'icon' => '📙',
                'label' => 'Fair',
            ];
        } else {
            return [
                'bg' => 'bg-red-100 dark:bg-red-500/10',
                'text' => 'text-red-700 dark:text-red-400',
                'icon' => '📕',
                'label' => 'Needs Practice',
            ];
        }
    }
}
