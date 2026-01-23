<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Drop the redundant ml_predicted_level column.
 * 
 * Reason: We use mastery_level directly (set by ML predictions),
 * so ml_predicted_level is unnecessary duplication.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_module_performance', function (Blueprint $table) {
            if (Schema::hasColumn('student_module_performance', 'ml_predicted_level')) {
                $table->dropColumn('ml_predicted_level');
            }
        });
    }

    public function down(): void
    {
        Schema::table('student_module_performance', function (Blueprint $table) {
            $table->unsignedTinyInteger('ml_predicted_level')->nullable()
                ->after('mastery_level')
                ->comment('DEPRECATED: Use mastery_level instead');
        });
    }
};
