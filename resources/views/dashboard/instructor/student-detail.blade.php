<x-app-layout>
    {{-- Student Detail Page - HCI Optimized Layout --}}
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-indigo-50 dark:from-slate-900 dark:via-slate-800 dark:to-indigo-900">
        <div class="w-full px-10 lg:px-16 xl:px-24 py-8">
            
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 rounded-xl text-green-700 dark:text-green-400">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-100 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-xl text-red-700 dark:text-red-400">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Header Section --}}
            <div class="mb-8">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 mb-4 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Dashboard
                </a>
                
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-indigo-500/25">
                            {{ strtoupper(substr($student->name, 0, 1)) }}
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">{{ $student->name }}</h1>
                            <p class="text-slate-500 dark:text-slate-400 text-sm">{{ $student->student_id }} • {{ $student->user->email ?? 'No email' }}</p>
                        </div>
                    </div>
                    @if($hasPerformance)
                    @php
                        $levelConfig = [
                            'advanced' => ['bg' => 'bg-gradient-to-r from-emerald-500 to-teal-500', 'label' => '🏆 Advanced'],
                            'proficient' => ['bg' => 'bg-gradient-to-r from-blue-500 to-cyan-500', 'label' => '📘 Proficient'],
                            'developing' => ['bg' => 'bg-gradient-to-r from-amber-500 to-orange-500', 'label' => '📙 Developing'],
                            'at_risk' => ['bg' => 'bg-gradient-to-r from-red-500 to-rose-500', 'label' => '⚠️ At Risk'],
                        ];
                        $config = $levelConfig[$overallStats['mastery_level']];
                    @endphp
                    <span class="inline-flex px-4 py-2 rounded-xl text-sm font-semibold text-white {{ $config['bg'] }} shadow-lg">
                        {{ $config['label'] }}
                    </span>
                    @endif
                </div>
            </div>

            {{-- Key Metrics Bar --}}
            @if($hasPerformance)
            <div class="grid grid-cols-3 md:grid-cols-6 gap-3 mb-8">
                @php
                    $metrics = [
                        ['value' => $overallStats['modules_completed'] . '/' . $overallStats['total_modules'], 'label' => 'Completed', 'color' => 'text-indigo-600 dark:text-indigo-400'],
                        ['value' => number_format($overallStats['best_lms'], 1), 'label' => 'Best LMS', 'color' => 'text-emerald-600 dark:text-emerald-400'],
                        ['value' => number_format($overallStats['avg_lms'], 1), 'label' => 'Avg LMS', 'color' => 'text-purple-600 dark:text-purple-400'],
                        ['value' => $overallStats['avg_score'] . '%', 'label' => 'Avg Score', 'color' => 'text-slate-800 dark:text-white'],
                        ['value' => $overallStats['avg_confidence'] . '/5', 'label' => 'Confidence', 'color' => 'text-slate-800 dark:text-white'],
                        ['value' => $overallStats['avg_hint_usage'] . '%', 'label' => 'Hints Used', 'color' => 'text-slate-800 dark:text-white'],
                    ];
                @endphp
                @foreach($metrics as $metric)
                <div class="bg-white dark:bg-slate-800/60 rounded-xl p-4 border border-slate-200/80 dark:border-slate-700/50 text-center hover:shadow-md transition-shadow">
                    <p class="text-xl font-bold {{ $metric['color'] }}">{{ $metric['value'] }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $metric['label'] }}</p>
                </div>
                @endforeach
            </div>
            @else
            <div class="mb-8 p-8 bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 rounded-2xl text-center">
                <svg class="w-12 h-12 mx-auto mb-3 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="text-lg font-semibold text-amber-700 dark:text-amber-400">No Assessment Data</h3>
                <p class="text-amber-600 dark:text-amber-300 text-sm mt-1">This student hasn't completed any module exams yet.</p>
            </div>
            @endif

            {{-- ========== MAIN CONTENT: 2/3 + 1/3 LAYOUT ========== --}}
            @if($hasPerformance)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                
                {{-- LEFT: Performance Table (2/3) --}}
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-slate-800/60 rounded-2xl border border-slate-200/80 dark:border-slate-700/50 overflow-hidden shadow-sm">
                        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-700/20">
                            <h3 class="text-base font-semibold text-slate-800 dark:text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                                </svg>
                                Module Performance
                            </h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-slate-50 dark:bg-slate-700/30">
                                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Module</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Score</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">LMS</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Level</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Hints</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                                    @foreach($allModules as $module)
                                        @php $perf = $modulePerformances->get($module->id); @endphp
                                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/20 transition-colors">
                                            <td class="px-5 py-4">
                                                <span class="font-medium text-slate-800 dark:text-white text-sm">{{ $module->name }}</span>
                                            </td>
                                            @if($perf)
                                                <td class="px-4 py-4 text-center">
                                                    <span class="font-semibold text-slate-700 dark:text-slate-200">{{ number_format($perf->score_percentage, 1) }}%</span>
                                                </td>
                                                <td class="px-4 py-4 text-center">
                                                    <span class="font-bold text-lg
                                                        @if($perf->mastery_level === 'advanced') text-emerald-600 dark:text-emerald-400
                                                        @elseif($perf->mastery_level === 'proficient') text-blue-600 dark:text-blue-400
                                                        @elseif($perf->mastery_level === 'developing') text-amber-600 dark:text-amber-400
                                                        @else text-red-600 dark:text-red-400
                                                        @endif
                                                    ">{{ number_format($perf->learning_mastery_score, 1) }}</span>
                                                </td>
                                                <td class="px-4 py-4 text-center">
                                                    @php
                                                        $lvlConfig = [
                                                            'advanced' => ['bg' => 'bg-emerald-100 dark:bg-emerald-500/20', 'text' => 'text-emerald-700 dark:text-emerald-400'],
                                                            'proficient' => ['bg' => 'bg-blue-100 dark:bg-blue-500/20', 'text' => 'text-blue-700 dark:text-blue-400'],
                                                            'developing' => ['bg' => 'bg-amber-100 dark:bg-amber-500/20', 'text' => 'text-amber-700 dark:text-amber-400'],
                                                            'at_risk' => ['bg' => 'bg-red-100 dark:bg-red-500/20', 'text' => 'text-red-700 dark:text-red-400'],
                                                        ];
                                                        $lvl = $lvlConfig[$perf->mastery_level];
                                                    @endphp
                                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-medium {{ $lvl['bg'] }} {{ $lvl['text'] }}">
                                                        {{ ucfirst(str_replace('_', ' ', $perf->mastery_level)) }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-4 text-center text-sm text-slate-600 dark:text-slate-300">{{ number_format($perf->hint_usage_percentage, 1) }}%</td>
                                            @else
                                                <td colspan="4" class="px-4 py-4 text-center">
                                                    <span class="text-slate-400 dark:text-slate-500 text-sm italic">Not attempted</span>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- RIGHT: AI Insights Card (1/3) --}}
                <div class="lg:col-span-1">
                    @if(!empty($aggregatedXAI))
                    <div class="bg-gradient-to-br from-violet-500 via-purple-500 to-indigo-600 rounded-2xl shadow-xl shadow-purple-500/20 overflow-hidden h-full">
                        {{-- Header --}}
                        <div class="px-5 py-4 border-b border-white/10">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-white">AI Learning Insights</h3>
                                    <p class="text-xs text-white/70">Based on {{ $aggregatedXAI['modules_count'] }} modules</p>
                                </div>
                            </div>
                        </div>

                        {{-- Stats Row --}}
                        <div class="grid grid-cols-2 gap-3 px-5 py-4 bg-white/5">
                            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 text-center">
                                <p class="text-2xl font-bold text-white">{{ $aggregatedXAI['avg_lms'] }}</p>
                                <p class="text-xs text-white/70">Avg LMS</p>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 text-center">
                                <p class="text-2xl font-bold text-white">{{ $aggregatedXAI['avg_confidence'] }}%</p>
                                <p class="text-xs text-white/70">ML Confidence</p>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="px-5 py-4 space-y-4">
                            {{-- Strengths --}}
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="w-5 h-5 rounded-full bg-emerald-400 flex items-center justify-center text-white text-xs">✓</span>
                                    <h4 class="text-sm font-semibold text-white">Top Strengths</h4>
                                </div>
                                @if(!empty($aggregatedXAI['positive_factors']))
                                    <div class="space-y-1.5">
                                        @foreach(array_slice($aggregatedXAI['positive_factors'], 0, 3, true) as $factor => $count)
                                            <div class="flex items-center justify-between py-2 px-3 bg-white/10 backdrop-blur-sm rounded-lg">
                                                <span class="text-sm text-white/90">{{ ucwords(str_replace('_', ' ', $factor)) }}</span>
                                                <span class="text-xs font-medium text-emerald-300 bg-emerald-400/20 px-2 py-0.5 rounded">{{ $count }}/{{ $aggregatedXAI['modules_count'] }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-sm text-white/50 italic">Analyzing...</p>
                                @endif
                            </div>

                            {{-- Areas for Growth --}}
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="w-5 h-5 rounded-full bg-amber-400 flex items-center justify-center text-white text-xs font-bold">!</span>
                                    <h4 class="text-sm font-semibold text-white">Focus Areas</h4>
                                </div>
                                @if(!empty($aggregatedXAI['negative_factors']))
                                    <div class="space-y-1.5">
                                        @foreach(array_slice($aggregatedXAI['negative_factors'], 0, 3, true) as $factor => $count)
                                            <div class="flex items-center justify-between py-2 px-3 bg-white/10 backdrop-blur-sm rounded-lg">
                                                <span class="text-sm text-white/90">{{ ucwords(str_replace('_', ' ', $factor)) }}</span>
                                                <span class="text-xs font-medium text-amber-300 bg-amber-400/20 px-2 py-0.5 rounded">{{ $count }}/{{ $aggregatedXAI['modules_count'] }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-sm text-white/50 italic">No issues detected</p>
                                @endif
                            </div>
                        </div>

                        {{-- Level Distribution Footer --}}
                        @if(!empty($aggregatedXAI['level_distribution']))
                        <div class="px-5 py-3 border-t border-white/10 bg-white/5">
                            <div class="flex items-center justify-center gap-3 flex-wrap">
                                @php
                                    $distIcons = ['advanced' => '🏆', 'proficient' => '📘', 'developing' => '📙', 'at_risk' => '⚠️'];
                                @endphp
                                @foreach($aggregatedXAI['level_distribution'] as $level => $count)
                                    <div class="flex items-center gap-1 text-white/80 text-sm">
                                        <span>{{ $distIcons[$level] ?? '📊' }}</span>
                                        <span class="font-medium">{{ $count }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- ========== PER-MODULE XAI ACCORDION ========== --}}
            @if($hasPerformance)
            <div class="mb-8" x-data="{ openModule: null }">
                <div class="bg-white dark:bg-slate-800/60 rounded-2xl border border-slate-200/80 dark:border-slate-700/50 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-700/20">
                        <h3 class="text-base font-semibold text-slate-800 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Detailed XAI Analysis
                            <span class="text-xs font-normal text-slate-500 dark:text-slate-400 ml-1">Click module to expand</span>
                        </h3>
                    </div>
                    
                    <div class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        @foreach($student->modulePerformances as $perf)
                            <div class="border-l-4 transition-all duration-200
                                @if($perf->mastery_level === 'advanced') border-l-emerald-500
                                @elseif($perf->mastery_level === 'proficient') border-l-blue-500
                                @elseif($perf->mastery_level === 'developing') border-l-amber-500
                                @else border-l-red-500
                                @endif
                            ">
                                <button 
                                    @click="openModule = openModule === {{ $perf->module_id }} ? null : {{ $perf->module_id }}"
                                    class="w-full px-6 py-4 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors"
                                >
                                    <div class="flex items-center gap-4">
                                        @php $lvlIcon = ['advanced' => '🏆', 'proficient' => '📘', 'developing' => '📙', 'at_risk' => '⚠️']; @endphp
                                        <span class="text-xl">{{ $lvlIcon[$perf->mastery_level] ?? '📊' }}</span>
                                        <div class="text-left">
                                            <h4 class="font-medium text-slate-800 dark:text-white text-sm">{{ $perf->module->name ?? 'Module ' . $perf->module_id }}</h4>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                                LMS: {{ number_format($perf->learning_mastery_score, 1) }} • Score: {{ number_format($perf->score_percentage, 1) }}%
                                            </p>
                                        </div>
                                    </div>
                                    <svg class="w-4 h-4 text-slate-400 transform transition-transform duration-200" :class="{ 'rotate-180': openModule === {{ $perf->module_id }} }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                
                                <div x-show="openModule === {{ $perf->module_id }}" x-collapse class="px-6 pb-5">
                                    <div class="bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-700/30 dark:to-slate-700/10 rounded-xl p-5">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <div class="flex items-center gap-2 mb-2">
                                                    <span class="text-emerald-500">✓</span>
                                                    <h5 class="text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase">Strengths</h5>
                                                </div>
                                                <p class="text-sm text-slate-700 dark:text-slate-200">
                                                    {{ $perf->top_positive_factors ? ucwords(str_replace('_', ' ', $perf->top_positive_factors)) : 'No data available' }}
                                                </p>
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-2 mb-2">
                                                    <span class="text-amber-500">!</span>
                                                    <h5 class="text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase">Areas for Growth</h5>
                                                </div>
                                                <p class="text-sm text-slate-700 dark:text-slate-200">
                                                    {{ $perf->top_negative_factors ? ucwords(str_replace('_', ' ', $perf->top_negative_factors)) : 'No issues identified' }}
                                                </p>
                                            </div>
                                        </div>
                                        @if($perf->xai_explanation)
                                        <div class="mt-4 p-4 bg-white dark:bg-slate-800/50 rounded-lg border border-slate-200 dark:border-slate-600/50">
                                            <p class="text-xs font-semibold text-purple-600 dark:text-purple-400 uppercase mb-1">AI Analysis</p>
                                            <p class="text-sm text-slate-600 dark:text-slate-300">{{ $perf->xai_explanation }}</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- ========== BOTTOM: ACTIONS ========== --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Detailed Metrics --}}
                @if($hasPerformance)
                <div class="bg-white dark:bg-slate-800/60 rounded-2xl p-6 border border-slate-200/80 dark:border-slate-700/50 shadow-sm">
                    <h3 class="text-base font-semibold text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Behavioral Metrics
                    </h3>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="py-3 px-4 bg-slate-50 dark:bg-slate-700/30 rounded-xl">
                            <p class="text-xs text-slate-500 dark:text-slate-400">Avg Time/Question</p>
                            <p class="text-lg font-semibold text-slate-800 dark:text-white mt-1">{{ $overallStats['avg_time_per_question'] }}s</p>
                        </div>
                        <div class="py-3 px-4 bg-slate-50 dark:bg-slate-700/30 rounded-xl">
                            <p class="text-xs text-slate-500 dark:text-slate-400">Answer Changes</p>
                            <p class="text-lg font-semibold text-slate-800 dark:text-white mt-1">{{ $overallStats['total_answer_changes'] }}</p>
                        </div>
                        <div class="py-3 px-4 bg-slate-50 dark:bg-slate-700/30 rounded-xl">
                            <p class="text-xs text-slate-500 dark:text-slate-400">Review Rate</p>
                            <p class="text-lg font-semibold text-slate-800 dark:text-white mt-1">{{ $overallStats['avg_review_percentage'] }}%</p>
                        </div>
                        <div class="py-3 px-4 bg-slate-50 dark:bg-slate-700/30 rounded-xl">
                            <p class="text-xs text-slate-500 dark:text-slate-400">Average LMS</p>
                            <p class="text-lg font-semibold text-purple-600 dark:text-purple-400 mt-1">{{ $overallStats['avg_lms'] }}</p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Send Warning --}}
                <div class="bg-white dark:bg-slate-800/60 rounded-2xl border border-slate-200/80 dark:border-slate-700/50 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700/50 bg-amber-50 dark:bg-amber-500/10">
                        <h3 class="text-base font-semibold text-slate-800 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            Send Notification
                        </h3>
                    </div>
                    <form action="{{ route('instructor.student.warn', $student) }}" method="POST" class="p-6">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Type</label>
                                <select name="warning_type" class="w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    <option value="performance">📉 Performance</option>
                                    <option value="attendance">🕐 Attendance</option>
                                    <option value="engagement">📊 Engagement</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Message</label>
                                <textarea name="message" rows="3" class="w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 text-sm">Dear {{ $student->name }}, We've noticed areas for improvement in your learning. Please reach out if you need support.</textarea>
                            </div>
                            <button type="submit" class="w-full px-4 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white rounded-xl font-medium transition-all duration-200 shadow-lg shadow-amber-500/25 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Send to {{ $student->user->email ?? 'student' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
