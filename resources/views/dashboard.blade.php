<x-app-layout>
    {{-- Multi-Module Instructor Dashboard with Light/Dark Mode --}}
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-indigo-50 dark:from-slate-900 dark:via-slate-800 dark:to-indigo-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            {{-- Header Section --}}
            <div class="mb-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-800 dark:text-white flex items-center gap-3">
                            <span class="p-2 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg shadow-indigo-500/25">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </span>
                            Instructor Dashboard
                        </h1>
                        <p class="mt-2 text-slate-600 dark:text-slate-400">Multi-module student performance and learning mastery insights</p>
                    </div>
                    <div class="flex gap-3">
                        <button class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-all duration-200 flex items-center gap-2 shadow-lg shadow-indigo-500/25">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Export Data
                        </button>
                    </div>
                </div>
            </div>

            {{-- Overview Stats Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
                {{-- Total Students --}}
                <div class="bg-white dark:bg-slate-800/50 backdrop-blur-xl rounded-2xl p-5 border border-slate-200 dark:border-slate-700/50 hover:border-blue-300 dark:hover:border-indigo-500/50 transition-all duration-300 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2 bg-blue-100 dark:bg-blue-500/10 rounded-xl">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ $totalStudents }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Total Students</p>
                </div>

                {{-- Exam Taken --}}
                <div class="bg-white dark:bg-slate-800/50 backdrop-blur-xl rounded-2xl p-5 border border-slate-200 dark:border-slate-700/50 hover:border-green-300 dark:hover:border-green-500/50 transition-all duration-300 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2 bg-green-100 dark:bg-green-500/10 rounded-xl">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ $examTakenCount }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Students Assessed</p>
                </div>

                {{-- Pending Exam --}}
                <div class="bg-white dark:bg-slate-800/50 backdrop-blur-xl rounded-2xl p-5 border border-slate-200 dark:border-slate-700/50 hover:border-amber-300 dark:hover:border-amber-500/50 transition-all duration-300 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2 bg-amber-100 dark:bg-amber-500/10 rounded-xl">
                            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ $examPendingCount }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Awaiting Assessment</p>
                </div>

                {{-- Total Performance Records --}}
                <div class="bg-white dark:bg-slate-800/50 backdrop-blur-xl rounded-2xl p-5 border border-slate-200 dark:border-slate-700/50 hover:border-indigo-300 dark:hover:border-indigo-500/50 transition-all duration-300 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2 bg-indigo-100 dark:bg-indigo-500/10 rounded-xl">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ $totalPerformanceRecords }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Exam Records</p>
                </div>

                {{-- Average LMS --}}
                <div class="bg-white dark:bg-slate-800/50 backdrop-blur-xl rounded-2xl p-5 border border-slate-200 dark:border-slate-700/50 hover:border-purple-300 dark:hover:border-purple-500/50 transition-all duration-300 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2 bg-purple-100 dark:bg-purple-500/10 rounded-xl">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ number_format($averageLMS, 1) }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Average LMS</p>
                </div>
            </div>

            {{-- Module Overview Cards --}}
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    Module Performance Overview
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($moduleStats as $module)
                    <div class="bg-white dark:bg-slate-800/50 backdrop-blur-xl rounded-xl p-4 border border-slate-200 dark:border-slate-700/50 hover:shadow-md transition-all duration-300">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <h4 class="font-semibold text-slate-800 dark:text-white text-sm truncate" title="{{ $module['name'] }}">{{ $module['name'] }}</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $module['questions_count'] }} questions</p>
                            </div>
                            <span class="px-2 py-1 text-xs font-medium rounded-full 
                                @if($module['avg_lms'] >= 76) bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400
                                @elseif($module['avg_lms'] >= 56) bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400
                                @elseif($module['avg_lms'] >= 36) bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400
                                @else bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400
                                @endif
                            ">{{ $module['avg_lms'] }} LMS</span>
                        </div>
                        <div class="flex items-center gap-4 text-xs">
                            <div>
                                <span class="text-slate-500 dark:text-slate-400">Students:</span>
                                <span class="font-semibold text-slate-700 dark:text-slate-200 ml-1">{{ $module['student_count'] }}</span>
                            </div>
                            <div>
                                <span class="text-slate-500 dark:text-slate-400">Avg Score:</span>
                                <span class="font-semibold text-slate-700 dark:text-slate-200 ml-1">{{ $module['avg_score'] }}%</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Main Content Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                
                {{-- Mastery Level Distribution --}}
                <div class="bg-white dark:bg-slate-800/50 backdrop-blur-xl rounded-2xl p-6 border border-slate-200 dark:border-slate-700/50 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                        </svg>
                        Mastery Distribution
                    </h3>
                    <div class="space-y-4">
                        {{-- Advanced --}}
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-slate-600 dark:text-slate-300 flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                                    Advanced (76-100)
                                </span>
                                <span class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">{{ $masteryDistribution['advanced'] }}</span>
                            </div>
                            <div class="h-2 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-emerald-500 to-emerald-400 rounded-full transition-all duration-500" 
                                     style="width: {{ $totalPerformanceRecords > 0 ? ($masteryDistribution['advanced'] / $totalPerformanceRecords) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        {{-- Proficient --}}
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-slate-600 dark:text-slate-300 flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                                    Proficient (56-75)
                                </span>
                                <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">{{ $masteryDistribution['proficient'] }}</span>
                            </div>
                            <div class="h-2 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-blue-500 to-blue-400 rounded-full transition-all duration-500" 
                                     style="width: {{ $totalPerformanceRecords > 0 ? ($masteryDistribution['proficient'] / $totalPerformanceRecords) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        {{-- Developing --}}
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-slate-600 dark:text-slate-300 flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                                    Developing (36-55)
                                </span>
                                <span class="text-sm font-semibold text-amber-600 dark:text-amber-400">{{ $masteryDistribution['developing'] }}</span>
                            </div>
                            <div class="h-2 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-amber-500 to-amber-400 rounded-full transition-all duration-500" 
                                     style="width: {{ $totalPerformanceRecords > 0 ? ($masteryDistribution['developing'] / $totalPerformanceRecords) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        {{-- At Risk --}}
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-slate-600 dark:text-slate-300 flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-red-500"></span>
                                    At Risk (0-35)
                                </span>
                                <span class="text-sm font-semibold text-red-600 dark:text-red-400">{{ $masteryDistribution['at_risk'] }}</span>
                            </div>
                            <div class="h-2 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-red-500 to-red-400 rounded-full transition-all duration-500" 
                                     style="width: {{ $totalPerformanceRecords > 0 ? ($masteryDistribution['at_risk'] / $totalPerformanceRecords) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Feature Insights --}}
                <div class="lg:col-span-2 bg-white dark:bg-slate-800/50 backdrop-blur-xl rounded-2xl p-6 border border-slate-200 dark:border-slate-700/50 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Class Performance Insights (All Modules)
                    </h3>
                    @if(count($featureAverages) > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="bg-slate-50 dark:bg-slate-700/30 rounded-xl p-4 border border-slate-200 dark:border-slate-600/30">
                            <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ $featureAverages['score_percentage'] }}%</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Avg Score</p>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-700/30 rounded-xl p-4 border border-slate-200 dark:border-slate-600/30">
                            <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ $featureAverages['hard_question_accuracy'] }}%</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Hard Q Accuracy</p>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-700/30 rounded-xl p-4 border border-slate-200 dark:border-slate-600/30">
                            <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ $featureAverages['hint_usage_percentage'] }}%</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Hint Usage</p>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-700/30 rounded-xl p-4 border border-slate-200 dark:border-slate-600/30">
                            <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ $featureAverages['avg_confidence'] }}/5</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Avg Confidence</p>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-700/30 rounded-xl p-4 border border-slate-200 dark:border-slate-600/30">
                            <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ $featureAverages['avg_time_per_question'] }}s</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Time/Question</p>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-700/30 rounded-xl p-4 border border-slate-200 dark:border-slate-600/30">
                            <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ $featureAverages['review_percentage'] }}%</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Review Rate</p>
                        </div>
                    </div>
                    @else
                    <div class="flex flex-col items-center justify-center py-12 text-slate-400">
                        <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <p class="text-lg font-medium">No Performance Data Yet</p>
                        <p class="text-sm mt-1">Insights will appear once students complete exams</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Students Table --}}
            <div class="bg-white dark:bg-slate-800/50 backdrop-blur-xl rounded-2xl border border-slate-200 dark:border-slate-700/50 overflow-hidden shadow-sm">
                <div class="p-6 border-b border-slate-200 dark:border-slate-700/50">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Student Performance (All Modules)
                        </h3>
                        <div class="flex gap-2">
                            <button onclick="filterStudents('all')" class="filter-btn active px-3 py-1.5 text-sm font-medium rounded-lg bg-indigo-600 text-white transition-all" data-filter="all">All</button>
                            <button onclick="filterStudents('completed')" class="filter-btn px-3 py-1.5 text-sm font-medium rounded-lg bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-slate-600 transition-all" data-filter="completed">Assessed</button>
                            <button onclick="filterStudents('pending')" class="filter-btn px-3 py-1.5 text-sm font-medium rounded-lg bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-slate-600 transition-all" data-filter="pending">Pending</button>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 dark:bg-slate-700/30">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Modules</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Best LMS</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Avg Score</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Overall Level</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700/50">
                            @forelse($students as $student)
                                @php
                                    $performances = $student->modulePerformances;
                                    $hasPerformance = $performances->isNotEmpty();
                                    $bestLMS = $hasPerformance ? $performances->max('learning_mastery_score') : null;
                                    $avgScore = $hasPerformance ? $performances->avg('score_percentage') : null;
                                    $overallLevel = $hasPerformance ? ($bestLMS >= 76 ? 'advanced' : ($bestLMS >= 56 ? 'proficient' : ($bestLMS >= 36 ? 'developing' : 'at_risk'))) : null;
                                @endphp
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/20 transition-colors student-row" 
                                    data-status="{{ $hasPerformance ? 'completed' : 'pending' }}">
                                    {{-- Student Info --}}
                                    <td class="px-6 py-4">
                                        <a href="{{ route('instructor.student.show', $student) }}" class="flex items-center gap-3 group">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow group-hover:shadow-lg transition-all">
                                                {{ strtoupper(substr($student->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-medium text-slate-800 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $student->name }}</p>
                                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $student->student_id }}</p>
                                            </div>
                                        </a>
                                    </td>
                                    
                                    {{-- Status --}}
                                    <td class="px-6 py-4 text-center">
                                        @if($hasPerformance)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-500/10 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-500/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                                Assessed
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                Pending
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Modules Count --}}
                                    <td class="px-6 py-4 text-center">
                                        @if($hasPerformance)
                                            <span class="font-semibold text-slate-800 dark:text-white">{{ $performances->count() }}</span>
                                            <span class="text-xs text-slate-500 dark:text-slate-400">/{{ count($modules) }}</span>
                                        @else
                                            <span class="text-slate-400 dark:text-slate-500">0/{{ count($modules) }}</span>
                                        @endif
                                    </td>

                                    {{-- Best LMS --}}
                                    <td class="px-6 py-4 text-center">
                                        @if($hasPerformance)
                                            <span class="text-lg font-bold 
                                                @if($overallLevel === 'advanced') text-emerald-600 dark:text-emerald-400
                                                @elseif($overallLevel === 'proficient') text-blue-600 dark:text-blue-400
                                                @elseif($overallLevel === 'developing') text-amber-600 dark:text-amber-400
                                                @else text-red-600 dark:text-red-400
                                                @endif
                                            ">{{ number_format($bestLMS, 1) }}</span>
                                        @else
                                            <span class="text-slate-400 dark:text-slate-500">—</span>
                                        @endif
                                    </td>

                                    {{-- Avg Score --}}
                                    <td class="px-6 py-4 text-center">
                                        @if($hasPerformance)
                                            <span class="text-slate-700 dark:text-slate-300 font-medium">{{ number_format($avgScore, 1) }}%</span>
                                        @else
                                            <span class="text-slate-400 dark:text-slate-500">—</span>
                                        @endif
                                    </td>

                                    {{-- Overall Level --}}
                                    <td class="px-6 py-4 text-center">
                                        @if($hasPerformance)
                                            @php
                                                $levelConfig = [
                                                    'advanced' => ['bg' => 'bg-emerald-100 dark:bg-emerald-500/10', 'text' => 'text-emerald-700 dark:text-emerald-400', 'border' => 'border-emerald-200 dark:border-emerald-500/20', 'label' => 'Advanced'],
                                                    'proficient' => ['bg' => 'bg-blue-100 dark:bg-blue-500/10', 'text' => 'text-blue-700 dark:text-blue-400', 'border' => 'border-blue-200 dark:border-blue-500/20', 'label' => 'Proficient'],
                                                    'developing' => ['bg' => 'bg-amber-100 dark:bg-amber-500/10', 'text' => 'text-amber-700 dark:text-amber-400', 'border' => 'border-amber-200 dark:border-amber-500/20', 'label' => 'Developing'],
                                                    'at_risk' => ['bg' => 'bg-red-100 dark:bg-red-500/10', 'text' => 'text-red-700 dark:text-red-400', 'border' => 'border-red-200 dark:border-red-500/20', 'label' => 'At Risk'],
                                                ];
                                                $config = $levelConfig[$overallLevel];
                                            @endphp
                                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }} border {{ $config['border'] }}">
                                                {{ $config['label'] }}
                                            </span>
                                        @else
                                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-slate-100 dark:bg-slate-700/50 text-slate-500 dark:text-slate-500 border border-slate-200 dark:border-slate-600/30">
                                                Not Assessed
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-6 py-4 text-center">
                                        <button class="p-2 rounded-lg bg-slate-100 dark:bg-slate-700/50 hover:bg-indigo-500 text-slate-500 dark:text-slate-400 hover:text-white transition-all duration-200" title="View Details">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center text-slate-400">
                                            <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                            </svg>
                                            <p class="text-lg font-medium">No Students Found</p>
                                            <p class="text-sm mt-1">Students will appear here once they register</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript for filtering --}}
    <script>
        function filterStudents(filter) {
            const rows = document.querySelectorAll('.student-row');
            const buttons = document.querySelectorAll('.filter-btn');
            
            // Update button styles
            buttons.forEach(btn => {
                if (btn.dataset.filter === filter) {
                    btn.classList.remove('bg-slate-200', 'dark:bg-slate-700', 'text-slate-700', 'dark:text-slate-300', 'hover:bg-slate-300', 'dark:hover:bg-slate-600');
                    btn.classList.add('bg-indigo-600', 'text-white', 'active');
                } else {
                    btn.classList.remove('bg-indigo-600', 'text-white', 'active');
                    btn.classList.add('bg-slate-200', 'dark:bg-slate-700', 'text-slate-700', 'dark:text-slate-300', 'hover:bg-slate-300', 'dark:hover:bg-slate-600');
                }
            });
            
            // Filter rows
            rows.forEach(row => {
                const status = row.dataset.status;
                if (filter === 'all' || status === filter) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
</x-app-layout>
