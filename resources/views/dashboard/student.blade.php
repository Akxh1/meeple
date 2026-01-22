<x-app-layout>
    
    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800 dark:text-white">
                        Student Dashboard
                    </h2>
                    <p class="text-gray-500 dark:text-gray-400 mt-1">
                        Track your progress and risk analysis.
                    </p>
                </div>
                <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition shadow-md">
                    View Analytics Report
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                @foreach($modules as $module)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden group">
                    
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div class="{{ $module['image_color'] }} w-12 h-12 rounded-lg flex items-center justify-center text-2xl shadow-md text-white">
                                {{ $module['icon'] }}
                            </div>
                            @php
                                $riskColor = match($module['risk_score']) {
                                    'High' => 'bg-red-100 text-red-700 border-red-200',
                                    'Medium' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                    'Low' => 'bg-green-100 text-green-700 border-green-200',
                                    default => 'bg-gray-100 text-gray-700 border-gray-200'
                                };
                            @endphp
                            <span class="px-2 py-1 rounded text-xs font-semibold border {{ $riskColor }}">
                                {{ $module['risk_score'] }} Risk
                            </span>
                        </div>

                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-1 group-hover:text-indigo-600 transition-colors">
                            {{ $module['title'] }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $module['category'] }}
                        </p>

                        <div class="mt-6">
                            <div class="flex justify-between text-xs mb-1">
                                <span class="text-gray-600 dark:text-gray-300 font-medium">{{ $module['progress'] }}% Complete</span>
                                <span class="text-gray-400">{{ $module['completed_lessons'] }}/{{ $module['total_lessons'] }} Lessons</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-indigo-600 h-2 rounded-full transition-all duration-1000" style="width: {{ $module['progress'] }}%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            <i class="far fa-clock mr-1"></i> {{ $module['last_accessed'] }}
                        </span>
                        <a href="#" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 text-sm font-semibold flex items-center gap-1">
                            Continue <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>
</x-app-layout>