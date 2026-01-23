<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;

class ModulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            [
                'id' => 1,
                'name' => 'Cyber Security Essentials',
                'description' => 'Fundamentals of cyber security, including threat landscape, defenses, and security policies.',
            ],
            [
                'id' => 2,
                'name' => 'Cloud Computing Fundamentals',
                'description' => 'Introduction to cloud services, deployment models, and major cloud platforms.',
            ],
            [
                'id' => 3,
                'name' => 'Data Science and Analytics',
                'description' => 'Overview of data analysis techniques, machine learning basics, and data visualization.',
            ],
            [
                'id' => 4,
                'name' => 'Network Infrastructure',
                'description' => 'Study of networking concepts, protocols, architecture, and hardware.',
            ],
            [
                'id' => 5,
                'name' => 'IT Project Management',
                'description' => 'Principles of managing IT projects, agile methodologies, and lifecycle management.',
            ],
            [
                'id' => 6,
                'name' => 'Software Engineering Principles',
                'description' => 'Core concepts of software development, SDLC, design patterns, and testing.',
            ],
        ];

        foreach ($modules as $module) {
            Module::updateOrCreate(['id' => $module['id']], $module);
        }
    }
}
