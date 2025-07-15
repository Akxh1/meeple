<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 dark:text-white tracking-tight">Welcome to MEEPLE</h2>
    </x-slot>

    <!-- ORB Background Hero Section -->
    <section class="relative h-[600px] w-full overflow-hidden rounded-xl shadow-xl mb-12">
        <!-- Orb Canvas container, absolutely positioned -->
        <div id="orb-root" class="absolute inset-0 -z-10"></div>

        <!-- Optional subtle light overlay for light mode, and dark overlay for dark mode -->
        <div class="absolute inset-0 rounded-xl -z-5
            bg-white/40 dark:bg-black/60"></div>

        <!-- Hero content centered -->
        <div class="relative z-10 flex flex-col justify-center items-center h-full px-6 text-center max-w-4xl mx-auto">
            <h1 class="text-6xl font-extrabold text-gray-900 dark:text-white drop-shadow-md leading-tight sm:text-7xl">
                MEEPLE
            </h1>
            <p class="mt-4 text-xl text-gray-800 dark:text-white/90 max-w-xl">
                Gamify Exams. Recommend Smartly. Upload Easily.
            </p>
            <a href="#what-is-meeple"
                class="mt-8 inline-block px-6 py-2 border border-gray-700 dark:border-white text-gray-800 dark:text-white font-medium rounded-md hover:bg-gray-100 dark:hover:bg-white/10 transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 dark:focus:ring-white/20">
                Learn What MEEPLE Does
            </a>


        </div>

    </section>

    <div class="space-y-0">
        <!-- Hero Section -->
        <section id="what-is-meeple" class="min-h-screen flex flex-col justify-center text-center px-4 sm:px-6 lg:px-8">
            <div class="space-y-6 max-w-5xl mx-auto">
                <h1
                    class="text-5xl font-extrabold tracking-tight text-gray-900 dark:text-white sm:text-6xl md:text-7xl">
                    Your University Learning + Exam Engine
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    Machine Learning meets education. Playful. Powerful. Academic.
                </p>
                <a href="#section-what"
                    class="inline-block px-6 py-2 border border-gray-800 dark:border-white text-gray-800 dark:text-white font-medium rounded-md hover:bg-gray-100 dark:hover:bg-white/10 transition duration-200 ease-in-out">
                    What is MEEPLE?
                </a>
            </div>
        </section>

        <!-- What is MEEPLE -->
        <section id="section-what" class="min-h-screen bg-gray-50 dark:bg-gray-900 flex items-center">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
                    <div class="space-y-4">
                        <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-100">What is MEEPLE?</h2>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                            MEEPLE is a university-grade platform combining playful design with academic power. It
                            handles
                            exams, assignments, quizzes, and automated grading through Excel uploads and AI-enhanced
                            recommendations. Named after the game token “meeple”, it merges fun with serious learning.
                        </p>
                        <a href="#how-it-works"
                            class="inline-block px-6 py-2 border border-yellow-500 text-yellow-700 dark:text-yellow-300 dark:border-yellow-300 font-medium rounded-md hover:bg-yellow-50 dark:hover:bg-yellow-300/10 transition">
                            How MEEPLE Works
                        </a>
                    </div>
                    <div class="aspect-w-20 aspect-h-20 rounded-xl overflow-hidden shadow-lg">
                        <iframe class="w-full h-full" src="https://www.youtube.com/embed/dQw4w9WgXcQ"
                            title="Intro to MEEPLE" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </section>

        <!-- Interactive How It Works -->
        <section id="how-it-works"
            class="min-h-screen bg-gradient-to-tr from-gray-100 to-gray-50 dark:from-gray-800 dark:to-gray-700 flex items-center">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full space-y-12">
                <h2 class="text-3xl font-bold text-gray-800 dark:text-white text-center">How MEEPLE Works</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @php
                        $cards = [
                            [
                                'title' => 'Excel Upload',
                                'desc' =>
                                    'Teachers upload questions directly via Excel — supporting MCQs, True/False, and Short Answer formats.',
                                'btn' => 'Upload Demo',
                                'href' => route('teacher.questions.upload'),
                                'bg' => 'border-red-500 text-red-600 dark:text-red-300 dark:border-red-400',
                            ],
                            [
                                'title' => 'ML Recommendations',
                                'desc' =>
                                    'MEEPLE learns from student performance and recommends tailored question sets with smart difficulty scaling.',
                                'btn' => 'Simulate ML Assist',
                                'href' => '#',
                                'bg' => 'border-yellow-500 text-yellow-700 dark:text-yellow-300 dark:border-yellow-400',
                            ],
                            [
                                'title' => 'Performance Engine',
                                'desc' =>
                                    'Colorful dashboards, leaderboard gamification, and performance insights per student/module.',
                                'btn' => 'View Sample Stats',
                                'href' => '#',
                                'bg' => 'border-blue-500 text-blue-700 dark:text-blue-300 dark:border-blue-400',
                            ],
                        ];
                    @endphp

                    @foreach ($cards as $card)
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 transition-transform transform hover:-translate-y-1 hover:shadow-lg">
                            <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-3">{{ $card['title'] }}
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 leading-relaxed">{{ $card['desc'] }}
                            </p>
                            <a href="{{ $card['href'] }}"
                                class="inline-block border {{ $card['bg'] }} px-4 py-2 text-sm font-medium rounded hover:bg-opacity-10 transition">
                                {{ $card['btn'] }}
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="text-center">
                    <a href="#final-cta"
                        class="inline-block px-6 py-2 border border-gray-800 dark:border-white text-gray-900 dark:text-white font-medium rounded-md hover:bg-gray-100 dark:hover:bg-white/10 transition duration-200">
                        I'm Ready for MEEPLE
                    </a>
                </div>
            </div>
        </section>

        <!-- Testimonials + Call to Action -->
        <section id="final-cta" class="min-h-screen bg-gray-50 dark:bg-gray-900 px-4 flex items-center">
            <div class="max-w-7xl mx-auto w-full grid grid-cols-1 md:grid-cols-2 gap-12 items-center">

                <!-- Testimonials -->
                <div class="space-y-6">
                    <h2 class="text-3xl font-bold text-gray-800 dark:text-white text-center md:text-left">
                        Trusted by Students & Professors
                    </h2>
                    <div class="space-y-6">
                        <blockquote
                            class="bg-white dark:bg-gray-800 border-l-4 border-meepleBlue pl-6 pr-4 py-4 rounded-md shadow-sm">
                            <p class="text-lg text-gray-700 dark:text-gray-100 leading-relaxed italic">
                                “MEEPLE helped automate grading and let me focus on actual teaching. It helped me learn which students are falling behind despite them being camaflauged or unnoticed in our large physical classrooms”
                            </p>
                            <footer class="mt-3 text-sm text-gray-500">– Prof. Indika, University of Kelaniya</footer>
                        </blockquote>

                        <blockquote
                            class="bg-white dark:bg-gray-800 border-l-4 border-meepleYellow pl-6 pr-4 py-4 rounded-md shadow-sm">
                            <p class="text-lg text-gray-700 dark:text-gray-100 leading-relaxed italic">
                                “I love how it makes practice feel like a game!”
                            </p>
                            <footer class="mt-3 text-sm text-gray-500">– Harini P., 2nd Year IT Student</footer>
                        </blockquote>
                    </div>
                </div>

                <!-- Call to Action -->
                <div class="text-center space-y-6">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Ready to Experience MEEPLE?</h2>
                    <p class="text-gray-600 dark:text-gray-300 max-w-md mx-auto">
                        Upload. Grade. Recommend. All in one academic engine.
                    </p>
                    <a href="{{ route('dashboard') }}"
                        class="inline-block px-6 py-3 border border-red-600 text-red-600 dark:text-red-400 dark:border-red-400 font-medium rounded-md hover:bg-red-50 dark:hover:bg-red-400/10 transition">
                        Go to Dashboard
                    </a>
                </div>
            </div>
        </section>

    </div>

</x-app-layout>
