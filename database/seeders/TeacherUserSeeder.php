<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TeacherUserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'teacher@example.com'],
            [
                'name' => 'Teacher One',
                'password' => Hash::make('password123'),
                'role' => 'teacher'
            ]
        );
    }
}
