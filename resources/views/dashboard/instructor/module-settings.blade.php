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
</x-app-layout>
