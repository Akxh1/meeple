<x-app-layout>
    {{-- Student Detail Page --}}
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-indigo-50 dark:from-slate-900 dark:via-slate-800 dark:to-indigo-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
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

            {{-- Header with Back Button --}}
            <div class="mb-8">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Dashboard
                </a>
                
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-2xl shadow-lg">
                            {{ strtoupper(substr($student->name, 0, 1)) }}
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">{{ $student->name }}</h1>
                            <p class="text-slate-500 dark:text-slate-400">{{ $student->student_id }} • {{ $student->user->email ?? 'No email' }}</p>
                        </div>
                    </div>
                    @if($hasPerformance)
                    <div class="flex items-center gap-3">
                        @php
                            $levelConfig = [
                                'advanced' => ['bg' => 'bg-emerald-100 dark:bg-emerald-500/10', 'text' => 'text-emerald-700 dark:text-emerald-400', 'border' => 'border-emerald-200 dark:border-emerald-500/20', 'label' => '🏆 Advanced'],
                                'proficient' => ['bg' => 'bg-blue-100 dark:bg-blue-500/10', 'text' => 'text-blue-700 dark:text-blue-400', 'border' => 'border-blue-200 dark:border-blue-500/20', 'label' => '📘 Proficient'],
                                'developing' => ['bg' => 'bg-amber-100 dark:bg-amber-500/10', 'text' => 'text-amber-700 dark:text-amber-400', 'border' => 'border-amber-200 dark:border-amber-500/20', 'label' => '📙 Developing'],
                                'at_risk' => ['bg' => 'bg-red-100 dark:bg-red-500/10', 'text' => 'text-red-700 dark:text-red-400', 'border' => 'border-red-200 dark:border-red-500/20', 'label' => '⚠️ At Risk'],
                            ];
                            $config = $levelConfig[$overallStats['mastery_level']];
                        @endphp
                        <span class="inline-flex px-4 py-2 rounded-full text-sm font-medium {{ $config['bg'] }} {{ $config['text'] }} border {{ $config['border'] }}">
                            {{ $config['label'] }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Overall Stats Cards --}}
            @if($hasPerformance)
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 mb-8">
                <div class="bg-white dark:bg-slate-800/50 rounded-xl p-4 border border-slate-200 dark:border-slate-700/50 shadow-sm">
                    <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $overallStats['modules_completed'] }}/{{ $overallStats['total_modules'] }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Modules Completed</p>
                </div>
                <div class="bg-white dark:bg-slate-800/50 rounded-xl p-4 border border-slate-200 dark:border-slate-700/50 shadow-sm">
                    <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $overallStats['best_lms'] }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Best LMS</p>
                </div>
                <div class="bg-white dark:bg-slate-800/50 rounded-xl p-4 border border-slate-200 dark:border-slate-700/50 shadow-sm">
                    <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ $overallStats['avg_score'] }}%</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Avg Score</p>
                </div>
                <div class="bg-white dark:bg-slate-800/50 rounded-xl p-4 border border-slate-200 dark:border-slate-700/50 shadow-sm">
                    <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ $overallStats['avg_confidence'] }}/5</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Avg Confidence</p>
                </div>
                <div class="bg-white dark:bg-slate-800/50 rounded-xl p-4 border border-slate-200 dark:border-slate-700/50 shadow-sm">
                    <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ $overallStats['avg_hint_usage'] }}%</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Hint Usage</p>
                </div>
            </div>
            @else
            <div class="mb-8 p-8 bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 rounded-xl text-center">
                <svg class="w-12 h-12 mx-auto mb-3 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="text-lg font-semibold text-amber-700 dark:text-amber-400">No Assessment Data</h3>
                <p class="text-amber-600 dark:text-amber-300 text-sm mt-1">This student hasn't completed any module exams yet.</p>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Module Performance Table --}}
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-slate-800/50 rounded-2xl border border-slate-200 dark:border-slate-700/50 overflow-hidden shadow-sm">
                        <div class="p-6 border-b border-slate-200 dark:border-slate-700/50">
                            <h3 class="text-lg font-semibold text-slate-800 dark:text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                Per-Module Performance
                            </h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-slate-50 dark:bg-slate-700/30">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Module</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Score</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">LMS</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Level</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Hints</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Time/Q</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 dark:divide-slate-700/50">
                                    @foreach($allModules as $module)
                                        @php
                                            $perf = $modulePerformances->get($module->id);
                                        @endphp
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/20">
                                            <td class="px-4 py-3">
                                                <span class="font-medium text-slate-800 dark:text-white text-sm">{{ Str::limit($module->name, 25) }}</span>
                                            </td>
                                            @if($perf)
                                                <td class="px-4 py-3 text-center">
                                                    <span class="font-semibold text-slate-800 dark:text-white">{{ number_format($perf->score_percentage, 1) }}%</span>
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <span class="font-bold 
                                                        @if($perf->mastery_level === 'advanced') text-emerald-600 dark:text-emerald-400
                                                        @elseif($perf->mastery_level === 'proficient') text-blue-600 dark:text-blue-400
                                                        @elseif($perf->mastery_level === 'developing') text-amber-600 dark:text-amber-400
                                                        @else text-red-600 dark:text-red-400
                                                        @endif
                                                    ">{{ number_format($perf->learning_mastery_score, 1) }}</span>
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    @php
                                                        $lvlConfig = [
                                                            'advanced' => ['bg' => 'bg-emerald-100 dark:bg-emerald-500/10', 'text' => 'text-emerald-700 dark:text-emerald-400'],
                                                            'proficient' => ['bg' => 'bg-blue-100 dark:bg-blue-500/10', 'text' => 'text-blue-700 dark:text-blue-400'],
                                                            'developing' => ['bg' => 'bg-amber-100 dark:bg-amber-500/10', 'text' => 'text-amber-700 dark:text-amber-400'],
                                                            'at_risk' => ['bg' => 'bg-red-100 dark:bg-red-500/10', 'text' => 'text-red-700 dark:text-red-400'],
                                                        ];
                                                        $lvl = $lvlConfig[$perf->mastery_level];
                                                    @endphp
                                                    <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium {{ $lvl['bg'] }} {{ $lvl['text'] }}">
                                                        {{ ucfirst(str_replace('_', ' ', $perf->mastery_level)) }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-center text-sm text-slate-600 dark:text-slate-300">{{ number_format($perf->hint_usage_percentage, 1) }}%</td>
                                                <td class="px-4 py-3 text-center text-sm text-slate-600 dark:text-slate-300">{{ number_format($perf->avg_time_per_question, 0) }}s</td>
                                            @else
                                                <td colspan="5" class="px-4 py-3 text-center">
                                                    <span class="text-slate-400 dark:text-slate-500 text-sm">Not attempted</span>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Actions Panel --}}
                <div class="space-y-6">
                    {{-- Send Warning --}}
                    <div class="bg-white dark:bg-slate-800/50 rounded-2xl border border-slate-200 dark:border-slate-700/50 overflow-hidden shadow-sm">
                        <div class="p-6 border-b border-slate-200 dark:border-slate-700/50">
                            <h3 class="text-lg font-semibold text-slate-800 dark:text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                Send Warning Notification
                            </h3>
                        </div>
                        <form action="{{ route('instructor.student.warn', $student) }}" method="POST" class="p-6">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Warning Type</label>
                                <select name="warning_type" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="performance">📉 Performance Warning</option>
                                    <option value="attendance">🕐 Attendance Warning</option>
                                    <option value="engagement">📊 Engagement Warning</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Message</label>
                                <textarea name="message" rows="4" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Dear {{ $student->name }},

