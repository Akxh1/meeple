<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Adds max_level_indicator_attempts to modules table.
     * This allows instructors to configure how many times 
     * students can attempt the Level Indicator Exam per module.
     */
    public function up(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->unsignedInteger('max_level_indicator_attempts')
                  ->default(3)
                  ->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->dropColumn('max_level_indicator_attempts');
        });
    }
};
