<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_module_performance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            
            // Tier 1: Core 6 Features (LMS Components)
            $table->decimal('score_percentage', 5, 2)->default(0);          // 0-100
            $table->decimal('hard_question_accuracy', 5, 2)->default(0);    // 0-100
            $table->decimal('hint_usage_percentage', 5, 2)->default(0);     // 0-100
            $table->decimal('avg_confidence', 3, 2)->default(0);            // 1-5
            $table->decimal('answer_changes_rate', 5, 4)->default(0);       // per question
            $table->decimal('tab_switches_rate', 5, 4)->default(0);         // per question
            
            // Tier 2: 5 ML Predictor Features
            $table->decimal('avg_time_per_question', 8, 2)->default(0);     // seconds
            $table->decimal('review_percentage', 5, 2)->default(0);         // 0-100
            $table->decimal('avg_first_action_latency', 8, 2)->default(0);  // seconds
            $table->decimal('clicks_per_question', 6, 2)->default(0);       // count
            $table->decimal('performance_trend', 6, 2)->default(0);         // -100 to +100
            
            // Calculated Outputs
            $table->decimal('learning_mastery_score', 5, 2)->default(0);    // 0-100
            $table->enum('mastery_level', ['at_risk', 'developing', 'proficient', 'advanced'])
                  ->default('developing');
            
            $table->timestamps();
            
            // One performance record per student per module (can be updated on retakes)
            $table->unique(['student_id', 'module_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_module_performance');
    }
};
