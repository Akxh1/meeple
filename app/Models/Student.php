<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'student_id', 'name'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function progress() {
        return $this->hasOne(StudentProgress::class);
    }

    public function modulePerformances() {
        return $this->hasMany(StudentModulePerformance::class);
    }
}
