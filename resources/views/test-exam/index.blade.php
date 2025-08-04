<x-app-layout>
{{-- This is the test exam index view --}}
<div x-data="quizApp()" class="min-h-screen bg-gradient-to-br from-gray-100 via-blue-100 to-indigo-200 dark:from-gray-900 dark:via-gray-800 dark:to-indigo-900 flex flex-col">
    <!-- User Details Section -->
    <div class="w-full max-w-6xl mx-auto px-6 py-6 mb-0"> <!-- py-8 -> py-6 (reduced vertical padding) -->
        <div class="bg-white dark:bg-gray-950 border border-gray-100 dark:border-gray-800 rounded-2xl shadow-xl p-8 flex flex-wrap gap-8 justify-between items-center">
            <!-- ...existing user details code... -->
            <div class="flex items-center gap-2">
                <i class="fa fa-globe text-indigo-700 dark:text-indigo-200 text-xl"></i>
                <span class="font-bold text-indigo-700 dark:text-indigo-200">IP:</span>
                <span class="font-mono text-gray-700 dark:text-gray-300" x-text="userDetails.ip || 'Could not find'"></span>
            </div>
            <div class="flex items-center gap-2">
                <i class="fa fa-map-marker-alt text-indigo-700 dark:text-indigo-200 text-xl"></i>
                <span class="font-bold text-indigo-700 dark:text-indigo-200">Location:</span>
                <span class="text-gray-700 dark:text-gray-300" x-text="userDetails.location || 'Could not find'"></span>
            </div>
            <div class="flex items-center gap-2">
                <i class="fa fa-window-maximize text-indigo-700 dark:text-indigo-200 text-xl"></i>
                <span class="font-bold text-indigo-700 dark:text-indigo-200">Browser:</span>
                <span class="text-gray-700 dark:text-gray-300" x-text="userDetails.browser || 'Could not find'"></span>
            </div>
            <div class="flex items-center gap-2">
                <i class="fa fa-desktop text-indigo-700 dark:text-indigo-200 text-xl"></i>
                <span class="font-bold text-indigo-700 dark:text-indigo-200">OS:</span>
                <span class="text-gray-700 dark:text-gray-300" x-text="userDetails.os || 'Could not find'"></span>
            </div>
            <div class="flex items-center gap-2">
                <i class="fa fa-mobile-alt text-indigo-700 dark:text-indigo-200 text-xl"></i>
                <span class="font-bold text-indigo-700 dark:text-indigo-200">Device Type:</span>
                <span class="text-gray-700 dark:text-gray-300" x-text="userDetails.deviceType || 'Could not find'"></span>
            </div>
            <div class="flex items-center gap-2">
                <i class="fa fa-info-circle text-indigo-700 dark:text-indigo-200 text-xl"></i>
                <span class="font-bold text-indigo-700 dark:text-indigo-200">User Agent:</span>
                <span class="text-gray-700 dark:text-gray-300 truncate max-w-8xl" x-text="userDetails.userAgent || 'Could not find'"></span>
            </div>
            <div class="flex items-center gap-2">
                <i class="fa fa-clock text-indigo-700 dark:text-indigo-200 text-xl"></i>
                <span class="font-bold text-indigo-700 dark:text-indigo-200">Timezone:</span>
                <span class="text-gray-700 dark:text-gray-300" x-text="userDetails.timezone || 'Could not find'"></span>
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
                            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl p-10 flex flex-col gap-8 transition-all duration-300 w-full">
                                <div class="flex items-center justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2" x-text="questions[current].question_text"></div>
                                        <div class="text-sm text-gray-400 dark:text-gray-500 font-mono">Type: <span class="uppercase" x-text="questions[current].type"></span></div>
                                    </div>
                                    <!-- Hint Button -->
                                    <button type="button" @click="fetchHint" class="ml-2 text-yellow-400 hover:text-yellow-500 focus:outline-none" title="Show Hint">
                                        <i class="fa fa-lightbulb text-3xl"></i>
                                    </button>
                                </div>
                                <!-- Question Types -->
                                <template x-if="questions[current].type === 'mcq'">
                                    <ul class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                        <template x-for="answer in questions[current].answers" :key="answer.id">
                                            <li>
                                                <label class="flex items-center gap-3 bg-indigo-50 dark:bg-gray-700 rounded-xl px-4 py-3 cursor-pointer hover:bg-indigo-100 dark:hover:bg-gray-600 transition">
                                                    <input 
                                                        type="radio"
                                                        :name="'answers[' + questions[current].id + ']'"
                                                        :value="answer.id"
                                                        class="form-radio h-6 w-6 text-indigo-600"
                                                        x-model="userAnswers[questions[current].id]"
                                                    >
                                                    <span class="text-lg text-gray-800 dark:text-gray-200" x-text="answer.answer_text"></span>
                                                </label>
                                            </li>
                                        </template>
                                    </ul>
                                </template>
                                <template x-if="questions[current].type === 'true_false'">
                                    <div class="flex gap-8 mt-4">
                                        <label class="flex items-center gap-3 bg-green-50 dark:bg-gray-700 rounded-xl px-4 py-3 cursor-pointer hover:bg-green-100 dark:hover:bg-gray-600 transition">
                                            <input 
                                                type="radio"
                                                :name="'answers[' + questions[current].id + ']'"
                                                value="true"
                                                class="form-radio h-6 w-6 text-green-600"
                                                x-model="userAnswers[questions[current].id]"
                                            >
                                            <span class="text-lg text-gray-800 dark:text-gray-200">True</span>
                                        </label>
                                        <label class="flex items-center gap-3 bg-red-50 dark:bg-gray-700 rounded-xl px-4 py-3 cursor-pointer hover:bg-red-100 dark:hover:bg-gray-600 transition">
                                            <input 
                                                type="radio"
                                                :name="'answers[' + questions[current].id + ']'"
                                                value="false"
                                                class="form-radio h-6 w-6 text-red-600"
                                                x-model="userAnswers[questions[current].id]"
                                            >
                                            <span class="text-lg text-gray-800 dark:text-gray-200">False</span>
                                        </label>
                                    </div>
                                </template>
                                <template x-if="questions[current].type === 'fill_in_blank'">
                                    <div class="mt-4">
                                        <input 
                                            type="text"
                                            :name="'answers[' + questions[current].id + ']'"
                                            class="form-input mt-1 block w-full rounded-xl border-2 border-indigo-300 dark:bg-gray-700 dark:text-gray-100 text-xl px-4 py-3 focus:border-indigo-500 transition"
                                            x-model="userAnswers[questions[current].id]"
                                            placeholder="Type your answer here"
                                        >
                                    </div>
                                </template>

                                <!-- Hint Canvas -->
                                <div x-show="showHint" x-transition class="fixed inset-0 z-50 flex">
                                    <div class="fixed inset-0 bg-black bg-opacity-40" @click="showHint = false"></div>
                                    <div class="relative bg-white dark:bg-gray-900 w-full max-w-md ml-auto h-full shadow-2xl p-10 flex flex-col rounded-l-3xl">
                                        <div class="flex items-center justify-between mb-6">
                                            <h2 class="text-2xl font-bold text-indigo-700 dark:text-indigo-200">Hint</h2>
                                            <button @click="showHint = false" class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="flex-1 flex items-center justify-center">
                                            <p class="text-gray-700 dark:text-gray-300 text-lg text-center" x-text="hint || 'Loading hint...'"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <div class="flex flex-wrap justify-between items-center mt-10 gap-4">
                            <button type="button" class="px-8 py-3 bg-gray-300 dark:bg-gray-700 rounded-xl hover:bg-gray-400 dark:hover:bg-gray-600 transition font-semibold text-lg"
                                @click="prevQuestion" x-bind:disabled="current === 0">
                                Previous
                            </button>
                            <div class="flex-1 text-center text-gray-500 dark:text-gray-400 text-lg font-mono">
                                Question <span x-text="current + 1"></span> of <span x-text="questions.length"></span>
                            </div>
                            <button type="button" class="px-8 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition font-semibold text-lg"
                                @click="nextQuestion" x-bind:disabled="current === questions.length - 1">
                                Next
                            </button>
                            <button type="submit" class="px-8 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition font-semibold text-lg"
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
            this.hint = 'Loading hint...';
            const currentQuestion = this.questions[this.current];
            try {
                const response = await fetch('/generate-hint', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ question_text: currentQuestion.question_text })
                });
                const data = await response.json();
                this.hint = data.hint || "Hint couldn't be generated. Try again later.";
            } catch {
                this.hint = "Hint couldn't be generated. Try again later.";
            }
        },
        async fetchDetails() {
            // IP & Location (using ipinfo.io)
            try {
                const res = await fetch('https://ipinfo.io/json?token={{ env('IPINFO_TOKEN') }}');
                const data = await res.json();
                this.userDetails.ip = data.ip || 'Could not find';
                this.userDetails.location = data.city && data.country ? `${data.city}, ${data.country}` : 'Could not find';
            } catch { /* ignore */ }
            // Improved Browser, OS, Device Type, User Agent, Timezone detection
            try {
                const parser = new UAParser();
                const result = parser.getResult();
                this.userDetails.browser = result.browser.name || 'Could not find';
                this.userDetails.os = result.os.name || 'Could not find';
                this.userDetails.deviceType = result.device.type || 'Desktop';
                this.userDetails.userAgent = navigator.userAgent || 'Could not find';
                this.userDetails.timezone = Intl.DateTimeFormat().resolvedOptions().timeZone || 'Could not find';
            } catch { /* ignore */ }
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
