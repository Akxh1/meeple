<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds a column to track whether prediction used Flask ML API or local fallback.
     */
    public function up(): void
    {
        Schema::table('level_indicator_attempts', function (Blueprint $table) {
            // 'flask_api' = Full ML model prediction via Flask API
            // 'local_fallback' = Local LMS formula when Flask unavailable
            $table->string('prediction_source', 50)->default('local_fallback')->after('ml_prediction_confidence');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('level_indicator_attempts', function (Blueprint $table) {
            $table->dropColumn('prediction_source');
        });
    }
};
