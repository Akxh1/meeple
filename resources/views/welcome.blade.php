<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 dark:text-white tracking-tight">Welcome to X-Scaffold</h2>
    </x-slot>

    <!-- ORB Background Hero Section -->
    <section class="relative h-[650px] w-full overflow-hidden rounded-2xl shadow-2xl mb-16 mt-8">
        <!-- Orb Canvas container -->
        <div id="orb-root" class="absolute inset-0 -z-10"></div>

        <!-- Gradient overlay for better text visibility -->
        <div class="absolute inset-0 rounded-2xl -z-5
            bg-gradient-to-br from-white/60 via-white/30 to-transparent 
            dark:from-gray-900/80 dark:via-gray-900/50 dark:to-transparent"></div>

        <!-- Hero content -->
        <div class="relative z-10 flex flex-col justify-center items-center h-full px-6 text-center max-w-5xl mx-auto">
            <!-- Logo Badge -->
            <div class="mb-6 px-4 py-2 bg-white/20 dark:bg-white/10 backdrop-blur-sm rounded-full border border-white/30 dark:border-white/20">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-200">🔬 Research Prototype • Final Year Project</span>
            </div>
            
            <h1 class="text-6xl md:text-7xl lg:text-8xl font-black text-gray-900 dark:text-white drop-shadow-lg leading-none tracking-tight">
                X-Scaffold
            </h1>
            
            <p class="mt-6 text-xl md:text-2xl text-gray-700 dark:text-gray-200 max-w-2xl font-light">
                <span class="font-semibold text-indigo-600 dark:text-indigo-400">Predict</span> Performance. 
                <span class="font-semibold text-emerald-600 dark:text-emerald-400">Explain</span> Insights. 
                <span class="font-semibold text-amber-600 dark:text-amber-400">Scaffold</span> Learning.
            </p>
            
            <p class="mt-4 text-base text-gray-600 dark:text-gray-400 max-w-xl">
                An intelligent framework synthesizing ML prediction, XAI diagnostics, and LLM-driven adaptive intervention for next-generation Learning Management Systems.
            </p>

            <div class="mt-10 flex flex-col sm:flex-row gap-4">
                <a href="#framework"
                    class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">
                    Explore the Framework
                </a>
                <a href="#architecture"
                    class="px-8 py-3 border-2 border-gray-800 dark:border-white text-gray-800 dark:text-white font-semibold rounded-xl hover:bg-gray-100 dark:hover:bg-white/10 transition-all duration-300">
                    View Architecture
                </a>
            </div>
        </div>
    </section>

    <div class="space-y-0">
        
        <!-- Predict-Explain-Act Framework Section -->
        <section id="framework" class="min-h-screen flex flex-col justify-center py-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-gray-50 to-white dark:from-gray-900 dark:to-gray-800">
            <div class="max-w-7xl mx-auto w-full">
                <div class="text-center mb-16">
                    <span class="inline-block px-4 py-1 bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 rounded-full text-sm font-semibold mb-4">
                        The Core Framework
                    </span>
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                        Predict → Explain → Act
                    </h2>
                    <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                        A unified pipeline that closes the loop between identifying at-risk students and providing timely, personalized interventions.
                    </p>
                </div>

                <!-- Three Pillars -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                    <!-- PREDICT -->
                    <div class="group relative bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-500 border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-cyan-500"></div>
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-brain text-2xl text-white"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">PREDICT</h3>
                        <p class="text-sm font-semibold text-blue-600 dark:text-blue-400 mb-4">Machine Learning Layer</p>
                        <ul class="space-y-3 text-gray-600 dark:text-gray-300">
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-blue-500 mt-1"></i>
                                <span>Random Forest models trained on 11 behavioral features</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-blue-500 mt-1"></i>
                                <span>Learning Mastery Score (LMS) calculation</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-blue-500 mt-1"></i>
                                <span>4-level risk classification (At-Risk → Advanced)</span>
                            </li>
                        </ul>
                    </div>

                    <!-- EXPLAIN -->
                    <div class="group relative bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-500 border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-500 to-teal-500"></div>
                        <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-lightbulb text-2xl text-white"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">EXPLAIN</h3>
                        <p class="text-sm font-semibold text-emerald-600 dark:text-emerald-400 mb-4">Explainable AI Layer</p>
                        <ul class="space-y-3 text-gray-600 dark:text-gray-300">
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-emerald-500 mt-1"></i>
                                <span>SHAP/LIME interpretability for predictions</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-emerald-500 mt-1"></i>
                                <span>Human-readable "reason codes" for teachers</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-emerald-500 mt-1"></i>
                                <span>Feature contribution visualizations</span>
                            </li>
                        </ul>
                    </div>

                    <!-- ACT -->
                    <div class="group relative bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-500 border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-amber-500 to-orange-500"></div>
                        <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-magic text-2xl text-white"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">ACT</h3>
                        <p class="text-sm font-semibold text-amber-600 dark:text-amber-400 mb-4">LLM Intervention Layer</p>
                        <ul class="space-y-3 text-gray-600 dark:text-gray-300">
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-amber-500 mt-1"></i>
                                <span>Generative AI-powered adaptive hints</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-amber-500 mt-1"></i>
                                <span>Progressive scaffolding (Basic → Advanced)</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-amber-500 mt-1"></i>
                                <span>Risk-profile-aware intervention strategies</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Problem Statement -->
                <div class="bg-gradient-to-r from-rose-50 to-pink-50 dark:from-rose-900/20 dark:to-pink-900/20 rounded-2xl p-8 border border-rose-200 dark:border-rose-800">
                    <div class="flex flex-col lg:flex-row gap-8 items-center">
                        <div class="flex-shrink-0">
                            <div class="w-20 h-20 bg-gradient-to-br from-rose-500 to-pink-500 rounded-2xl flex items-center justify-center">
                                <i class="fas fa-exclamation-triangle text-3xl text-white"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">The Problem We're Solving</h3>
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                Modern LMS platforms collect vast amounts of data but fail to <strong>close the loop</strong> between identifying a struggling student and helping them effectively. 
                                X-Scaffold bridges this <strong>"Prediction-Intervention Gap"</strong> by synthesizing ML predictions, XAI explanations, and LLM-generated scaffolding into a unified, actionable framework.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Architecture & Diagrams Section -->
        <section id="architecture" class="min-h-screen py-20 px-4 sm:px-6 lg:px-8 bg-white dark:bg-gray-800">
            <div class="max-w-7xl mx-auto w-full">
                <div class="text-center mb-16">
                    <span class="inline-block px-4 py-1 bg-violet-100 dark:bg-violet-900/50 text-violet-700 dark:text-violet-300 rounded-full text-sm font-semibold mb-4">
                        System Design
                    </span>
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                        Architecture & Design
                    </h2>
                    <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                        Explore the technical blueprints and design artifacts powering X-Scaffold.
                    </p>
                </div>

                <!-- Developer's Canvas Button -->
                <div class="text-center mb-12">
                    <button id="openCanvasBtn"
                        class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-violet-600 via-purple-600 to-indigo-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-2xl hover:scale-105 transition-all duration-300">
                        <i class="fas fa-images text-xl"></i>
                        <span>Open Developer's Canvas</span>
                        <span class="px-2 py-1 bg-white/20 rounded-lg text-sm">6 Diagrams</span>
                    </button>
                </div>

                <!-- Image Grid Preview -->
                <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                    @php
                        $diagrams = [
                            ['path' => '/images/FYP IMAGES/High_Level_Architecture_Diagram.png', 'title' => 'High-Level Architecture', 'desc' => 'System components & data flow'],
                            ['path' => '/images/FYP IMAGES/System_workflow_diagram.png', 'title' => 'System Workflow', 'desc' => 'End-to-end process flow'],
                            ['path' => '/images/FYP IMAGES/Use Case Diagram.png', 'title' => 'Use Case Diagram', 'desc' => 'Actor interactions'],
                            ['path' => '/images/FYP IMAGES/Context Diagram.png', 'title' => 'Context Diagram', 'desc' => 'System boundaries'],
                            ['path' => '/images/FYP IMAGES/Rich Picture Diagram.png', 'title' => 'Rich Picture', 'desc' => 'Stakeholder ecosystem'],
                            ['path' => '/images/FYP IMAGES/onion_test.drawio.png', 'title' => 'Onion Architecture', 'desc' => 'Layered design pattern'],
                        ];
                    @endphp

                    @foreach ($diagrams as $index => $diagram)
                        <div class="group relative bg-gray-100 dark:bg-gray-700 rounded-xl overflow-hidden cursor-pointer hover:shadow-xl transition-all duration-300"
                             data-diagram-index="{{ $index }}">
                            <div class="aspect-video overflow-hidden bg-gray-200 dark:bg-gray-600">
                                <img src="{{ $diagram['path'] }}" alt="{{ $diagram['title'] }}"
                                    loading="lazy"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div class="absolute bottom-0 left-0 right-0 p-4">
                                    <h4 class="text-white font-semibold">{{ $diagram['title'] }}</h4>
                                    <p class="text-gray-300 text-sm">{{ $diagram['desc'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Canvas Modal -->
                <div id="canvasModal"
                    class="fixed inset-0 bg-black/90 backdrop-blur-md hidden z-50 flex flex-col items-center justify-center p-4">
                    <div class="w-full max-w-6xl">
                        <div class="flex justify-between items-center mb-4">
                            <h3 id="canvasTitle" class="text-xl font-semibold text-white">Developer's Canvas</h3>
                            <button id="closeCanvasBtn"
                                class="text-white text-3xl font-bold hover:text-red-500 transition">&times;</button>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-2 shadow-2xl">
                            <img id="canvasImage" src="" alt="Diagram" class="w-full h-auto max-h-[70vh] object-contain rounded-lg">
                        </div>
                        <div class="mt-6 flex justify-center items-center gap-4">
                            <button id="prevImageBtn"
                                class="px-6 py-3 bg-white/20 text-white rounded-xl hover:bg-white/30 transition flex items-center gap-2">
                                <i class="fas fa-chevron-left"></i> Previous
                            </button>
                            <div class="flex gap-2" id="modalDots"></div>
                            <button id="nextImageBtn"
                                class="px-6 py-3 bg-white/20 text-white rounded-xl hover:bg-white/30 transition flex items-center gap-2">
                                Next <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features & How It Works -->
        <section id="features" class="min-h-screen py-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-gray-50 via-white to-gray-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
            <div class="max-w-7xl mx-auto w-full">
                <div class="text-center mb-16">
                    <span class="inline-block px-4 py-1 bg-teal-100 dark:bg-teal-900/50 text-teal-700 dark:text-teal-300 rounded-full text-sm font-semibold mb-4">
                        Capabilities
                    </span>
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                        How X-Scaffold Works
                    </h2>
                    <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                        From question upload to adaptive intervention — experience the complete learning enhancement pipeline.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @php
                        $features = [
                            [
                                'icon' => 'fa-file-excel',
                                'title' => 'Excel Upload',
                                'desc' => 'Teachers upload questions directly via Excel — supporting MCQs, True/False, and Short Answer formats with difficulty tagging.',
                                'btn' => 'Upload Demo',
                                'href' => route('teacher.questions.upload'),
                                'gradient' => 'from-red-500 to-rose-600',
                                'note' => 'Teacher Login Required',
                            ],
                            [
                                'icon' => 'fa-robot',
                                'title' => 'Adaptive Scaffolding',
                                'desc' => 'AI analyzes student behavior in real-time and generates personalized hints based on their specific struggle patterns.',
                                'btn' => 'Try Mock Exam',
                                'href' => route('test-exam.index'),
                                'gradient' => 'from-amber-500 to-orange-600',
                                'note' => null,
                            ],
                            [
                                'icon' => 'fa-chart-line',
                                'title' => 'Risk Prediction',
                                'desc' => 'ML models analyze 11 behavioral features to compute a Learning Mastery Score and classify students into risk levels.',
                                'btn' => 'View Predictor',
                                'href' => route('risk.predictor'),
                                'gradient' => 'from-blue-500 to-cyan-600',
                                'note' => null,
                            ],
                        ];
                    @endphp

                    @foreach ($features as $feature)
                        <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden">
                            <div class="h-2 bg-gradient-to-r {{ $feature['gradient'] }}"></div>
                            <div class="p-8">
                                <div class="w-14 h-14 bg-gradient-to-br {{ $feature['gradient'] }} rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas {{ $feature['icon'] }} text-xl text-white"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">{{ $feature['title'] }}</h3>
                                <p class="text-gray-600 dark:text-gray-300 text-sm mb-6 leading-relaxed">{{ $feature['desc'] }}</p>
                                
                                @if ($feature['note'])
                                    <div class="mb-4">
                                        <span class="inline-block text-xs font-semibold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/30 px-3 py-1 rounded-full">
                                            {{ $feature['note'] }}
                                        </span>
                                    </div>
                                @endif
                                
                                <a href="{{ $feature['href'] }}"
                                    class="inline-flex items-center gap-2 text-sm font-semibold bg-gradient-to-r {{ $feature['gradient'] }} bg-clip-text text-transparent hover:underline">
                                    {{ $feature['btn'] }} <i class="fas fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Tech Stack -->
                <div class="mt-16 bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 text-center">Technology Stack</h3>
                    <div class="flex flex-wrap justify-center gap-4">
                        @php
                            $techStack = [
                                ['name' => 'Laravel 11', 'icon' => 'fab fa-laravel', 'color' => 'text-red-500'],
                                ['name' => 'PHP 8.3', 'icon' => 'fab fa-php', 'color' => 'text-indigo-500'],
                                ['name' => 'JavaScript', 'icon' => 'fab fa-js', 'color' => 'text-yellow-500'],
                                ['name' => 'TailwindCSS', 'icon' => 'fab fa-css3', 'color' => 'text-cyan-500'],
                                ['name' => 'Python', 'icon' => 'fab fa-python', 'color' => 'text-blue-500'],
                                ['name' => 'Scikit-learn', 'icon' => 'fas fa-brain', 'color' => 'text-orange-500'],
                                ['name' => 'Gemini AI', 'icon' => 'fas fa-magic', 'color' => 'text-purple-500'],
                                ['name' => 'MySQL', 'icon' => 'fas fa-database', 'color' => 'text-blue-600'],
                            ];
                        @endphp
                        @foreach ($techStack as $tech)
                            <div class="flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-full">
                                <i class="{{ $tech['icon'] }} {{ $tech['color'] }}"></i>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $tech['name'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section id="cta" class="min-h-screen py-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-indigo-900 via-purple-900 to-violet-900 flex items-center">
            <div class="max-w-7xl mx-auto w-full">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <!-- Testimonials -->
                    <div class="space-y-8">
                        <h2 class="text-3xl font-bold text-white">
                            Designed for Educators & Learners
                        </h2>
                        
                        <blockquote class="bg-white/10 backdrop-blur-sm border-l-4 border-cyan-400 rounded-r-xl pl-6 pr-6 py-6">
                            <p class="text-lg text-white/90 leading-relaxed italic">
                                "X-Scaffold's <strong>risk prediction</strong> identified struggling students weeks before the exam. The explainable AI showed me exactly why—low confidence paired with high hint usage."
                            </p>
                            <footer class="mt-4 text-cyan-300 text-sm font-medium">— Dr. Aruna, Senior Lecturer</footer>
                        </blockquote>

                        <blockquote class="bg-white/10 backdrop-blur-sm border-l-4 border-amber-400 rounded-r-xl pl-6 pr-6 py-6">
                            <p class="text-lg text-white/90 leading-relaxed italic">
                                "The <strong>adaptive scaffolding</strong> doesn't just give answers—it generates hints based on my exact mistakes. Like having a personalized tutor."
                            </p>
                            <footer class="mt-4 text-amber-300 text-sm font-medium">— Kasun D., 3rd Year CS Student</footer>
                        </blockquote>
                    </div>

                    <!-- CTA -->
                    <div class="text-center lg:text-left space-y-8">
                        <div>
                            <h2 class="text-4xl md:text-5xl font-bold text-white mb-4">
                                Ready to Close the Learning Loop?
                            </h2>
                            <p class="text-xl text-white/70">
                                Predict Risk. Explain Causes. Act with AI.<br>
                                Experience the next generation of LMS intervention.
                            </p>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                            @auth
                                @if(auth()->user()->role === 'student')
                                    <a href="{{ route('student.dashboard') }}"
                                        class="px-8 py-4 bg-gradient-to-r from-cyan-500 to-blue-500 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300 text-center">
                                        <i class="fas fa-graduation-cap mr-2"></i> Go to Student Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('dashboard') }}"
                                        class="px-8 py-4 bg-gradient-to-r from-rose-500 to-red-500 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300 text-center">
                                        <i class="fas fa-chalkboard-teacher mr-2"></i> Go to Instructor Dashboard
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('login') }}"
                                    class="px-8 py-4 bg-white text-indigo-900 font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300 text-center">
                                    <i class="fas fa-sign-in-alt mr-2"></i> Log In to Get Started
                                </a>
                                <a href="{{ route('register') }}"
                                    class="px-8 py-4 border-2 border-white text-white font-semibold rounded-xl hover:bg-white/10 transition-all duration-300 text-center">
                                    Create Account
                                </a>
                            @endauth
                        </div>

                        <!-- Research Badge -->
                        <div class="pt-8 border-t border-white/20">
                            <p class="text-white/50 text-sm">
                                <i class="fas fa-flask mr-2"></i>
                                Final Year Research Project • Computing Mathematics Education
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Canvas Modal JavaScript -->
    <script>
        (() => {
            const modal = document.getElementById('canvasModal');
            const openBtn = document.getElementById('openCanvasBtn');
            const closeBtn = document.getElementById('closeCanvasBtn');
            const canvasImage = document.getElementById('canvasImage');
            const canvasTitle = document.getElementById('canvasTitle');
            const prevBtn = document.getElementById('prevImageBtn');
            const nextBtn = document.getElementById('nextImageBtn');
            const dotsContainer = document.getElementById('modalDots');

            const diagrams = [
                { path: '/images/FYP IMAGES/High_Level_Architecture_Diagram.png', title: 'High-Level Architecture' },
                { path: '/images/FYP IMAGES/System_workflow_diagram.png', title: 'System Workflow' },
                { path: '/images/FYP IMAGES/Use Case Diagram.png', title: 'Use Case Diagram' },
                { path: '/images/FYP IMAGES/Context Diagram.png', title: 'Context Diagram' },
                { path: '/images/FYP IMAGES/Rich Picture Diagram.png', title: 'Rich Picture' },
                { path: '/images/FYP IMAGES/onion_test.drawio.png', title: 'Onion Architecture' },
            ];

            let currentIndex = 0;

            function updateDots() {
                dotsContainer.innerHTML = diagrams.map((_, i) => 
                    `<button class="w-3 h-3 rounded-full transition-all ${i === currentIndex ? 'bg-white scale-125' : 'bg-white/40 hover:bg-white/60'}" data-index="${i}"></button>`
                ).join('');
                
                dotsContainer.querySelectorAll('button').forEach(btn => {
                    btn.addEventListener('click', () => {
                        currentIndex = parseInt(btn.dataset.index);
                        showImage();
                    });
                });
            }

            function showImage() {
                canvasImage.src = diagrams[currentIndex].path;
                canvasTitle.textContent = diagrams[currentIndex].title;
                updateDots();
            }

            function openModal(index = 0) {
                currentIndex = index;
                showImage();
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeModal() {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }

            openBtn.addEventListener('click', () => openModal(0));
            closeBtn.addEventListener('click', closeModal);
            
            prevBtn.addEventListener('click', () => {
                currentIndex = (currentIndex - 1 + diagrams.length) % diagrams.length;
                showImage();
            });
            
            nextBtn.addEventListener('click', () => {
                currentIndex = (currentIndex + 1) % diagrams.length;
                showImage();
            });

            // Click on diagram cards to open modal
            document.querySelectorAll('[data-diagram-index]').forEach(card => {
                card.addEventListener('click', () => {
                    openModal(parseInt(card.dataset.diagramIndex));
                });
            });

            // Close on ESC
            window.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
                if (e.key === 'ArrowLeft' && !modal.classList.contains('hidden')) {
                    prevBtn.click();
                }
                if (e.key === 'ArrowRight' && !modal.classList.contains('hidden')) {
                    nextBtn.click();
                }
            });

            // Close on backdrop click
            modal.addEventListener('click', (e) => {
                if (e.target === modal) closeModal();
            });
        })();
    </script>
</x-app-layout>
