<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Creates level_indicator_attempts table to track:
     * - Each attempt a student makes on a module's Level Indicator Exam
     * - All 11 behavioral features captured during the exam
     * - ML prediction results and SHAP explanations
     */
    public function up(): void
    {
        Schema::create('level_indicator_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('attempt_number');  // 1, 2, 3...
            
            // ================================================================
            // TIER 1: Core 6 Features (LMS Components)
            // ================================================================
            $table->decimal('score_percentage', 5, 2)->default(0);          // 0-100
            $table->decimal('hard_question_accuracy', 5, 2)->default(0);    // 0-100
            $table->decimal('hint_usage_percentage', 5, 2)->default(0);     // 0-100
            $table->decimal('avg_confidence', 3, 2)->default(3);            // 1-5
            $table->decimal('answer_changes_rate', 5, 4)->default(0);       // per question
            $table->decimal('tab_switches_rate', 5, 4)->default(0);         // per question
            
            // ================================================================
            // TIER 2: 5 ML Predictor Features
            // ================================================================
            $table->decimal('avg_time_per_question', 8, 2)->default(0);     // seconds
            $table->decimal('review_percentage', 5, 2)->default(0);         // 0-100
            $table->decimal('avg_first_action_latency', 8, 2)->default(0);  // seconds
            $table->decimal('clicks_per_question', 6, 2)->default(0);       // count
            $table->decimal('performance_trend', 6, 2)->default(0);         // -100 to +100
            
            // ================================================================
            // ML Prediction Results
            // ================================================================
            $table->decimal('learning_mastery_score', 5, 2)->default(0);    // 0-100
            $table->enum('mastery_level', ['at_risk', 'developing', 'proficient', 'advanced'])
                  ->default('developing');
            $table->decimal('ml_prediction_confidence', 5, 4)->nullable();  // 0-1
            
            // ================================================================
            // XAI Explanations (Full SHAP breakdown)
            // ================================================================
            $table->json('shap_values')->nullable();              // Full SHAP contribution array
            $table->text('xai_explanation')->nullable();          // Natural language explanation
            $table->text('top_positive_factors')->nullable();     // Comma-separated positive factors
            $table->text('top_negative_factors')->nullable();     // Comma-separated negative factors
            
            // ================================================================
            // Raw exam data for debugging/analysis
            // ================================================================
            $table->json('question_ids')->nullable();             // IDs of questions shown
            $table->json('answers_data')->nullable();             // Raw answer data
            
            $table->timestamps();
            
            // One attempt number per student per module (allows multiple attempts)
            $table->unique(['student_id', 'module_id', 'attempt_number']);
            
            // Index for quick lookup of latest attempt
            $table->index(['student_id', 'module_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('level_indicator_attempts');
    }
};
