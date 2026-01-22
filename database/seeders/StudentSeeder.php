<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Student;
use App\Models\StudentProgress;
use Faker\Factory as Faker;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 50; $i++) {
            // Create user with a real random name
            $name = $faker->name;
            $email = $faker->unique()->safeEmail;

            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('password'), // default password
            ]);

            // Generate unique student_id e.g. w1234567
            $studentId = 'w' . str_pad((string)random_int(0, 9999999), 7, '0', STR_PAD_LEFT);

            // Create student profile
            $student = Student::create([
                'user_id' => $user->id,
                'student_id' => $studentId,
                'name' => $name,
            ]);

            // Random realistic progress
            StudentProgress::create([
                'student_id' => $student->id,
                'exam_1' => rand(35, 100),
                'exam_2' => rand(35, 100),
                'exam_3' => rand(35, 100),
                'attendance_rate' => rand(35, 100),
                'engagement_score' => rand(30, 100) / 10, // 3.0 to 10.0
                'quiz_score' => rand(30, 100),
                'group_work_score' => rand(40, 100),
                'revision_hours' => rand(0, 25),
            ]);
        }
    }
}
