<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Adds a difficulty level to questions for:
     * - Hard question accuracy calculation in Level Indicator Exam
     * - Question bank stratification by difficulty
     */
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            // 1 = easy, 2 = medium, 3 = hard
            $table->unsignedTinyInteger('difficulty')->default(2)->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('difficulty');
        });
    }
};
