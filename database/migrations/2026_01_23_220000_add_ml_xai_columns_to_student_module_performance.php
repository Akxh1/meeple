<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add ML Prediction and XAI columns to student_module_performance table.
 * 
 * New columns:
 * - ml_predicted_level: Bagging Classifier prediction (0-3)
 * - ml_prediction_confidence: Model confidence (0-1)
 * - shap_values: JSON storage of SHAP feature contributions
 * - xai_explanation: Natural language explanation for LLM prompts
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_module_performance', function (Blueprint $table) {
            // ML Prediction fields
            $table->unsignedTinyInteger('ml_predicted_level')->nullable()
                ->after('mastery_level')
                ->comment('ML predicted mastery level: 0=at_risk, 1=developing, 2=proficient, 3=advanced');
            
            $table->decimal('ml_prediction_confidence', 5, 4)->nullable()
                ->after('ml_predicted_level')
                ->comment('Model prediction confidence (0.0000 to 1.0000)');
            
            // XAI fields
            $table->json('shap_values')->nullable()
                ->after('ml_prediction_confidence')
                ->comment('SHAP feature contributions as JSON');
            
            $table->text('xai_explanation')->nullable()
                ->after('shap_values')
                ->comment('Natural language XAI explanation for LLM prompts');
            
            // Top risk factors for quick display
            $table->string('top_positive_factors', 255)->nullable()
                ->after('xai_explanation')
                ->comment('Comma-separated top 3 positive contributing features');
            
            $table->string('top_negative_factors', 255)->nullable()
                ->after('top_positive_factors')
                ->comment('Comma-separated top 3 negative contributing features');
        });
    }

    public function down(): void
    {
        Schema::table('student_module_performance', function (Blueprint $table) {
            $table->dropColumn([
                'ml_predicted_level',
                'ml_prediction_confidence',
                'shap_values',
                'xai_explanation',
                'top_positive_factors',
                'top_negative_factors',
            ]);
        });
    }
};