We've noticed that your performance in some modules needs attention...">Dear {{ $student->name }},

We've noticed that your Learning Mastery Score (LMS) indicates areas for improvement. Please consider:

1. Reviewing module materials before attempting exams
2. Using fewer hints to build independent problem-solving skills
3. Taking more time on difficult questions

Please reach out if you need additional support.

Best regards,
Your Instructor</textarea>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Send To</label>
                                <input type="text" value="{{ $student->user->email ?? 'No email available' }}" disabled class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-400 bg-slate-100 text-sm cursor-not-allowed">
                            </div>
                            <button type="submit" class="w-full px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg font-medium transition-all duration-200 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Send Warning Email
                            </button>
                        </form>
                    </div>

                    {{-- Quick Stats --}}
                    @if($hasPerformance)
                    <div class="bg-white dark:bg-slate-800/50 rounded-2xl p-6 border border-slate-200 dark:border-slate-700/50 shadow-sm">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4">Detailed Metrics</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-slate-600 dark:text-slate-400">Avg Time/Question</span>
                                <span class="font-semibold text-slate-800 dark:text-white">{{ $overallStats['avg_time_per_question'] }}s</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-slate-600 dark:text-slate-400">Answer Changes Rate</span>
                                <span class="font-semibold text-slate-800 dark:text-white">{{ $overallStats['total_answer_changes'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-slate-600 dark:text-slate-400">Review Percentage</span>
                                <span class="font-semibold text-slate-800 dark:text-white">{{ $overallStats['avg_review_percentage'] }}%</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-slate-600 dark:text-slate-400">Average LMS</span>
                                <span class="font-semibold text-slate-800 dark:text-white">{{ $overallStats['avg_lms'] }}</span>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- XAI Explanation Panel --}}
                    @if($hasPerformance)
                    <div class="bg-gradient-to-br from-violet-50 to-purple-50 dark:from-violet-900/20 dark:to-purple-900/20 rounded-2xl p-6 border border-violet-200 dark:border-violet-500/30 shadow-sm">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                            XAI Insights
                        </h3>
                        <p class="text-sm text-slate-600 dark:text-slate-300 mb-4">
                            AI-powered analysis of this student's learning patterns based on SHAP feature importance.
                        </p>
                        
                        @php
                            // Get the first module performance for XAI display
                            $firstPerf = $student->modulePerformances->first();
                        @endphp
                        
                        @if($firstPerf)
                            {{-- Display REAL SHAP-based XAI insights from database --}}
                            <div class="space-y-3 mb-4">
                                {{-- Strengths from ML analysis --}}
                                <div class="flex items-start gap-2">
                                    <span class="text-green-500 text-lg">✓</span>
                                    <div>
                                        <span class="text-xs font-medium text-slate-500 dark:text-slate-400">STRENGTHS</span>
                                        <p class="text-sm text-slate-700 dark:text-slate-200">
                                            @if($firstPerf->top_positive_factors)
                                                {{ str_replace('_', ' ', ucwords($firstPerf->top_positive_factors, ',_')) }}
                                            @else
                                                {{-- Fallback if no ML data --}}
                                                @if($firstPerf->score_percentage >= 60)
                                                    Score of {{ number_format($firstPerf->score_percentage, 1) }}%
                                                @endif
                                                @if($firstPerf->hint_usage_percentage <= 30)
                                                    • Low hint dependency
                                                @endif
                                                @if(!($firstPerf->score_percentage >= 60) && !($firstPerf->hint_usage_percentage <= 30))
                                                    Engagement with material
                                                @endif
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                {{-- Areas for Growth from ML analysis --}}
                                <div class="flex items-start gap-2">
                                    <span class="text-amber-500 text-lg">!</span>
                                    <div>
                                        <span class="text-xs font-medium text-slate-500 dark:text-slate-400">AREAS FOR GROWTH</span>
                                        <p class="text-sm text-slate-700 dark:text-slate-200">
                                            @if($firstPerf->top_negative_factors)
                                                {{ str_replace('_', ' ', ucwords($firstPerf->top_negative_factors, ',_')) }}
                                            @else
                                                @if($firstPerf->hint_usage_percentage > 50)
                                                    High hint usage needs attention
                                                @elseif($firstPerf->score_percentage < 50)
                                                    Score improvement needed
                                                @else
                                                    Continue building on progress
                                                @endif
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                {{-- Full XAI Explanation --}}
                                @if($firstPerf->xai_explanation)
                                <div class="mt-3 p-3 bg-slate-50 dark:bg-slate-700/30 rounded-lg">
                                    <span class="text-xs font-medium text-violet-600 dark:text-violet-400 uppercase">AI Analysis</span>
                                    <p class="text-xs text-slate-600 dark:text-slate-300 mt-1">
                                        {{ $firstPerf->xai_explanation }}
                                    </p>
                                </div>
                                @endif
                            </div>

                            {{-- ML Prediction Status --}}
                            @if($firstPerf->mastery_level && $firstPerf->ml_prediction_confidence)
                            <div class="mt-4 p-3 bg-white/50 dark:bg-slate-800/50 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-medium text-violet-600 dark:text-violet-400 uppercase">ML Prediction</span>
                                    <span class="text-xs text-slate-500">Confidence: {{ number_format(($firstPerf->ml_prediction_confidence ?? 0) * 100, 1) }}%</span>
                                </div>
                                <p class="text-sm text-slate-700 dark:text-slate-200 mt-1">
                                    @php
                                        $mlLevels = ['at_risk' => 'At Risk', 'developing' => 'Developing', 'proficient' => 'Proficient', 'advanced' => 'Advanced'];
                                        $mlLevel = $mlLevels[$firstPerf->mastery_level] ?? ucfirst(str_replace('_', ' ', $firstPerf->mastery_level));
                                    @endphp
                                    Predicted as <strong>{{ $mlLevel }}</strong> (LMS: {{ number_format($firstPerf->learning_mastery_score, 1) }})
                                </p>
                            </div>
                            @elseif($firstPerf->mastery_level)
                            <div class="mt-4 p-3 bg-slate-100 dark:bg-slate-700/50 rounded-lg">
                                <span class="text-xs text-slate-500 dark:text-slate-400">
                                    📊 Level: {{ ucfirst(str_replace('_', ' ', $firstPerf->mastery_level)) }} (LMS: {{ number_format($firstPerf->learning_mastery_score, 1) }})
                                </span>
                            </div>
                            @else
                            <div class="mt-4 p-3 bg-slate-100 dark:bg-slate-700/50 rounded-lg text-center">
                                <span class="text-xs text-slate-500 dark:text-slate-400">
                                    📊 Complete Level Indicator Exam for ML prediction
                                </span>
                            </div>
                            @endif
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
