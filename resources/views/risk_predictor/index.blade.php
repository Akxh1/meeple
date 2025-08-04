<!-- resources/views/risk_predictor/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-semibold flex items-center space-x-2">
            <span>📉</span>
            <span>Student Risk Predictor</span>
        </h1>
    </x-slot>

    <form method="POST" action="{{ route('risk.predictor.upload') }}" enctype="multipart/form-data" class="mt-8 max-w-lg mx-auto space-y-6 bg-white dark:bg-gray-900 rounded-xl shadow-lg p-8 border border-gray-200 dark:border-gray-800">
        @csrf
        <label for="student_data" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
            Upload Student Data <span class="text-xs text-gray-400">(Excel .xlsx or .xls)</span>
        </label>
        <input id="student_data" type="file" name="student_data" accept=".xlsx,.xls"
            class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 px-4 py-3 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-yellow-400 transition" required>

        <button type="submit"
            class="w-full bg-gradient-to-r from-yellow-400 to-yellow-600 hover:from-yellow-500 hover:to-yellow-700 text-white font-bold py-3 rounded-lg shadow transition-all duration-200 flex items-center justify-center gap-2 text-lg">
            <i class="fa fa-upload"></i>
            Upload & Predict
        </button>
    </form>

    @if(session('predictions'))
        <section class="mt-12 max-w-4xl mx-auto bg-white dark:bg-gray-900 rounded-2xl shadow-2xl p-8 border border-gray-200 dark:border-gray-800">
            <h2 class="text-2xl font-extrabold mb-8 border-b border-gray-200 dark:border-gray-700 pb-3 flex items-center gap-2">
                <i class="fa fa-chart-line text-yellow-500"></i>
                Prediction Scores
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach(session('predictions') as $studentId => $score)
                    @php
                        $category = '';
                        $badgeColor = '';
                        $progressColor = '';

                        if ($score >= 75) {
                            $category = 'Likely to Pass';
                            $badgeColor = 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100';
                            $progressColor = '#22c55e'; // green-500
                        } elseif ($score >= 40) {
                            $category = 'At Risk / Stagnant';
                            $badgeColor = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100';
                            $progressColor = '#eab308'; // yellow-500
                        } else {
                            $category = 'Likely to Fail';
                            $badgeColor = 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100';
                            $progressColor = '#ef4444'; // red-500
                        }
                    @endphp

                    <div class="flex flex-col items-center justify-center bg-gradient-to-br from-gray-50 via-yellow-50 to-white dark:from-gray-800 dark:via-gray-900 dark:to-gray-800 rounded-xl shadow p-6 border border-gray-100 dark:border-gray-800 hover:shadow-lg transition-shadow duration-200">
                        <div class="w-28 h-28 mb-4">
                            <!-- ProgressBar.js Circle -->
                            <div id="progress-{{ $studentId }}"></div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    if (!window['progressBar_{{ $studentId }}']) {
                                        window['progressBar_{{ $studentId }}'] = new ProgressBar.Circle('#progress-{{ $studentId }}', {
                                            color: '{{ $progressColor }}',
                                            strokeWidth: 8,
                                            trailWidth: 4,
                                            trailColor: '#e5e7eb',
                                            easing: 'easeInOut',
                                            duration: 1400,
                                            text: {
                                                autoStyleContainer: false
                                            },
                                            from: { color: '{{ $progressColor }}', width: 8 },
                                            to: { color: '{{ $progressColor }}', width: 8 },
                                            step: function(state, circle) {
                                                circle.setText(Math.round(circle.value() * 100) + ' / 100');
                                            }
                                        });
                                        window['progressBar_{{ $studentId }}'].animate({{ $score }} / 100);
                                    }
                                });
                            </script>
                        </div>
                        <div class="text-center">
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1"> {{ $studentId }}</p>
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold mb-2 {{ $badgeColor }}">
                                {{ $category }}
                            </span>
                            <div class="text-gray-500 dark:text-gray-400 text-sm">Score: <span class="font-bold">{{ $score }}</span></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
        <!-- ProgressBar.js CDN -->
        <script src="https://cdn.jsdelivr.net/npm/progressbar.js@1.1.0/dist/progressbar.min.js"></script>
    @endif
</x-app-layout>
