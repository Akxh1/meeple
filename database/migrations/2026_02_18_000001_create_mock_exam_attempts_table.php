<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create mock_exam_attempts table.
     * 
     * Tracks practice exam attempts (unlimited).
     * Does NOT store ML prediction results — those live in student_module_performance
     * from the Level Indicator Exam.
     */
    public function up(): void
    {
        Schema::create('mock_exam_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('attempt_number');

            // Score
            $table->unsignedInteger('total_correct')->default(0);
            $table->unsignedInteger('total_questions')->default(10);
            $table->decimal('score_percentage', 5, 2)->default(0);

            // 11 Behavioral Features (same as Level Indicator)
            $table->decimal('hard_question_accuracy', 5, 2)->default(0);
            $table->decimal('hint_usage_percentage', 5, 2)->default(0);
            $table->decimal('avg_confidence', 3, 2)->default(3);
            $table->decimal('answer_changes_rate', 5, 4)->default(0);
            $table->decimal('tab_switches_rate', 5, 4)->default(0);
            $table->decimal('avg_time_per_question', 8, 2)->default(0);
            $table->decimal('review_percentage', 5, 2)->default(0);
            $table->decimal('avg_first_action_latency', 8, 2)->default(0);
            $table->decimal('clicks_per_question', 6, 2)->default(0);
            $table->decimal('performance_trend', 6, 2)->default(0);

            // Raw exam data
            $table->json('question_ids')->nullable();
            $table->json('answers_data')->nullable();

            $table->timestamps();

            $table->unique(['student_id', 'module_id', 'attempt_number']);
            $table->index(['student_id', 'module_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mock_exam_attempts');
    }
};
