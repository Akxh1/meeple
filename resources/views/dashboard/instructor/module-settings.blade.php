<x-app-layout>
    {{-- Instructor Module Settings Page --}}
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-blue-900">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            {{-- Back Navigation --}}
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 mb-6 transition-colors">
                <i class="fas fa-arrow-left"></i>
                Back to Dashboard
            </a>

            {{-- Header --}}
            <div class="bg-gradient-to-r {{ $moduleData['gradient'] }} rounded-3xl p-8 mb-8 relative overflow-hidden shadow-xl">
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-3">
                        <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                            <i class="fas fa-cog text-white text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-white">Module Settings</h1>
                            <p class="text-white/80">{{ $moduleData['title'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Success Message --}}
            @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl text-green-700 dark:text-green-400">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
            @endif

            {{-- Module Statistics --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white dark:bg-slate-800/50 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700/50">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                            <i class="fas fa-question-circle text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <span class="text-sm text-slate-500 dark:text-slate-400">Questions</span>
                    </div>
                    <div class="text-3xl font-bold text-slate-800 dark:text-white">{{ $stats['questions_count'] }}</div>
                </div>
                
                <div class="bg-white dark:bg-slate-800/50 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700/50">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                            <i class="fas fa-user-graduate text-green-600 dark:text-green-400"></i>
                        </div>
                        <span class="text-sm text-slate-500 dark:text-slate-400">Students</span>
                    </div>
                    <div class="text-3xl font-bold text-slate-800 dark:text-white">{{ $stats['students_completed'] }}</div>
                </div>
                
                <div class="bg-white dark:bg-slate-800/50 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700/50">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                            <i class="fas fa-brain text-purple-600 dark:text-purple-400"></i>
                        </div>
                        <span class="text-sm text-slate-500 dark:text-slate-400">Avg LMS</span>
                    </div>
                    <div class="text-3xl font-bold text-slate-800 dark:text-white">{{ $stats['avg_lms'] }}</div>
                </div>
                
                <div class="bg-white dark:bg-slate-800/50 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700/50">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                            <i class="fas fa-redo text-amber-600 dark:text-amber-400"></i>
                        </div>
                        <span class="text-sm text-slate-500 dark:text-slate-400">Total Attempts</span>
                    </div>
                    <div class="text-3xl font-bold text-slate-800 dark:text-white">{{ $stats['total_attempts'] }}</div>
                </div>
            </div>

            {{-- Settings Form --}}
            <div class="bg-white dark:bg-slate-800/50 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700/50 p-6">
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-6 flex items-center gap-2">
                    <i class="fas fa-sliders-h text-blue-500"></i>
                    Level Indicator Exam Settings
                </h3>
                
                <form action="{{ route('instructor.module.settings.update', $module) }}" method="POST">
                    @csrf
                    
                    {{-- Max Attempts Setting --}}
                    <div class="mb-6">
                        <label for="max_level_indicator_attempts" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            <i class="fas fa-repeat mr-2 text-blue-500"></i>
                            Maximum Attempts per Student
                        </label>
                        <div class="flex items-center gap-4">
                            <input type="number" 
                                   name="max_level_indicator_attempts" 
                                   id="max_level_indicator_attempts"
                                   value="{{ $module->max_level_indicator_attempts ?? 3 }}"
                                   min="1" max="10"
                                   class="w-24 px-4 py-3 text-lg font-bold text-center rounded-xl border-2 border-slate-200 dark:border-slate-700 dark:bg-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                            <div class="text-sm text-slate-500 dark:text-slate-400">
                                <p>Number of times a student can attempt the Level Indicator Exam.</p>
                                <p class="text-xs mt-1">Allowed range: 1 - 10 attempts</p>
                            </div>
                        </div>
                        @error('max_level_indicator_attempts')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    {{-- Description Setting --}}
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            <i class="fas fa-align-left mr-2 text-blue-500"></i>
                            Module Description
                        </label>
                        <textarea name="description" 
                                  id="description"
                                  rows="3"
                                  class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 dark:border-slate-700 dark:bg-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition"
                                  placeholder="Optional module description...">{{ $module->description }}</textarea>
                        @error('description')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    {{-- Info Box --}}
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4 mb-6">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                            <div class="text-sm text-blue-700 dark:text-blue-300">
                                <p class="font-medium mb-1">About Level Indicator Attempts</p>
                                <p>Each attempt allows students to retake the diagnostic exam and potentially improve their Learning Mastery Score (LMS). More attempts give students additional chances to demonstrate their knowledge, while fewer attempts encourage more focused preparation.</p>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Submit Button --}}
                    <div class="flex justify-end">
                        <button type="submit" 
                                class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-500 to-cyan-600 hover:from-blue-600 hover:to-cyan-700 text-white rounded-xl font-semibold shadow-lg shadow-blue-500/25 transition-all hover:shadow-blue-500/40">
                            <i class="fas fa-save"></i>
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>

            {{-- ============================================================ --}}
            {{-- QUESTION BANK MANAGEMENT                                      --}}
            {{-- ============================================================ --}}
            <div class="bg-white dark:bg-slate-800/50 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700/50 p-6 mt-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-white flex items-center gap-2">
                        <i class="fas fa-database text-indigo-500"></i>
                        Question Bank
                        <span class="ml-2 px-2.5 py-0.5 bg-indigo-100 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-300 text-xs font-bold rounded-full">{{ $questions->count() }}</span>
                    </h3>
                    <div class="flex gap-2 flex-wrap">
                        <button onclick="document.getElementById('addQuestionModal').classList.remove('hidden')"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white rounded-lg font-medium text-sm shadow-lg shadow-green-500/25 transition-all">
                            <i class="fas fa-plus"></i> Add Question
                        </button>
                        <button onclick="document.getElementById('importSection').classList.toggle('hidden')"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white rounded-lg font-medium text-sm shadow-lg shadow-amber-500/25 transition-all">
                            <i class="fas fa-file-import"></i> Bulk Import
                        </button>
                    </div>
                </div>

                {{-- Bulk Import Section (hidden by default) --}}
                <div id="importSection" class="hidden mb-6 p-5 bg-amber-50 dark:bg-amber-900/10 border border-amber-200 dark:border-amber-800 rounded-xl">
                    <h4 class="text-sm font-semibold text-amber-800 dark:text-amber-300 mb-3 flex items-center gap-2">
                        <i class="fas fa-file-excel"></i> Import Questions from Excel/CSV
                    </h4>
                    <form action="{{ route('instructor.module.questions.import', $module) }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row gap-3 items-start sm:items-end">
                        @csrf
                        <div class="flex-1">
                            <label class="block text-xs text-amber-700 dark:text-amber-400 mb-1">Select file (.xlsx, .xls, .csv)</label>
                            <input type="file" name="excel_file" accept=".xlsx,.xls,.csv" required
                                class="block w-full text-sm text-slate-700 dark:text-slate-300 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-amber-100 file:text-amber-700 dark:file:bg-amber-900/30 dark:file:text-amber-300 hover:file:bg-amber-200 transition">
                        </div>
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg font-medium text-sm transition">
                            <i class="fas fa-upload"></i> Import
                        </button>
                        <a href="{{ route('instructor.module.questions.template') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-lg font-medium text-sm hover:bg-slate-300 dark:hover:bg-slate-600 transition">
                            <i class="fas fa-download"></i> Download Template
                        </a>
                    </form>
                    @error('excel_file')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-xs text-amber-600 dark:text-amber-500">
                        Template columns: question_text, type (mcq/true_false/fill_in_blank), difficulty (1-3), is_hard (true/false), answer_1, correct_1, answer_2, correct_2, answer_3, correct_3, answer_4, correct_4
                    </p>
                </div>

                {{-- Error Message --}}
                @if(session('error'))
                <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-400 text-sm">
                    <i class="fas fa-exclamation-triangle mr-1"></i>{{ session('error') }}
                </div>
                @endif

                {{-- Search & Filter --}}
                <div class="flex flex-col sm:flex-row gap-3 mb-4">
                    <div class="flex-1 relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" id="questionSearch" placeholder="Search questions..."
                            class="w-full pl-10 pr-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-700 dark:bg-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition"
                            oninput="filterQuestions()">
                    </div>
                    <select id="typeFilter" onchange="filterQuestions()" class="px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-700 dark:bg-slate-800 focus:border-indigo-500 transition">
                        <option value="">All Types</option>
                        <option value="mcq">MCQ</option>
                        <option value="true_false">True/False</option>
                        <option value="fill_in_blank">Fill in Blank</option>
                    </select>
                    <select id="difficultyFilter" onchange="filterQuestions()" class="px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-700 dark:bg-slate-800 focus:border-indigo-500 transition">
                        <option value="">All Difficulties</option>
                        <option value="1">Easy</option>
                        <option value="2">Medium</option>
                        <option value="3">Hard</option>
                    </select>
                </div>

                {{-- Questions Table --}}
                @if($questions->count() > 0)
                <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-700">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-700/50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">#</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider min-w-[250px]">Question</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Difficulty</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Answers</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50" id="questionsTableBody">
                            @foreach($questions as $index => $q)
                            <tr class="question-row hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors"
                                data-type="{{ $q->type }}"
                                data-difficulty="{{ $q->difficulty }}"
                                data-text="{{ strtolower($q->question_text) }}">
                                <td class="px-4 py-3 text-slate-500 dark:text-slate-400 font-mono text-xs">{{ $q->id }}</td>
                                <td class="px-4 py-3 text-slate-800 dark:text-slate-200">
                                    <div class="max-w-md truncate" title="{{ $q->question_text }}">{{ Str::limit($q->question_text, 80) }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $typeColors = [
                                            'mcq' => 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300',
                                            'true_false' => 'bg-purple-100 text-purple-700 dark:bg-purple-500/20 dark:text-purple-300',
                                            'fill_in_blank' => 'bg-teal-100 text-teal-700 dark:bg-teal-500/20 dark:text-teal-300',
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 rounded-lg text-xs font-semibold {{ $typeColors[$q->type] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ str_replace('_', ' ', ucfirst($q->type)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $diffColors = [
                                            1 => 'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-300',
                                            2 => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-300',
                                            3 => 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-300',
                                        ];
                                        $diffLabels = [1 => 'Easy', 2 => 'Medium', 3 => 'Hard'];
                                    @endphp
                                    <span class="px-2 py-1 rounded-lg text-xs font-semibold {{ $diffColors[$q->difficulty] ?? 'bg-gray-100' }}">
                                        {{ $diffLabels[$q->difficulty] ?? 'Unknown' }}
                                    </span>
                                    @if($q->is_hard)
                                        <span class="ml-1 text-xs text-red-500" title="Marked as Hard"><i class="fas fa-fire"></i></span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-1 bg-slate-100 dark:bg-slate-700 rounded-lg text-xs font-medium text-slate-600 dark:text-slate-400">
                                        {{ $q->answers->count() }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <button onclick="openEditModal({{ $q->id }}, {{ json_encode($q) }})"
                                            class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-500/10 rounded-lg transition" title="Edit">
                                            <i class="fas fa-edit text-sm"></i>
                                        </button>
                                        <button onclick="openDeleteModal({{ $q->id }}, '{{ addslashes(Str::limit($q->question_text, 50)) }}')"
                                            class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-lg transition" title="Delete">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-12 text-slate-400 dark:text-slate-500">
                    <i class="fas fa-inbox text-4xl mb-3"></i>
                    <p class="font-medium">No questions in this module yet</p>
                    <p class="text-sm mt-1">Click "Add Question" or use "Bulk Import" to get started.</p>
                </div>
                @endif
            </div>

            {{-- Quick Links --}}
            <div class="mt-6 flex flex-wrap gap-4 justify-center">
                <a href="{{ route('dashboard') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-lg font-medium hover:bg-slate-300 dark:hover:bg-slate-600 transition">
                    <i class="fas fa-tachometer-alt"></i>
                    Back to Dashboard
                </a>
            </div>

        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- ADD QUESTION MODAL                                            --}}
    {{-- ============================================================ --}}
    <div id="addQuestionModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white dark:bg-slate-800 px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between rounded-t-2xl z-10">
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white flex items-center gap-2">
                    <i class="fas fa-plus-circle text-green-500"></i> Add New Question
                </h3>
                <button onclick="document.getElementById('addQuestionModal').classList.add('hidden')" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition">
                    <i class="fas fa-times text-slate-400"></i>
                </button>
            </div>
            <form action="{{ route('instructor.module.questions.store', $module) }}" method="POST" class="p-6">
                @csrf
                {{-- Question Text --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Question Text *</label>
                    <textarea name="question_text" rows="3" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 dark:bg-slate-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm transition"
                        placeholder="Enter your question here..."></textarea>
                </div>
                {{-- Type + Difficulty Row --}}
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Type *</label>
                        <select name="type" id="addType" onchange="handleTypeChange('add')" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-sm transition">
                            <option value="mcq">MCQ</option>
                            <option value="true_false">True / False</option>
                            <option value="fill_in_blank">Fill in Blank</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Difficulty *</label>
                        <select name="difficulty" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-sm transition">
                            <option value="1">Easy</option>
                            <option value="2" selected>Medium</option>
                            <option value="3">Hard</option>
                        </select>
                    </div>
                </div>
                {{-- Answers Section --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Answers *</label>
                    <div id="addAnswersContainer">
                        <div class="answer-row flex items-center gap-2 mb-2">
                            <input type="text" name="answers[0][answer_text]" placeholder="Answer text" required
                                class="flex-1 px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-sm transition">
                            <label class="flex items-center gap-1.5 text-xs text-slate-600 dark:text-slate-400 whitespace-nowrap cursor-pointer">
                                <input type="hidden" name="answers[0][is_correct]" value="0">
                                <input type="checkbox" name="answers[0][is_correct]" value="1"
                                    class="w-4 h-4 rounded border-slate-300 text-green-600 focus:ring-green-500">
                                Correct
                            </label>
                        </div>
                        <div class="answer-row flex items-center gap-2 mb-2">
                            <input type="text" name="answers[1][answer_text]" placeholder="Answer text" required
                                class="flex-1 px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-sm transition">
                            <label class="flex items-center gap-1.5 text-xs text-slate-600 dark:text-slate-400 whitespace-nowrap cursor-pointer">
                                <input type="hidden" name="answers[1][is_correct]" value="0">
                                <input type="checkbox" name="answers[1][is_correct]" value="1"
                                    class="w-4 h-4 rounded border-slate-300 text-green-600 focus:ring-green-500">
                                Correct
                            </label>
                        </div>
                    </div>
                    <button type="button" onclick="addAnswerRow('add')"
                        class="mt-1 text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 font-medium flex items-center gap-1">
                        <i class="fas fa-plus"></i> Add Another Answer
                    </button>
                </div>
                {{-- Submit --}}
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                    <button type="button" onclick="document.getElementById('addQuestionModal').classList.add('hidden')"
                        class="px-4 py-2 text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition">Cancel</button>
                    <button type="submit"
                        class="px-5 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg font-medium text-sm shadow-lg shadow-green-500/25 transition-all hover:from-green-600 hover:to-emerald-700">
                        <i class="fas fa-check mr-1"></i> Add Question
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- EDIT QUESTION MODAL                                           --}}
    {{-- ============================================================ --}}
    <div id="editQuestionModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white dark:bg-slate-800 px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between rounded-t-2xl z-10">
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white flex items-center gap-2">
                    <i class="fas fa-edit text-blue-500"></i> Edit Question
                </h3>
                <button onclick="document.getElementById('editQuestionModal').classList.add('hidden')" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition">
                    <i class="fas fa-times text-slate-400"></i>
                </button>
            </div>
            <form id="editForm" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Question Text *</label>
                    <textarea name="question_text" id="editQuestionText" rows="3" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 dark:bg-slate-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm transition"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Type *</label>
                        <select name="type" id="editType" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-sm transition">
                            <option value="mcq">MCQ</option>
                            <option value="true_false">True / False</option>
                            <option value="fill_in_blank">Fill in Blank</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Difficulty *</label>
                        <select name="difficulty" id="editDifficulty" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-sm transition">
                            <option value="1">Easy</option>
                            <option value="2">Medium</option>
                            <option value="3">Hard</option>
                        </select>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Answers *</label>
                    <div id="editAnswersContainer"></div>
                    <button type="button" onclick="addAnswerRow('edit')"
                        class="mt-1 text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 font-medium flex items-center gap-1">
                        <i class="fas fa-plus"></i> Add Another Answer
                    </button>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                    <button type="button" onclick="document.getElementById('editQuestionModal').classList.add('hidden')"
                        class="px-4 py-2 text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition">Cancel</button>
                    <button type="submit"
                        class="px-5 py-2 bg-gradient-to-r from-blue-500 to-cyan-600 text-white rounded-lg font-medium text-sm shadow-lg shadow-blue-500/25 transition-all hover:from-blue-600 hover:to-cyan-700">
                        <i class="fas fa-save mr-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- DELETE CONFIRMATION MODAL                                     --}}
    {{-- ============================================================ --}}
    <div id="deleteModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md p-6">
            <div class="text-center mb-4">
                <div class="w-14 h-14 bg-red-100 dark:bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Delete Question?</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2" id="deleteQuestionText"></p>
            </div>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex gap-3 justify-center">
                    <button type="button" onclick="document.getElementById('deleteModal').classList.add('hidden')"
                        class="px-5 py-2 text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition">Cancel</button>
                    <button type="submit"
                        class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium text-sm transition">
                        <i class="fas fa-trash mr-1"></i> Delete
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- JAVASCRIPT                                                    --}}
    {{-- ============================================================ --}}
    <script>
        // ---- Search & Filter ----
        function filterQuestions() {
            const search = document.getElementById('questionSearch').value.toLowerCase();
            const typeFilter = document.getElementById('typeFilter').value;
            const diffFilter = document.getElementById('difficultyFilter').value;

            document.querySelectorAll('.question-row').forEach(row => {
                const text = row.dataset.text;
                const type = row.dataset.type;
                const diff = row.dataset.difficulty;

                const matchSearch = !search || text.includes(search);
                const matchType = !typeFilter || type === typeFilter;
                const matchDiff = !diffFilter || diff === diffFilter;

                row.style.display = (matchSearch && matchType && matchDiff) ? '' : 'none';
            });
        }

        // ---- Dynamic Answer Rows ----
        let addAnswerIndex = 2;
        let editAnswerIndex = 0;

        function addAnswerRow(mode) {
            const container = document.getElementById(mode + 'AnswersContainer');
            const idx = mode === 'add' ? addAnswerIndex++ : editAnswerIndex++;
            const div = document.createElement('div');
            div.className = 'answer-row flex items-center gap-2 mb-2';
            div.innerHTML = `
                <input type="text" name="answers[${idx}][answer_text]" placeholder="Answer text" required
                    class="flex-1 px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-sm transition">
                <label class="flex items-center gap-1.5 text-xs text-slate-600 dark:text-slate-400 whitespace-nowrap cursor-pointer">
                    <input type="hidden" name="answers[${idx}][is_correct]" value="0">
                    <input type="checkbox" name="answers[${idx}][is_correct]" value="1"
                        class="w-4 h-4 rounded border-slate-300 text-green-600 focus:ring-green-500">
                    Correct
                </label>
                <button type="button" onclick="this.closest('.answer-row').remove()" class="p-1.5 text-red-400 hover:text-red-600 transition">
                    <i class="fas fa-times text-xs"></i>
                </button>
            `;
            container.appendChild(div);
        }

        // ---- Edit Modal ----
        function openEditModal(id, question) {
            const baseUrl = "{{ route('instructor.module.questions.update', [$module->id, '__ID__']) }}";
            document.getElementById('editForm').action = baseUrl.replace('__ID__', id);
            document.getElementById('editQuestionText').value = question.question_text;
            document.getElementById('editType').value = question.type;
            document.getElementById('editDifficulty').value = question.difficulty;

            // Populate answers
            const container = document.getElementById('editAnswersContainer');
            container.innerHTML = '';
            editAnswerIndex = 0;

            (question.answers || []).forEach((ans, i) => {
                editAnswerIndex = i + 1;
                const div = document.createElement('div');
                div.className = 'answer-row flex items-center gap-2 mb-2';
                const checked = ans.is_correct ? 'checked' : '';
                div.innerHTML = `
                    <input type="text" name="answers[${i}][answer_text]" value="${escapeHtml(ans.answer_text)}" required
                        class="flex-1 px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-sm transition">
                    <label class="flex items-center gap-1.5 text-xs text-slate-600 dark:text-slate-400 whitespace-nowrap cursor-pointer">
                        <input type="hidden" name="answers[${i}][is_correct]" value="0">
                        <input type="checkbox" name="answers[${i}][is_correct]" value="1" ${checked}
                            class="w-4 h-4 rounded border-slate-300 text-green-600 focus:ring-green-500">
                        Correct
                    </label>
                    <button type="button" onclick="this.closest('.answer-row').remove()" class="p-1.5 text-red-400 hover:text-red-600 transition">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                `;
                container.appendChild(div);
            });

            document.getElementById('editQuestionModal').classList.remove('hidden');
        }

        // ---- Delete Modal ----
        function openDeleteModal(id, text) {
            const baseUrl = "{{ route('instructor.module.questions.delete', [$module->id, '__ID__']) }}";
            document.getElementById('deleteForm').action = baseUrl.replace('__ID__', id);
            document.getElementById('deleteQuestionText').textContent = 'Are you sure you want to delete: "' + text + '"? This action cannot be undone.';
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        // ---- Utilities ----
        function escapeHtml(text) {
            const el = document.createElement('span');
            el.textContent = text;
            return el.innerHTML;
        }

        function handleTypeChange(mode) {
            // Auto-populate True/False answers when type is true_false
            const type = document.getElementById(mode + 'Type').value;
            if (type === 'true_false') {
                const container = document.getElementById(mode + 'AnswersContainer');
                container.innerHTML = '';
                const idx = mode === 'add' ? 0 : 0;
                if (mode === 'add') addAnswerIndex = 2;
                else editAnswerIndex = 2;
                container.innerHTML = `
                    <div class="answer-row flex items-center gap-2 mb-2">
                        <input type="text" name="answers[0][answer_text]" value="True" required readonly
                            class="flex-1 px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-sm bg-slate-50 dark:bg-slate-800 transition">
                        <label class="flex items-center gap-1.5 text-xs text-slate-600 dark:text-slate-400 whitespace-nowrap cursor-pointer">
                            <input type="hidden" name="answers[0][is_correct]" value="0">
                            <input type="checkbox" name="answers[0][is_correct]" value="1"
                                class="w-4 h-4 rounded border-slate-300 text-green-600 focus:ring-green-500">
                            Correct
                        </label>
                    </div>
                    <div class="answer-row flex items-center gap-2 mb-2">
                        <input type="text" name="answers[1][answer_text]" value="False" required readonly
                            class="flex-1 px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 dark:bg-slate-900 text-sm bg-slate-50 dark:bg-slate-800 transition">
                        <label class="flex items-center gap-1.5 text-xs text-slate-600 dark:text-slate-400 whitespace-nowrap cursor-pointer">
                            <input type="hidden" name="answers[1][is_correct]" value="0">
                            <input type="checkbox" name="answers[1][is_correct]" value="1"
                                class="w-4 h-4 rounded border-slate-300 text-green-600 focus:ring-green-500">
                            Correct
                        </label>
                    </div>
                `;
            }
        }
    </script>

</x-app-layout>
