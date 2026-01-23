<x-app-layout>
    {{-- This is the test exam index view --}}
    <div x-data="quizApp()"
        class="min-h-screen bg-gradient-to-br from-gray-100 via-blue-100 to-indigo-200 dark:from-gray-900 dark:via-gray-800 dark:to-indigo-900 flex flex-col">
        <!-- User Details Section -->
        <div class="w-full max-w-6xl mx-auto px-6 py-6 mb-0"> <!-- py-8 -> py-6 (reduced vertical padding) -->
            <div
                class="bg-white dark:bg-gray-950 border border-gray-100 dark:border-gray-800 rounded-2xl shadow-xl p-8 flex flex-wrap gap-8 justify-between items-center">
                <!-- ...existing user details code... -->
                <div class="flex items-center gap-2">
                    <i class="fa fa-globe text-indigo-700 dark:text-indigo-200 text-xl"></i>
                    <span class="font-bold text-indigo-700 dark:text-indigo-200">IP:</span>
                    <span class="font-mono text-gray-700 dark:text-gray-300"
                        x-text="userDetails.ip || 'Could not find'"></span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa fa-map-marker-alt text-indigo-700 dark:text-indigo-200 text-xl"></i>
                    <span class="font-bold text-indigo-700 dark:text-indigo-200">Location:</span>
                    <span class="text-gray-700 dark:text-gray-300"
                        x-text="userDetails.location || 'Could not find'"></span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa fa-window-maximize text-indigo-700 dark:text-indigo-200 text-xl"></i>
                    <span class="font-bold text-indigo-700 dark:text-indigo-200">Browser:</span>
                    <span class="text-gray-700 dark:text-gray-300"
                        x-text="userDetails.browser || 'Could not find'"></span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa fa-desktop text-indigo-700 dark:text-indigo-200 text-xl"></i>
                    <span class="font-bold text-indigo-700 dark:text-indigo-200">OS:</span>
                    <span class="text-gray-700 dark:text-gray-300" x-text="userDetails.os || 'Could not find'"></span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa fa-mobile-alt text-indigo-700 dark:text-indigo-200 text-xl"></i>
                    <span class="font-bold text-indigo-700 dark:text-indigo-200">Device Type:</span>
                    <span class="text-gray-700 dark:text-gray-300"
                        x-text="userDetails.deviceType || 'Could not find'"></span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa fa-info-circle text-indigo-700 dark:text-indigo-200 text-xl"></i>
                    <span class="font-bold text-indigo-700 dark:text-indigo-200">User Agent:</span>
                    <span class="text-gray-700 dark:text-gray-300 truncate max-w-8xl"
                        x-text="userDetails.userAgent || 'Could not find'"></span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa fa-clock text-indigo-700 dark:text-indigo-200 text-xl"></i>
                    <span class="font-bold text-indigo-700 dark:text-indigo-200">Timezone:</span>
                    <span class="text-gray-700 dark:text-gray-300"
                        x-text="userDetails.timezone || 'Could not find'"></span>
                </div>
            </div>
        </div>

        <!-- Quiz Section Full Width -->
        <div class="flex-1 flex items-center justify-center w-full mt-0"> <!-- removed default margin-top -->
            <div class="w-full max-w-6xl mx-auto px-6 py-4"> <!-- py-8 -> py-6 (reduced vertical padding) -->
                {{-- <h1 class="text-5xl font-black text-indigo-900 dark:text-indigo-200 mb-12 text-center tracking-tight drop-shadow-lg">Test Exam</h1> --}}
                <form @submit.prevent="submitAnswers" class="relative">
                    @csrf
                    <template x-if="questions.length > 0">
                        <div>
                            <!-- ...existing quiz code... -->
                            <template x-if="current < questions.length">
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl p-10 flex flex-col gap-8 transition-all duration-300 w-full">
                                    <div class="flex items-center justify-between gap-4">
                                        <div class="flex-1">
                                            <div class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2"
                                                x-text="questions[current].question_text"></div>
                                            <div class="text-sm text-gray-400 dark:text-gray-500 font-mono">Type: <span
                                                    class="uppercase" x-text="questions[current].type"></span></div>
                                        </div>
                                        <!-- Hint Button -->
                                        <button type="button" @click="fetchHint"
                                            class="ml-2 text-yellow-400 hover:text-yellow-500 focus:outline-none"
                                            title="Show Hint">
                                            <i class="fa fa-lightbulb text-3xl"></i>
                                        </button>
                                    </div>
                                    <!-- Question Types -->
                                    <template x-if="questions[current].type === 'mcq'">
                                        <ul class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                            <template x-for="answer in questions[current].answers"
                                                :key="answer.id">
                                                <li>
                                                    <label
                                                        class="flex items-center gap-3 bg-indigo-50 dark:bg-gray-700 rounded-xl px-4 py-3 cursor-pointer hover:bg-indigo-100 dark:hover:bg-gray-600 transition">
                                                        <input type="radio"
                                                            :name="'answers[' + questions[current].id + ']'"
                                                            :value="answer.id"
                                                            class="form-radio h-6 w-6 text-indigo-600"
                                                            x-model="userAnswers[questions[current].id]">
                                                        <span class="text-lg text-gray-800 dark:text-gray-200"
                                                            x-text="answer.answer_text"></span>
                                                    </label>
                                                </li>
                                            </template>
                                        </ul>
                                    </template>
                                    <template x-if="questions[current].type === 'true_false'">
                                        <div class="flex gap-8 mt-4">
                                            <label
                                                class="flex items-center gap-3 bg-green-50 dark:bg-gray-700 rounded-xl px-4 py-3 cursor-pointer hover:bg-green-100 dark:hover:bg-gray-600 transition">
                                                <input type="radio" :name="'answers[' + questions[current].id + ']'"
                                                    value="true" class="form-radio h-6 w-6 text-green-600"
                                                    x-model="userAnswers[questions[current].id]">
                                                <span class="text-lg text-gray-800 dark:text-gray-200">True</span>
                                            </label>
                                            <label
                                                class="flex items-center gap-3 bg-red-50 dark:bg-gray-700 rounded-xl px-4 py-3 cursor-pointer hover:bg-red-100 dark:hover:bg-gray-600 transition">
                                                <input type="radio" :name="'answers[' + questions[current].id + ']'"
                                                    value="false" class="form-radio h-6 w-6 text-red-600"
                                                    x-model="userAnswers[questions[current].id]">
                                                <span class="text-lg text-gray-800 dark:text-gray-200">False</span>
                                            </label>
                                        </div>
                                    </template>
                                    <template x-if="questions[current].type === 'fill_in_blank'">
                                        <div class="mt-4">
                                            <input type="text" :name="'answers[' + questions[current].id + ']'"
                                                class="form-input mt-1 block w-full rounded-xl border-2 border-indigo-300 dark:bg-gray-700 dark:text-gray-100 text-xl px-4 py-3 focus:border-indigo-500 transition"
                                                x-model="userAnswers[questions[current].id]"
                                                placeholder="Type your answer here">
                                        </div>
                                    </template>

                                    <!-- Hint Canvas -->
                                    <div x-show="showHint" x-transition class="fixed inset-0 z-50 flex">
                                        <div class="fixed inset-0 bg-black bg-opacity-40" @click="showHint = false">
                                        </div>
                                        <div
                                            class="relative bg-white dark:bg-gray-900 w-full max-w-lg ml-auto h-full shadow-2xl flex flex-col rounded-l-3xl overflow-hidden">
                                            <!-- Header -->
                                            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                                                <div class="flex items-center gap-3">
                                                    <i class="fa fa-lightbulb text-yellow-400 text-2xl"></i>
                                                    <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">Personalized Hint</h2>
                                                </div>
                                                <button type="button" @click="showHint = false"
                                                    class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                            
                                            <!-- Content Area - Scrollable -->
                                            <div class="flex-1 overflow-y-auto p-6 space-y-6">
                                                
                                                <!-- Loading State -->
                                                <template x-if="hintLoading">
                                                    <div class="flex flex-col items-center justify-center py-16 space-y-4">
                                                        <!-- Animated Spinner -->
                                                        <div class="relative">
                                                            <div class="w-16 h-16 border-4 border-indigo-200 dark:border-indigo-800 rounded-full"></div>
                                                            <div class="absolute top-0 left-0 w-16 h-16 border-4 border-transparent border-t-indigo-600 rounded-full animate-spin"></div>
                                                        </div>
                                                        <!-- Pulsing Message -->
                                                        <p class="text-indigo-600 dark:text-indigo-400 font-medium animate-pulse text-center">
                                                            Generating your personalized hint...
                                                        </p>
                                                        <p class="text-gray-400 dark:text-gray-500 text-sm text-center">
                                                            Analyzing your learning profile
                                                        </p>
                                                    </div>
                                                </template>
                                                
                                                <!-- Loaded Content -->
                                                <template x-if="!hintLoading">
                                                    <div class="space-y-6">
                                                        
                                                        <!-- Student Level Badge -->
                                                        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/30 dark:to-purple-900/30 rounded-xl p-4">
                                                            <div class="flex items-center justify-between">
                                                                <div>
                                                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Your Predicted Level</p>
                                                                    <div class="flex items-center gap-2">
                                                                        <span class="px-3 py-1 rounded-full text-sm font-bold"
                                                                            :class="{
                                                                                'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300': studentProfile.level === 'L1',
                                                                                'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300': studentProfile.level === 'L2',
                                                                                'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300': studentProfile.level === 'L3'
                                                                            }"
                                                                            x-text="studentProfile.level">
                                                                        </span>
                                                                        <span class="text-lg font-semibold text-gray-800 dark:text-gray-100" x-text="studentProfile.level_name"></span>
                                                                    </div>
                                                                </div>
                                                                <div class="text-3xl" 
                                                                    x-text="studentProfile.level === 'L1' ? '🌟' : (studentProfile.level === 'L2' ? '📈' : '🎯')">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- SHAP Explanations (Collapsible) -->
                                                        <div class="bg-gray-50 dark:bg-gray-800 rounded-xl overflow-hidden">
                                                            <button type="button" @click="showShapDetails = !showShapDetails" 
                                                                class="w-full flex items-center justify-between p-4 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                                                <div class="flex items-center gap-2">
                                                                    <i class="fa fa-chart-bar text-indigo-500"></i>
                                                                    <span class="font-medium text-gray-700 dark:text-gray-200">Why this level?</span>
                                                                    <span class="text-xs text-gray-400">(SHAP Analysis)</span>
                                                                </div>
                                                                <i class="fa fa-chevron-down text-gray-400 transition-transform duration-200"
                                                                    :class="{ 'rotate-180': showShapDetails }"></i>
                                                            </button>
                                                            
                                                            <div x-show="showShapDetails" x-collapse class="border-t border-gray-200 dark:border-gray-700">
                                                                <div class="p-4 space-y-2 max-h-64 overflow-y-auto">
                                                                    <template x-for="item in studentProfile.shap_explanation" :key="item.feature">
                                                                        <div class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-white dark:hover:bg-gray-700 transition">
                                                                            <div class="flex-1">
                                                                                <p class="text-sm font-medium text-gray-700 dark:text-gray-200" x-text="item.feature"></p>
                                                                                <p class="text-xs text-gray-400" x-text="item.desc"></p>
                                                                            </div>
                                                                            <div class="flex items-center gap-3">
                                                                                <span class="text-sm text-gray-600 dark:text-gray-300 font-mono" x-text="item.value"></span>
                                                                                <span class="text-xs font-bold px-2 py-0.5 rounded"
                                                                                    :class="{
                                                                                        'bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-400': item.contribution > 0,
                                                                                        'bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-400': item.contribution < 0,
                                                                                        'bg-gray-100 text-gray-500 dark:bg-gray-600 dark:text-gray-400': item.contribution === 0
                                                                                    }"
                                                                                    x-text="item.contribution > 0 ? '+' + item.contribution.toFixed(2) : item.contribution.toFixed(2)">
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </template>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Personalized Hint -->
                                                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5">
                                                            <div class="flex items-center gap-2 mb-3">
                                                                <i class="fa fa-magic text-purple-500"></i>
                                                                <span class="font-medium text-gray-700 dark:text-gray-200">Your Personalized Hint</span>
                                                            </div>
                                                            <div class="prose prose-sm dark:prose-invert max-w-none text-gray-600 dark:text-gray-300 leading-relaxed"
                                                                x-html="hint || '<p class=\'text-gray-400\'>No hint available.</p>'">
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                </template>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <div class="flex flex-wrap justify-between items-center mt-10 gap-4">
                                <button type="button"
                                    class="px-8 py-3 bg-gray-300 dark:bg-gray-700 rounded-xl hover:bg-gray-400 dark:hover:bg-gray-600 transition font-semibold text-lg"
                                    @click="prevQuestion" x-bind:disabled="current === 0">
                                    Previous
                                </button>
                                <div class="flex-1 text-center text-gray-500 dark:text-gray-400 text-lg font-mono">
                                    Question <span x-text="current + 1"></span> of <span
                                        x-text="questions.length"></span>
                                </div>
                                <button type="button"
                                    class="px-8 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition font-semibold text-lg"
                                    @click="nextQuestion" x-bind:disabled="current === questions.length - 1">
                                    Next
                                </button>
                                <button type="submit"
                                    class="px-8 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition font-semibold text-lg"
                                    x-show="current === questions.length - 1">
                                    Submit Answers
                                </button>
                            </div>
                        </div>
                    </template>
                    <template x-if="questions.length === 0">
                        <div class="text-gray-500 text-center text-xl mt-12">No questions found.</div>
                    </template>
                </form>
            </div>
        </div>
    </div>

    <!-- Add UAParser.js for accurate user agent detection -->
    <script src="https://cdn.jsdelivr.net/npm/ua-parser-js@1.0.2/src/ua-parser.min.js"></script>
    <script>
        function quizApp() {
            return {
                questions: @json($Questions),
                current: 0,
                showHint: false,
                hintLoading: false,
                showShapDetails: false,
                hint: '',
                userAnswers: {},
                userDetails: {
                    ip: 'Could not find',
                    location: 'Could not find',
                    browser: 'Could not find',
                    os: 'Could not find',
                    deviceType: 'Could not find',
                    userAgent: 'Could not find',
                    timezone: 'Could not find'
                },
                // Static student profile (will be fetched from DB when ML pipeline is ready)
                studentProfile: {
                    level: "L1",
                    level_name: "Developing",
                    shap_explanation: [
                        { feature: "Score %", value: "65%", contribution: 0.25, desc: "Percentage of correct answers" },
                        { feature: "Avg. Time/Question", value: "45s", contribution: -0.10, desc: "Mean time spent per item" },
                        { feature: "Confidence", value: "3.2/5", contribution: 0.05, desc: "Mean self-reported confidence" },
                        { feature: "Focus Rate", value: "0.3", contribution: -0.15, desc: "Avg tab switches per question" },
                        { feature: "Uncertainty Rate", value: "0.5", contribution: -0.08, desc: "Avg answer changes per question" },
                        { feature: "Review %", value: "20%", contribution: 0.02, desc: "% of questions marked for review" },
                        { feature: "Processing Speed", value: "2.1s", contribution: 0.03, desc: "Mean latency to first interaction" },
                        { feature: "Interaction Intensity", value: "4.5", contribution: 0.00, desc: "Avg clicks per question" },
                        { feature: "Endurance Trend", value: "-0.05", contribution: -0.12, desc: "Accuracy change (2nd half - 1st half)" },
                        { feature: "Advanced Mastery", value: "55%", contribution: 0.08, desc: "% correct on difficulty > 1" },
                        { feature: "Scaffolding Use", value: "25%", contribution: 0.10, desc: "% of questions where hint was used" }
                    ]
                },
                nextQuestion() {
                    if (this.current < this.questions.length - 1) this.current++;
                },
                prevQuestion() {
                    if (this.current > 0) this.current--;
                },
                submitAnswers() {
                    alert(JSON.stringify(this.userAnswers));
                },
                async fetchHint() {
                    this.showHint = true;
                    this.hintLoading = true;
                    this.hint = '';
                    const currentQuestion = this.questions[this.current];
                    try {
                        const response = await fetch('/generate-hint', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                question_text: currentQuestion.question_text,
                                hint_level: this.studentProfile.level === 'L1' ? 1 : (this.studentProfile.level === 'L2' ? 2 : 3),
                                student_context: `Student is at ${this.studentProfile.level_name} level (${this.studentProfile.level}). Key factors: Score ${this.studentProfile.shap_explanation[0].value}, Confidence ${this.studentProfile.shap_explanation[2].value}.`
                            })
                        });
                        const data = await response.json();
                        this.hint = data.hint || "Hint couldn't be generated. Try again later.";
                    } catch {
                        this.hint = "Hint couldn't be generated. Try again later.";
                    } finally {
                        this.hintLoading = false;
                    }
                },
                async fetchDetails() {
                    // IP & Location (using ipinfo.io)
                    try {
                        const res = await fetch('https://ipinfo.io/json?token={{ env('IPINFO_TOKEN') }}');
                        const data = await res.json();
                        this.userDetails.ip = data.ip || 'Could not find';
                        this.userDetails.location = data.city && data.country ? `${data.city}, ${data.country}` :
                            'Could not find';
                    } catch {
                        /* ignore */ }
                    // Improved Browser, OS, Device Type, User Agent, Timezone detection
                    try {
                        const parser = new UAParser();
                        const result = parser.getResult();
                        this.userDetails.browser = result.browser.name || 'Could not find';
                        this.userDetails.os = result.os.name || 'Could not find';
                        this.userDetails.deviceType = result.device.type || 'Desktop';
                        this.userDetails.userAgent = navigator.userAgent || 'Could not find';
                        this.userDetails.timezone = Intl.DateTimeFormat().resolvedOptions().timeZone ||
                        'Could not find';
                    } catch {
                        /* ignore */ }
                }
            }
        }
        document.addEventListener('alpine:init', () => {
            Alpine.data('quizApp', () => ({
                ...quizApp(),
                init() {
                    this.fetchDetails();
                }
            }));
        });
    </script>
    <!-- Add this to your layout or head section if not already present -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</x-app-layout>
