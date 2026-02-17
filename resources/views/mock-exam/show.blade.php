<x-app-layout>
    {{-- Mock Exam - Landing/Show Page --}}
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-purple-50 dark:from-slate-900 dark:via-slate-800 dark:to-purple-900">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            {{-- Back Navigation --}}
            <a href="{{ route('student.module.show', $module) }}" class="inline-flex items-center gap-2 text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300 mb-6 transition-colors">
                <i class="fas fa-arrow-left"></i>
                Back to Module
            </a>

            {{-- Header --}}
            <div class="bg-gradient-to-r from-purple-500 to-violet-600 rounded-3xl p-8 mb-8 relative overflow-hidden shadow-xl">
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                            <i class="fas fa-magic text-white text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-white">Mock Exam</h1>
                            <p class="text-white/80">{{ $moduleData['title'] }} — Practice with Adaptive Hints</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Adaptive Hints Disclaimer --}}
            @if(!$hasLevelIndicatorData)
            <div class="bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/30 rounded-2xl p-5 mb-6 shadow-sm">
                <div class="flex items-start gap-3">
                    <i class="fas fa-exclamation-triangle text-amber-500 text-xl mt-0.5"></i>
                    <div>
                        <h4 class="font-semibold text-amber-800 dark:text-amber-300 mb-1">Level Indicator Exam Not Completed</h4>
                        <p class="text-sm text-amber-700 dark:text-amber-400 leading-relaxed">
                            You haven't taken the <strong>Level Indicator Exam</strong> for this module yet. Without it, hints will be <strong>generic one-liners</strong> instead of personalized adaptive hints tailored to your learning level.
                        </p>
                        <a href="{{ route('level-indicator.show', $module) }}"
                           class="inline-flex items-center gap-2 mt-3 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-sm font-medium transition">
                            <i class="fas fa-clipboard-check"></i>
                            Take Level Indicator Exam First
                        </a>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/30 rounded-2xl p-5 mb-6 shadow-sm">
                <div class="flex items-start gap-3">
                    <i class="fas fa-check-circle text-green-500 text-xl mt-0.5"></i>
                    <div>
                        <h4 class="font-semibold text-green-800 dark:text-green-300 mb-1">Adaptive Hints Enabled</h4>
                        <p class="text-sm text-green-700 dark:text-green-400 leading-relaxed">
                            Based on your Level Indicator results (<strong>{{ ucfirst(str_replace('_', ' ', $performance->mastery_level)) }}</strong> — LMS: {{ round($performance->learning_mastery_score, 1) }}), hints will be personalized to your learning level using ML predictions and SHAP analysis.
                        </p>
                    </div>
                </div>
            </div>
            @endif

            {{-- Latest Attempt Summary --}}
            @if($latestAttempt)
            <div class="bg-white dark:bg-slate-800/50 rounded-2xl border border-slate-200 dark:border-slate-700/50 p-6 mb-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-trophy text-amber-500"></i>
                    Latest Result (Attempt {{ $latestAttempt->attempt_number }})
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                    <div class="bg-slate-50 dark:bg-slate-700/30 rounded-xl p-4 text-center">
                        <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ round($latestAttempt->score_percentage, 1) }}%</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Score</p>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-700/30 rounded-xl p-4 text-center">
                        <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ $latestAttempt->total_correct }}/{{ $latestAttempt->total_questions }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Correct</p>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-700/30 rounded-xl p-4 text-center">
                        <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ round($latestAttempt->avg_confidence, 1) }}/5</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Confidence</p>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-700/30 rounded-xl p-4 text-center">
                        @php $scoreBadge = $latestAttempt->getScoreBadge(); @endphp
                        <p class="text-2xl">{{ $scoreBadge['icon'] }}</p>
                        <p class="text-xs {{ $scoreBadge['text'] }} font-medium mt-1">{{ $scoreBadge['label'] }}</p>
                    </div>
                </div>
                <a href="{{ route('mock-exam.results', [$module, $latestAttempt]) }}"
                   class="inline-flex items-center gap-2 text-purple-600 dark:text-purple-400 hover:underline text-sm font-medium">
                    <i class="fas fa-chart-bar"></i>
                    View Full Results
                </a>
            </div>
            @endif

            {{-- Start Button --}}
            <div class="bg-white dark:bg-slate-800/50 rounded-2xl border border-slate-200 dark:border-slate-700/50 p-6 shadow-sm">
                <div class="text-center">
                    <div class="mb-6">
                        <i class="fas fa-magic text-6xl text-purple-500 mb-4"></i>
                        <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">
                            {{ $attemptCount > 0 ? 'Practice Again?' : 'Ready to Practice?' }}
                        </h3>
                        <p class="text-slate-600 dark:text-slate-400 max-w-md mx-auto">
                            Practice with adaptive AI-powered hints based on your learning profile.
                            You have <strong>unlimited</strong> attempts.
                        </p>
                    </div>

                    <div class="space-y-3 text-left max-w-md mx-auto mb-6">
                        <div class="flex items-center gap-3 text-sm text-slate-600 dark:text-slate-400">
                            <i class="fas fa-clock text-purple-500 w-5"></i>
                            <span>10 questions, approximately 15-20 minutes</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-slate-600 dark:text-slate-400">
                            <i class="fas fa-lightbulb text-purple-500 w-5"></i>
                            <span>
                                @if($hasLevelIndicatorData)
                                    Personalized adaptive hints based on your ML profile
                                @else
                                    Generic hints (complete Level Indicator for personalized hints)
                                @endif
                            </span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-slate-600 dark:text-slate-400">
                            <i class="fas fa-sync-alt text-purple-500 w-5"></i>
                            <span>Unlimited attempts — practice as much as you need</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-slate-600 dark:text-slate-400">
                            <i class="fas fa-check-double text-purple-500 w-5"></i>
                            <span>Per-question feedback after submission</span>
                        </div>
                    </div>

                    <a href="{{ route('mock-exam.start', $module) }}"
                       class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-purple-500 to-violet-600 hover:from-purple-600 hover:to-violet-700 text-white rounded-xl font-semibold text-lg shadow-lg shadow-purple-500/25 transition-all hover:shadow-purple-500/40 hover:scale-105">
                        <i class="fas fa-play"></i>
                        {{ $attemptCount > 0 ? 'Start New Practice' : 'Start Mock Exam' }}
                    </a>
                </div>
            </div>

            {{-- Attempt History --}}
            @if($attempts->count() > 0)
            <div class="mt-6 bg-white dark:bg-slate-800/50 rounded-2xl border border-slate-200 dark:border-slate-700/50 p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-history text-slate-500"></i>
                    Practice History
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-slate-700">
                                <th class="text-left py-3 px-2 font-medium text-slate-600 dark:text-slate-400">Attempt</th>
                                <th class="text-left py-3 px-2 font-medium text-slate-600 dark:text-slate-400">Date</th>
                                <th class="text-left py-3 px-2 font-medium text-slate-600 dark:text-slate-400">Score</th>
                                <th class="text-left py-3 px-2 font-medium text-slate-600 dark:text-slate-400">Correct</th>
                                <th class="text-left py-3 px-2 font-medium text-slate-600 dark:text-slate-400">Grade</th>
                                <th class="text-right py-3 px-2 font-medium text-slate-600 dark:text-slate-400">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attempts as $att)
                            @php $aBadge = $att->getScoreBadge(); @endphp
                            <tr class="border-b border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/30">
                                <td class="py-3 px-2 text-slate-800 dark:text-white font-medium">#{{ $att->attempt_number }}</td>
                                <td class="py-3 px-2 text-slate-600 dark:text-slate-400">{{ $att->created_at->format('M j, Y g:i A') }}</td>
                                <td class="py-3 px-2 text-slate-800 dark:text-white">{{ round($att->score_percentage, 1) }}%</td>
                                <td class="py-3 px-2 text-slate-800 dark:text-white">{{ $att->total_correct }}/{{ $att->total_questions }}</td>
                                <td class="py-3 px-2">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium {{ $aBadge['bg'] }} {{ $aBadge['text'] }}">
                                        {{ $aBadge['icon'] }} {{ $aBadge['label'] }}
                                    </span>
                                </td>
                                <td class="py-3 px-2 text-right">
                                    <a href="{{ route('mock-exam.results', [$module, $att]) }}"
                                       class="inline-flex items-center gap-1 text-purple-600 dark:text-purple-400 hover:underline">
                                        <i class="fas fa-eye text-xs"></i>
                                        View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
