<x-app-layout>
    <div
        class="p-0 bg-gradient-to-br from-indigo-50 via-white to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-indigo-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-4xl font-extrabold text-indigo-700 dark:text-indigo-200 tracking-tight mb-2">👩‍🏫
                        Teacher Dashboard</h1>
                    <p class="text-gray-600 dark:text-gray-400 text-lg">Monitor, compare, and analyze your students'
                        progress visually and interactively.</p>
                </div>
                <div class="flex gap-2">
                    <button
                        class="px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition">Export
                        Data</button>
                    <button
                        class="px-4 py-2 rounded-lg bg-white dark:bg-gray-800 text-indigo-600 dark:text-indigo-300 font-semibold border border-indigo-200 dark:border-indigo-700 shadow hover:bg-indigo-50 dark:hover:bg-gray-700 transition">Settings</button>
                </div>
            </div>

            <!-- Metrics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 flex flex-col items-center">
                    <span class="text-2xl mb-2 text-red-500"><i class="fa fa-exclamation-triangle"></i></span>
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-white mb-1">Average Risk Score</h3>
                    <div class="text-4xl font-bold text-red-500 mb-2">Under Development</div>
                    <span class="text-xs text-gray-400">Risk prediction coming soon</span>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 flex flex-col items-center">
                    <span class="text-2xl mb-2 text-indigo-500"><i class="fa fa-user-check"></i></span>
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-white mb-1">Average Attendance</h3>
                    <div class="text-4xl font-bold text-indigo-500 mb-2">
                        {{ number_format($students->avg('progress.attendance_rate'), 1) }}%
                    </div>
                    <span class="text-xs text-gray-400">Across all students</span>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 flex flex-col items-center">
                    <span class="text-2xl mb-2 text-yellow-500"><i class="fa fa-bolt"></i></span>
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-white mb-1">Engagement Score</h3>
                    <div class="text-4xl font-bold text-yellow-500 mb-2">
                        {{ number_format($students->avg('progress.engagement_score'), 1) }}
                    </div>
                    <span class="text-xs text-gray-400">Class average</span>
                </div>
            </div>

            <!-- Interactive Chart & Filters -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
                <div class="col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                        <i class="fa fa-chart-bar text-indigo-400"></i>
                        Performance Overview
                    </h2>
                    <canvas id="performanceChart" height="120"></canvas>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 flex flex-col gap-6">
                    <h2 class="text-lg font-semibold text-gray-700 dark:text-white mb-2">Quick Filters</h2>
                    <div>
                        <label for="riskFilter"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Risk Score</label>
                        <select id="riskFilter"
                            class="w-full p-2 rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="all">All</option>
                            <option value="high">High Risk (0-40)</option>
                            <option value="medium">Medium Risk (41-70)</option>
                            <option value="low">Low Risk (71-100)</option>
                        </select>
                    </div>
                    <div>
                        <label for="attendanceFilter"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Attendance</label>
                        <input type="range" min="0" max="100" value="75" id="attendanceFilter"
                            class="w-full accent-indigo-500">
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Min Attendance: <span
                                id="attendanceValue">75</span>%</div>
                    </div>
                </div>
            </div>

            <!-- Student Table with Expandable Rows -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fa fa-users text-indigo-400"></i>
                    Student Performance Table
                </h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold text-gray-500 dark:text-gray-300 uppercase">Student
                                </th>
                                <th class="px-4 py-2">Exam 1</th>
                                <th class="px-4 py-2">Exam 2</th>
                                <th class="px-4 py-2">Exam 3</th>
                                <th class="px-4 py-2">Attendance</th>
                                <th class="px-4 py-2">Engagement</th>
                                <th class="px-4 py-2">Quiz</th>
                                <th class="px-4 py-2">Group</th>
                                <th class="px-4 py-2">Revision
                                    <span class="block text-xs text-gray-400 dark:text-gray-500 font-normal ml-1">(hrs)</span>
                                </th>
                                <th class="px-4 py-2">Risk</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                        </thead>
                        <tbody id="studentsTable"
                            class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($students as $student)
                                <tr class="hover:bg-indigo-50 dark:hover:bg-indigo-900 transition group">
                                    <td class="px-4 py-2 font-medium text-gray-700 dark:text-white flex items-center gap-2">
                                        <span
                                            class="inline-block w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-300 font-bold">
                                            {{ strtoupper(substr($student->name, 0, 1)) }}
                                        </span>
                                        <span>
                                            <span class="block">{{ $student->name }}</span>
                                            <span class="text-xs text-gray-400">{{ $student->student_id }}</span>
                                        </span>
                                    </td>
                                    <td class="text-center">{{ $student->progress->exam_1 ?? '-' }}</td>
                                    <td class="text-center">{{ $student->progress->exam_2 ?? '-' }}</td>
                                    <td class="text-center">{{ $student->progress->exam_3 ?? '-' }}</td>
                                    <td class="text-center">
                                        <span
                                            class="inline-block px-2 py-1 rounded bg-indigo-50 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-300 font-semibold">
                                            {{ $student->progress->attendance_rate ?? '-' }}%
                                        </span>
                                    </td>
                                    <td class="text-center">{{ $student->progress->engagement_score ?? '-' }}</td>
                                    <td class="text-center">{{ $student->progress->quiz_score ?? '-' }}</td>
                                    <td class="text-center">{{ $student->progress->group_work_score ?? '-' }}</td>
                                    <td class="text-center">{{ $student->progress->revision_hours ?? '-' }}</td>
                                    <td class="text-center text-red-500 font-semibold">Under Development</td>
                                    <td class="text-center">
                                        <button
                                            class="expand-row px-2 py-1 rounded bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-indigo-200 hover:bg-indigo-200 dark:hover:bg-indigo-700 transition"
                                            title="View Details">
                                            <i class="fa fa-chevron-down"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="hidden student-details bg-indigo-50 dark:bg-indigo-900">
                                    <td colspan="12" class="px-4 py-3">
                                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                            <div>
                                                <span class="font-semibold text-gray-700 dark:text-white">Email:</span>
                                                <span class="text-gray-600 dark:text-gray-300">{{ $student->user->email ?? '-' }}</span>
                                            </div>
                                            <div>
                                                <span class="font-semibold text-gray-700 dark:text-white">Joined date:</span>
                                                <span class="text-gray-600 dark:text-gray-300">{{ $student->created_at?? '-' }}</span>
                                            </div>
                                            <div>
                                                <span class="font-semibold text-gray-700 dark:text-white">Quiz Score:</span>
                                                <span class="text-gray-600 dark:text-gray-300">{{ $student->progress->quiz_score ?? '-' }}</span>
                                            </div>
                                            <div>
                                                <span class="font-semibold text-gray-700 dark:text-white">Group Work:</span>
                                                <span class="text-gray-600 dark:text-gray-300">{{ $student->progress->group_work_score ?? '-' }}</span>
                                            </div>
                                            <div>
                                                <span class="font-semibold text-gray-700 dark:text-white">Engagement:</span>
                                                <span class="text-gray-600 dark:text-gray-300">{{ $student->progress->engagement_score ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js CDN & FontAwesome -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <script>
        // Chart Data
        const students = @json($students);

        const labels = students.map(s => s.name);
        const exam1 = students.map(s => s.progress.exam_1 || 0);
        const exam2 = students.map(s => s.progress.exam_2 || 0);
        const exam3 = students.map(s => s.progress.exam_3 || 0);

        const ctx = document.getElementById('performanceChart').getContext('2d');
        const performanceChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                        label: 'Exam 1',
                        data: exam1,
                        backgroundColor: 'rgba(99, 102, 241, 0.7)',
                        borderRadius: 8,
                    },
                    {
                        label: 'Exam 2',
                        data: exam2,
                        backgroundColor: 'rgba(239, 68, 68, 0.7)',
                        borderRadius: 8,
                    },
                    {
                        label: 'Exam 3',
                        data: exam3,
                        backgroundColor: 'rgba(253, 224, 71, 0.7)',
                        borderRadius: 8,
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.parsed.y}`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#e5e7eb',
                            drawBorder: false
                        }
                    }
                }
            }
        });

        // Expandable Table Rows
        document.querySelectorAll('.expand-row').forEach((btn, idx) => {
            btn.addEventListener('click', function() {
                const detailsRow = btn.closest('tr').nextElementSibling;
                detailsRow.classList.toggle('hidden');
                btn.querySelector('i').classList.toggle('fa-chevron-down');
                btn.querySelector('i').classList.toggle('fa-chevron-up');
            });
        });

        // Attendance Filter
        document.getElementById('attendanceFilter').addEventListener('input', function() {
            document.getElementById('attendanceValue').innerText = this.value;
            // You can add JS filtering logic here if you want live table filtering
        });
    </script>
</x-app-layout>
