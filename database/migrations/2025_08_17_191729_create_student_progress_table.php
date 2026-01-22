<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('student_progress', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            
            $table->integer('exam_1');
            $table->integer('exam_2');
            $table->integer('exam_3');
            $table->integer('attendance_rate'); // % e.g. 95
            $table->decimal('engagement_score', 4, 1); // e.g. 8.2
            $table->integer('quiz_score');
            $table->integer('group_work_score');
            $table->integer('revision_hours');
            
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('student_progress');
    }
};