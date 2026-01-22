<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProgress extends Model
{
    use HasFactory;

    protected $table = 'student_progress';

    protected $fillable = [
        'student_id', 'exam_1', 'exam_2', 'exam_3',
        'attendance_rate', 'engagement_score',
        'quiz_score', 'group_work_score', 'revision_hours'
    ];

    public function student() {
        return $this->belongsTo(Student::class);
    }
}
