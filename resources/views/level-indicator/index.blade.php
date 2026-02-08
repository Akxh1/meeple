<x-app-layout>
    {{-- Level Indicator Exam - Main Exam Interface --}}
    <div x-data="levelIndicatorExam()" 
         x-init="init()"
         @visibilitychange.window="handleVisibilityChange()"
         class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-blue-900">
        
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            
            {{-- Header Bar --}}
            <div class="bg-gradient-to-r {{ $moduleData['gradient'] }} rounded-2xl p-4 mb-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <i class="fas fa-clipboard-check text-white"></i>
                        </div>
                        <div>
                            <h1 class="text-lg font-bold text-white">Level Indicator Exam</h1>
                            <p class="text-white/80 text-sm">{{ $moduleData['title'] }} • Attempt {{ $attemptNumber }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 text-white">
                        <div class="text-center">
                            <div class="text-2xl font-bold" x-text="formatTime(elapsedTime)">00:00</div>
                            <div class="text-xs text-white/70">Time</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold" x-text="(current + 1) + '/' + questions.length">1/10</div>
                            <div class="text-xs text-white/70">Question</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Progress Bar --}}
            <div class="mb-6">
                <div class="h-2 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full transition-all duration-300"
                         :style="'width: ' + ((current + 1) / questions.length * 100) + '%'"></div>
                </div>
                <div class="flex justify-between mt-2 text-xs text-slate-500 dark:text-slate-400">
                    <span>Question <span x-text="current + 1"></span></span>
                    <span x-text="Math.round((current + 1) / questions.length * 100) + '% Complete'"></span>
                </div>
            </div>

            {{-- Question Card --}}
            <template x-if="questions.length > 0">
                <div class="bg-white dark:bg-slate-800/80 rounded-3xl shadow-xl overflow-hidden border border-slate-200 dark:border-slate-700">
                    
                    {{-- Question Header --}}
                    <div class="bg-slate-50 dark:bg-slate-800 px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="w-10 h-10 rounded-xl bg-blue-500 text-white flex items-center justify-center font-bold"
                                      x-text="current + 1"></span>
                                <div>
                                    <span class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide"
                                          x-text="questions[current].type.replace('_', ' ')"></span>
                                    <span class="ml-2 px-2 py-0.5 rounded text-xs font-medium"
                                          :class="{
                                              'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400': questions[current].difficulty == 1,
                                              'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400': questions[current].difficulty == 2,
                                              'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400': questions[current].difficulty >= 3
                                          }"
                                          x-text="questions[current].difficulty == 1 ? 'Easy' : (questions[current].difficulty == 2 ? 'Medium' : 'Hard')">
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                {{-- Review Flag --}}
                                <button type="button" @click="toggleReview(questions[current].id)"
                                        class="p-2 rounded-lg transition"
                                        :class="tracking.reviewMarked.has(questions[current].id) 
                                            ? 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400' 
                                            : 'bg-slate-100 text-slate-400 dark:bg-slate-700 hover:bg-amber-50 hover:text-amber-500'">
                                    <i class="fas fa-flag"></i>
                                </button>
                                {{-- Hint Button --}}
                                <button type="button" @click="requestHint(questions[current].id)"
                                        class="p-2 rounded-lg bg-slate-100 dark:bg-slate-700 text-yellow-500 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 transition"
                                        title="Request Hint (affects LMS)">
                                    <i class="fas fa-lightbulb"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Question Body --}}
                    <div class="p-6" @click="recordClick(questions[current].id)">
                        <h2 class="text-xl font-semibold text-slate-800 dark:text-white mb-6"
                            x-text="questions[current].question_text"></h2>
                        
                        {{-- MCQ Options --}}
                        <template x-if="questions[current].type === 'mcq'">
                            <div class="space-y-3">
                                <template x-for="answer in questions[current].answers" :key="answer.id">
                                    <label class="flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all"
                                           :class="userAnswers[questions[current].id] == answer.id 
                                               ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' 
                                               : 'border-slate-200 dark:border-slate-700 hover:border-blue-300 dark:hover:border-blue-700'"
                                           @click="selectAnswer(questions[current].id, answer.id)">
                                        <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition"
                                             :class="userAnswers[questions[current].id] == answer.id 
                                                 ? 'border-blue-500 bg-blue-500' 
                                                 : 'border-slate-300 dark:border-slate-600'">
                                            <div class="w-2 h-2 rounded-full bg-white"
                                                 x-show="userAnswers[questions[current].id] == answer.id"></div>
                                        </div>
                                        <span class="flex-1 text-slate-700 dark:text-slate-200" x-text="answer.answer_text"></span>
                                    </label>
                                </template>
                            </div>
                        </template>

                        {{-- True/False --}}
                        <template x-if="questions[current].type === 'true_false'">
                            <div class="flex gap-4">
                                <label class="flex-1 flex items-center justify-center gap-3 p-6 rounded-xl border-2 cursor-pointer transition-all"
                                       :class="userAnswers[questions[current].id] === 'true' 
                                           ? 'border-green-500 bg-green-50 dark:bg-green-900/20' 
                                           : 'border-slate-200 dark:border-slate-700 hover:border-green-300'"
                                       @click="selectAnswer(questions[current].id, 'true')">
                                    <i class="fas fa-check-circle text-2xl"
                                       :class="userAnswers[questions[current].id] === 'true' ? 'text-green-500' : 'text-slate-400'"></i>
                                    <span class="text-lg font-medium text-slate-700 dark:text-slate-200">True</span>
                                </label>
                                <label class="flex-1 flex items-center justify-center gap-3 p-6 rounded-xl border-2 cursor-pointer transition-all"
                                       :class="userAnswers[questions[current].id] === 'false' 
                                           ? 'border-red-500 bg-red-50 dark:bg-red-900/20' 
                                           : 'border-slate-200 dark:border-slate-700 hover:border-red-300'"
                                       @click="selectAnswer(questions[current].id, 'false')">
                                    <i class="fas fa-times-circle text-2xl"
                                       :class="userAnswers[questions[current].id] === 'false' ? 'text-red-500' : 'text-slate-400'"></i>
                                    <span class="text-lg font-medium text-slate-700 dark:text-slate-200">False</span>
                                </label>
                            </div>
                        </template>

                        {{-- Fill in Blank --}}
                        <template x-if="questions[current].type === 'fill_in_blank'">
                            <div>
                                <input type="text" 
                                       class="w-full p-4 text-lg rounded-xl border-2 border-slate-200 dark:border-slate-700 dark:bg-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition"
                                       placeholder="Type your answer here..."
                                       x-model="userAnswers[questions[current].id]"
                                       @input="recordAnswerChange(questions[current].id)">
                            </div>
                        </template>
                    </div>

                    {{-- Confidence Rating --}}
                    <div class="px-6 pb-6">
                        <div class="bg-slate-50 dark:bg-slate-700/30 rounded-xl p-4">
                            <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-3">
                                <i class="fas fa-gauge-high mr-2"></i>
                                How confident are you in your answer?
                            </label>
                            <div class="flex justify-between gap-2">
                                <template x-for="level in [1, 2, 3, 4, 5]" :key="level">
                                    <button type="button"
                                            @click="setConfidence(questions[current].id, level)"
                                            class="flex-1 py-3 rounded-lg font-medium text-sm transition-all"
                                            :class="tracking.confidenceRatings[questions[current].id] === level 
                                                ? 'bg-blue-500 text-white shadow-lg' 
                                                : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-600 hover:border-blue-300'">
                                        <span x-text="level"></span>
                                        <span class="hidden sm:inline ml-1" 
                                              x-text="level === 1 ? '(Guess)' : (level === 3 ? '(Maybe)' : (level === 5 ? '(Sure)' : ''))"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Navigation --}}
                    <div class="px-6 pb-6 flex items-center justify-between gap-4">
                        <button type="button" @click="prevQuestion()"
                                class="px-6 py-3 rounded-xl font-medium transition"
                                :class="current === 0 
                                    ? 'bg-slate-100 text-slate-400 cursor-not-allowed dark:bg-slate-800' 
                                    : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-300 dark:hover:bg-slate-600'"
                                :disabled="current === 0">
                            <i class="fas fa-arrow-left mr-2"></i> Previous
                        </button>
                        
                        <div class="flex gap-1 overflow-x-auto px-2">
                            <template x-for="(q, idx) in questions" :key="q.id">
                                <button type="button" @click="goToQuestion(idx)"
                                        class="w-8 h-8 rounded-lg text-xs font-bold transition flex-shrink-0"
                                        :class="{
                                            'bg-blue-500 text-white': current === idx,
                                            'bg-green-500 text-white': current !== idx && userAnswers[q.id],
                                            'bg-amber-500 text-white': current !== idx && !userAnswers[q.id] && tracking.reviewMarked.has(q.id),
                                            'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400': current !== idx && !userAnswers[q.id] && !tracking.reviewMarked.has(q.id)
                                        }"
                                        x-text="idx + 1">
                                </button>
                            </template>
                        </div>
                        
                        <template x-if="current < questions.length - 1">
                            <button type="button" @click="nextQuestion()"
                                    class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-xl font-medium transition">
                                Next <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </template>
                        <template x-if="current === questions.length - 1">
                            <button type="button" @click="submitExam()"
                                    :disabled="submitting"
                                    class="px-8 py-3 bg-green-500 hover:bg-green-600 text-white rounded-xl font-semibold transition disabled:opacity-50">
                                <span x-show="!submitting"><i class="fas fa-check mr-2"></i> Submit Exam</span>
                                <span x-show="submitting"><i class="fas fa-spinner fa-spin mr-2"></i> Submitting...</span>
                            </button>
                        </template>
                    </div>
                </div>
            </template>

            {{-- Hint Modal --}}
            <div x-show="showHint" x-transition
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-md w-full p-6"
                     @click.away="showHint = false">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                            <i class="fas fa-lightbulb text-yellow-500"></i> Hint
                        </h3>
                        <button @click="showHint = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <template x-if="hintLoading">
                        <div class="flex items-center justify-center py-8">
                            <i class="fas fa-spinner fa-spin text-3xl text-blue-500"></i>
                        </div>
                    </template>
                    <template x-if="!hintLoading">
                        <div class="prose dark:prose-invert text-sm" x-html="currentHint || 'No hint available.'"></div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <script>
        function levelIndicatorExam() {
            return {
                // Exam data
                questions: @json($questions),
                moduleId: {{ $module->id }},
                current: 0,
                userAnswers: {},
                
                // UI state
                submitting: false,
                showHint: false,
                hintLoading: false,
                currentHint: '',
                elapsedTime: 0,
                timerInterval: null,
                
                // ================================================================
                // 11-FEATURE TRACKING SYSTEM
                // ================================================================
                tracking: {
                    examStartTime: null,
                    questionStartTimes: {},       // timestamp when question was first viewed
                    questionTotalTimes: {},       // accumulated time per question
                    answerHistory: {},            // {qId: [{old, new, timestamp}]}
                    tabSwitchCount: 0,
                    clickCounts: {},              // per question
                    reviewMarked: new Set(),
                    hintsUsed: new Set(),
                    confidenceRatings: {},        // 1-5 per question
                    firstActionTimestamps: {},    // time of first interaction per question
                },
                
                init() {
                    this.tracking.examStartTime = Date.now();
                    this.startTimer();
                    this.recordQuestionView(this.questions[0].id);
                    
                    // Initialize answer history
                    this.questions.forEach(q => {
                        this.tracking.answerHistory[q.id] = [];
                        this.tracking.clickCounts[q.id] = 0;
                    });
                },
                
                startTimer() {
                    this.timerInterval = setInterval(() => {
                        this.elapsedTime = Math.floor((Date.now() - this.tracking.examStartTime) / 1000);
                    }, 1000);
                },
                
                formatTime(seconds) {
                    const mins = Math.floor(seconds / 60);
                    const secs = seconds % 60;
                    return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                },
                
                // ================================================================
                // TRACKING METHODS
                // ================================================================
                
                recordQuestionView(qId) {
                    if (!this.tracking.questionStartTimes[qId]) {
                        this.tracking.questionStartTimes[qId] = Date.now();
                    }
                },
                
                recordQuestionLeave(qId) {
                    if (this.tracking.questionStartTimes[qId]) {
                        const timeSpent = (Date.now() - this.tracking.questionStartTimes[qId]) / 1000;
                        this.tracking.questionTotalTimes[qId] = (this.tracking.questionTotalTimes[qId] || 0) + timeSpent;
                        this.tracking.questionStartTimes[qId] = null;
                    }
                },
                
                recordFirstAction(qId) {
                    if (!this.tracking.firstActionTimestamps[qId]) {
                        const viewStart = this.tracking.questionStartTimes[qId] || Date.now();
                        this.tracking.firstActionTimestamps[qId] = (Date.now() - viewStart) / 1000;
                    }
                },
                
                recordClick(qId) {
                    this.tracking.clickCounts[qId] = (this.tracking.clickCounts[qId] || 0) + 1;
                    this.recordFirstAction(qId);
                },
                
                selectAnswer(qId, value) {
                    const oldValue = this.userAnswers[qId];
                    if (oldValue !== undefined && oldValue !== value) {
                        this.tracking.answerHistory[qId].push({
                            old: oldValue,
                            new: value,
                            timestamp: Date.now()
                        });
                    }
                    this.userAnswers[qId] = value;
                    this.recordFirstAction(qId);
                },
                
                recordAnswerChange(qId) {
                    this.recordFirstAction(qId);
                },
                
                setConfidence(qId, level) {
                    this.tracking.confidenceRatings[qId] = level;
                    this.recordFirstAction(qId);
                },
                
                toggleReview(qId) {
                    if (this.tracking.reviewMarked.has(qId)) {
                        this.tracking.reviewMarked.delete(qId);
                    } else {
                        this.tracking.reviewMarked.add(qId);
                    }
                },
                
                handleVisibilityChange() {
                    if (document.hidden) {
                        this.tracking.tabSwitchCount++;
                    }
                },
                
                async requestHint(qId) {
                    this.tracking.hintsUsed.add(qId);
                    this.showHint = true;
                    this.hintLoading = true;
                    this.currentHint = '';
                    
                    try {
                        // Level Indicator uses SIMPLE hints (not adaptive scaffolding)
                        // since this IS the diagnostic exam - we're collecting data, not using it
                        const response = await fetch('/generate-hint', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                question_text: this.questions[this.current].question_text,
                                is_diagnostic: true, // Flag for diagnostic exam - uses simple L2 hints
                                hint_level: 2, // Moderate hint level (not adaptive)
                                student_context: 'Level Indicator Exam - Diagnostic Assessment'
                            })
                        });
                        const data = await response.json();
                        this.currentHint = data.hint || "Hint couldn't be generated.";
                    } catch {
                        this.currentHint = "Hint couldn't be generated.";
                    } finally {
                        this.hintLoading = false;
                    }
                },
                
                // ================================================================
                // NAVIGATION
                // ================================================================
                
                nextQuestion() {
                    this.recordQuestionLeave(this.questions[this.current].id);
                    if (this.current < this.questions.length - 1) {
                        this.current++;
                        this.recordQuestionView(this.questions[this.current].id);
                    }
                },
                
                prevQuestion() {
                    this.recordQuestionLeave(this.questions[this.current].id);
                    if (this.current > 0) {
                        this.current--;
                        this.recordQuestionView(this.questions[this.current].id);
                    }
                },
                
                goToQuestion(idx) {
                    this.recordQuestionLeave(this.questions[this.current].id);
                    this.current = idx;
                    this.recordQuestionView(this.questions[this.current].id);
                },
                
                // ================================================================
                // FEATURE CALCULATION
                // ================================================================
                
                calculateFeatures() {
                    const totalQuestions = this.questions.length;
                    
                    // Calculate hint usage percentage
                    const hintUsagePercentage = (this.tracking.hintsUsed.size / totalQuestions) * 100;
                    
                    // Calculate average confidence
                    const confidenceValues = Object.values(this.tracking.confidenceRatings);
                    const avgConfidence = confidenceValues.length > 0 
                        ? confidenceValues.reduce((a, b) => a + b, 0) / confidenceValues.length 
                        : 3.0;
                    
                    // Calculate answer changes rate
                    let totalChanges = 0;
                    Object.values(this.tracking.answerHistory).forEach(history => {
                        totalChanges += history.length;
                    });
                    const answerChangesRate = totalChanges / totalQuestions;
                    
                    // Calculate tab switches rate
                    const tabSwitchesRate = this.tracking.tabSwitchCount / totalQuestions;
                    
                    // Calculate average time per question
                    // Finalize current question time
                    this.recordQuestionLeave(this.questions[this.current].id);
                    const times = Object.values(this.tracking.questionTotalTimes);
                    const avgTimePerQuestion = times.length > 0 
                        ? times.reduce((a, b) => a + b, 0) / times.length 
                        : 60;
                    
                    // Calculate review percentage
                    const reviewPercentage = (this.tracking.reviewMarked.size / totalQuestions) * 100;
                    
                    // Calculate average first action latency
                    const latencies = Object.values(this.tracking.firstActionTimestamps);
                    const avgFirstActionLatency = latencies.length > 0 
                        ? latencies.reduce((a, b) => a + b, 0) / latencies.length 
                        : 3.0;
                    
                    // Calculate clicks per question
                    const clicks = Object.values(this.tracking.clickCounts);
                    const clicksPerQuestion = clicks.length > 0 
                        ? clicks.reduce((a, b) => a + b, 0) / totalQuestions 
                        : 4.0;
                    
                    return {
                        hint_usage_percentage: hintUsagePercentage,
                        avg_confidence: avgConfidence,
                        answer_changes_rate: answerChangesRate,
                        tab_switches_rate: tabSwitchesRate,
                        avg_time_per_question: avgTimePerQuestion,
                        review_percentage: reviewPercentage,
                        avg_first_action_latency: avgFirstActionLatency,
                        clicks_per_question: clicksPerQuestion,
                    };
                },
                
                // ================================================================
                // SUBMISSION
                // ================================================================
                
                async submitExam() {
                    if (this.submitting) return;
                    
                    // Check for unanswered questions
                    const unanswered = this.questions.filter(q => !this.userAnswers[q.id]);
                    if (unanswered.length > 0) {
                        if (!confirm(`You have ${unanswered.length} unanswered question(s). Submit anyway?`)) {
                            return;
                        }
                    }
                    
                    this.submitting = true;
                    clearInterval(this.timerInterval);
                    
                    const features = this.calculateFeatures();
                    const questionIds = this.questions.map(q => q.id);
                    
                    try {
                        const response = await fetch('{{ route("level-indicator.submit", $module) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                answers: this.userAnswers,
                                features: features,
                                question_ids: questionIds
                            })
                        });
                        
                        const data = await response.json();
                        
                        if (data.success && data.redirect) {
                            window.location.href = data.redirect;
                        } else {
                            alert('Error submitting exam. Please try again.');
                            this.submitting = false;
                        }
                    } catch (error) {
                        console.error('Submission error:', error);
                        alert('Error submitting exam. Please try again.');
                        this.submitting = false;
                    }
                }
            };
        }
    </script>
</x-app-layout>
