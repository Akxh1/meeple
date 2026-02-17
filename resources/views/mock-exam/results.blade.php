<x-app-layout>
    {{-- Mock Exam - Results Page --}}
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-purple-50 dark:from-slate-900 dark:via-slate-800 dark:to-purple-900">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            {{-- Back Navigation --}}
            <a href="{{ route('mock-exam.show', $module) }}" class="inline-flex items-center gap-2 text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300 mb-6 transition-colors">
                <i class="fas fa-arrow-left"></i>
                Back to Mock Exam
            </a>

            {{-- Score Header --}}
            <div class="bg-gradient-to-r from-purple-500 to-violet-600 rounded-3xl p-8 mb-8 shadow-xl relative overflow-hidden">
                <div class="absolute right-0 top-0 h-full w-1/2 opacity-10">
                    <i class="fas fa-trophy text-white" style="font-size: 200px; position: absolute; right: -20px; top: 50%; transform: translateY(-50%);"></i>
                </div>
                <div class="relative z-10 text-center text-white">
                    <p class="text-white/70 text-sm mb-2">Attempt #{{ $attempt->attempt_number }}</p>
                    <div class="text-7xl font-black mb-2">{{ round($attempt->score_percentage) }}%</div>
                    <div class="text-xl font-medium mb-1">{{ $attempt->total_correct }} / {{ $attempt->total_questions }} correct</div>
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full mt-2">
                        <span class="text-lg">{{ $badge['icon'] }}</span>
                        <span class="font-medium">{{ $badge['label'] }}</span>
                    </div>
                </div>
            </div>

            {{-- Behavioral Features Grid --}}
            <div class="bg-white dark:bg-slate-800/50 rounded-2xl border border-slate-200 dark:border-slate-700/50 p-6 mb-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-chart-radar text-purple-500"></i>
                    Behavioral Features
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                    @php
                        $features = [
                            ['label' => 'Score', 'value' => round($attempt->score_percentage, 1) . '%', 'icon' => 'fa-percentage', 'color' => 'text-blue-500'],
                            ['label' => 'Confidence', 'value' => round($attempt->avg_confidence, 1) . '/5', 'icon' => 'fa-gauge-high', 'color' => 'text-green-500'],
                            ['label' => 'Hint Usage', 'value' => round($attempt->hint_usage_percentage, 1) . '%', 'icon' => 'fa-lightbulb', 'color' => 'text-yellow-500'],
                            ['label' => 'Hard Q Accuracy', 'value' => round($attempt->hard_question_accuracy, 1) . '%', 'icon' => 'fa-brain', 'color' => 'text-red-500'],
                            ['label' => 'Answer Changes', 'value' => round($attempt->answer_changes_rate, 2) . '/q', 'icon' => 'fa-exchange-alt', 'color' => 'text-orange-500'],
                            ['label' => 'Tab Switches', 'value' => round($attempt->tab_switches_rate, 2) . '/q', 'icon' => 'fa-exchange-alt', 'color' => 'text-slate-500'],
                            ['label' => 'Avg Time/Q', 'value' => round($attempt->avg_time_per_question, 0) . 's', 'icon' => 'fa-clock', 'color' => 'text-indigo-500'],
                            ['label' => 'Review %', 'value' => round($attempt->review_percentage, 1) . '%', 'icon' => 'fa-flag', 'color' => 'text-amber-500'],
                            ['label' => 'First Action', 'value' => round($attempt->avg_first_action_latency, 1) . 's', 'icon' => 'fa-bolt', 'color' => 'text-cyan-500'],
                            ['label' => 'Clicks/Q', 'value' => round($attempt->clicks_per_question, 1), 'icon' => 'fa-mouse-pointer', 'color' => 'text-pink-500'],
                            ['label' => 'Performance Trend', 'value' => ($attempt->performance_trend >= 0 ? '+' : '') . round($attempt->performance_trend, 1), 'icon' => 'fa-trending-up', 'color' => $attempt->performance_trend >= 0 ? 'text-emerald-500' : 'text-red-500'],
                        ];
                    @endphp

                    @foreach($features as $feat)
                    <div class="bg-slate-50 dark:bg-slate-700/30 rounded-xl p-3 text-center">
                        <i class="fas {{ $feat['icon'] }} {{ $feat['color'] }} mb-1"></i>
                        <p class="text-lg font-bold text-slate-800 dark:text-white">{{ $feat['value'] }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $feat['label'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Per-Question Breakdown --}}
            <div class="bg-white dark:bg-slate-800/50 rounded-2xl border border-slate-200 dark:border-slate-700/50 p-6 mb-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-list-check text-purple-500"></i>
                    Per-Question Breakdown
                </h3>
                <div class="space-y-3">
                    @php $answersData = $attempt->answers_data ?? []; @endphp
                    @foreach($attempt->question_ids ?? [] as $idx => $qId)
                        @php
                            $question = $questions->get($qId);
                            $answerInfo = $answersData[$qId] ?? [];
                            $isCorrect = $answerInfo['correct'] ?? false;
                            $userAnswer = $answerInfo['user_answer'] ?? '—';
                            $correctText = $answerInfo['correct_answer_text'] ?? '—';
                            $difficulty = $answerInfo['difficulty'] ?? 2;
                        @endphp
                        <div class="flex items-start gap-4 p-4 rounded-xl border
                                    {{ $isCorrect ? 'border-green-200 bg-green-50 dark:border-green-800 dark:bg-green-900/10' : 'border-red-200 bg-red-50 dark:border-red-800 dark:bg-red-900/10' }}">
                            <div class="flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center font-bold
                                        {{ $isCorrect ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                @if($isCorrect)
                                    <i class="fas fa-check"></i>
                                @else
                                    <i class="fas fa-times"></i>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2 mb-1">
                                    <p class="text-sm font-medium text-slate-800 dark:text-white">
                                        Q{{ $idx + 1 }}: {{ $question?->question_text ?? 'Question not found' }}
                                    </p>
                                    <span class="flex-shrink-0 px-2 py-0.5 rounded text-xs font-medium
                                                {{ $difficulty == 1 ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' :
                                                   ($difficulty == 2 ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' :
                                                   'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400') }}">
                                        {{ $difficulty == 1 ? 'Easy' : ($difficulty == 2 ? 'Medium' : 'Hard') }}
                                    </span>
                                </div>
                                @if(!$isCorrect)
                                <div class="mt-2 text-xs space-y-1">
                                    <p class="text-red-600 dark:text-red-400">
                                        <strong>Your answer:</strong>
                                        @if($question && $question->type === 'mcq')
                                            {{ $question->answers->firstWhere('id', $userAnswer)?->answer_text ?? $userAnswer }}
                                        @else
                                            {{ $userAnswer }}
                                        @endif
                                    </p>
                                    <p class="text-green-600 dark:text-green-400">
                                        <strong>Correct answer:</strong> {{ $correctText }}
                                    </p>
                                </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('mock-exam.start', $module) }}"
                   class="flex-1 py-4 bg-gradient-to-r from-purple-500 to-violet-600 hover:from-purple-600 hover:to-violet-700 text-white rounded-xl font-semibold text-center shadow-lg shadow-purple-500/25 transition-all hover:shadow-purple-500/40">
                    <i class="fas fa-redo mr-2"></i>Practice Again
                </a>
                <a href="{{ route('student.module.show', $module) }}"
                   class="flex-1 py-4 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl font-semibold text-center hover:bg-slate-300 dark:hover:bg-slate-600 transition">
                    <i class="fas fa-home mr-2"></i>Back to Module
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
