<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 dark:text-white tracking-tight">Welcome to MEEPLE</h2>
    </x-slot>

    <!-- ORB Background Hero Section -->
    <section class="relative h-[600px] w-full overflow-hidden rounded-xl shadow-xl mb-14 mt-8">
        <!-- Orb Canvas container, absolutely positioned -->
        <div id="orb-root" class="absolute inset-0 -z-10"></div>

        <!-- Optional subtle light overlay for light mode, and dark overlay for dark mode -->
        <div class="absolute inset-0 rounded-xl -z-5
            bg-white/40 dark:bg-black/60"></div>

        <!-- Hero content centered -->
        <div class="relative z-10 flex flex-col justify-center items-center h-full px-6 text-center max-w-4xl mx-auto">
            <h1 class="text-6xl font-extrabold text-gray-900 dark:text-white drop-shadow-md leading-tight sm:text-7xl">
                X-Scaffold
            </h1>
            <p class="mt-4 text-xl text-gray-800 dark:text-white/90 max-w-xl">
                Predict Performance. Explain Insights. Scaffold Learning.
            </p>
            <a href="#what-is-meeple"
                class="mt-8 inline-block px-6 py-2 border border-gray-700 dark:border-white text-gray-800 dark:text-white font-medium rounded-md hover:bg-gray-100 dark:hover:bg-white/10 transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 dark:focus:ring-white/20">
                Discover the Predict-Explain-Act Framework
            </a>


        </div>

    </section>

    <div class="space-y-0">
        <!-- Hero Section -->
        <section id="what-is-meeple"
            class="min-h-screen flex flex-col justify-center text-center px-4 sm:px-6 lg:px-8 relative overflow-hidden">
            <!-- Carousel container -->
            <div class="absolute inset-0 -z-10">
                <div class="relative w-full h-full max-w-5xl mx-auto rounded-lg overflow-hidden">
                    @php
                        $images = [
                            '/images/COMPONENTS RANGE.png',
                            '/images/DATABASE DESIGN.png',
                            '/images/FLOW.png',
                            '/images/MEEPLE LEARNING PROCESS.png',
                        ];
                    @endphp

                    <div id="carousel" class="relative w-full h-full">
                        @foreach ($images as $index => $img)
                            <img src="{{ $img }}" alt="MEEPLE Work Image {{ $index + 1 }}"
                                class="absolute inset-0 w-full h-full object-contain opacity-0 transition-opacity duration-1000 ease-in-out"
                                data-carousel-item="{{ $index }}"
                                style="pointer-events:none; user-select:none;" />
                        @endforeach
                    </div>

                    <!-- Navigation dots -->
                    <div id="carousel-dots"
                        class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex space-x-3">
                        @foreach ($images as $index => $img)
                            <button type="button" aria-label="Go to slide {{ $index + 1 }}"
                                data-carousel-dot="{{ $index }}"
                                class="w-3 h-3 rounded-full bg-white bg-opacity-40 hover:bg-opacity-70 transition"
                                style="opacity:0.6"></button>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Text Content -->
            <div class="space-y-6 max-w-5xl mx-auto relative z-10">
                <h1
                    class="text-5xl font-extrabold tracking-tight text-gray-900 dark:text-white sm:text-6xl md:text-7xl">
                    The Predict-Explain-Act Learning Engine
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    Bridging the gap between Machine Learning prediction and pedagogical action with Explainable AI and
                    LLM-driven scaffolding.
                </p>
                <div class="inline-flex space-x-4 justify-center">
                    <a href="#section-what"
                        class="inline-block px-6 py-2 border border-gray-800 dark:border-white text-gray-800 dark:text-white font-medium rounded-md hover:bg-gray-100 dark:hover:bg-white/10 transition duration-200 ease-in-out">
                        What is X-Scaffold?
                    </a>
                    <button id="openCanvasBtn"
                        class="relative inline-flex items-center px-6 py-2 font-medium rounded-md text-white
bg-transparent border border-transparent
bg-gradient-to-r from-teal-600 via-teal-400 to-teal-600
bg-[length:200%_100%] bg-left-bottom
hover:bg-right-bottom
transition-all duration-500 ease-in-out
shadow-sm
dark:text-gray-800
dark:from-gray-50 dark:via-gray-100 dark:to-gray-300
focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <span class="relative z-10">Show Developer's Canvas</span>
                        <span
                            class="absolute inset-0 rounded-md bg-white/10 dark:bg-black/20 blur-sm opacity-0 hover:opacity-100 transition-opacity duration-300"></span>
                    </button>

                </div>
            </div>

            <!-- Canvas Modal Overlay -->
            <div id="canvasModal"
                class="fixed inset-0 bg-black bg-opacity-80 backdrop-blur-sm hidden z-50 flex flex-col items-center justify-center p-4">
                <button id="closeCanvasBtn"
                    class="self-end mb-4 text-white text-3xl font-bold hover:text-red-500 transition">&times;</button>
                <canvas id="imageCanvas" width="800" height="600"
                    class="rounded-lg shadow-lg border border-white"></canvas>
                <div class="mt-4 flex space-x-4">
                    <button id="prevImageBtn"
                        class="px-4 py-2 bg-white bg-opacity-20 text-white rounded-md hover:bg-opacity-40 transition">Previous</button>
                    <button id="nextImageBtn"
                        class="px-4 py-2 bg-white bg-opacity-20 text-white rounded-md hover:bg-opacity-40 transition">Next</button>
                </div>
            </div>

            <script>
                (() => {
                    // Carousel code (unchanged)
                    const images = document.querySelectorAll('[data-carousel-item]');
                    const dots = document.querySelectorAll('[data-carousel-dot]');
                    let current = 0;
                    const slideInterval = 5000;
                    let timer;

                    function showSlide(index) {
                        images.forEach((img, i) => {
                            if (i === index) {
                                img.style.opacity = '0.15';
                                img.style.zIndex = '0';
                            } else {
                                img.style.opacity = '0';
                                img.style.zIndex = '-1';
                            }
                        });
                        dots.forEach((dot, i) => {
                            dot.style.opacity = i === index ? '1' : '0.4';
                            dot.classList.toggle('bg-opacity-100', i === index);
                        });
                        current = index;
                    }

                    function nextSlide() {
                        let next = (current + 1) % images.length;
                        showSlide(next);
                    }
                    dots.forEach((dot, i) => {
                        dot.addEventListener('click', () => {
                            clearInterval(timer);
                            showSlide(i);
                            timer = setInterval(nextSlide, slideInterval);
                        });
                    });
                    showSlide(0);
                    timer = setInterval(nextSlide, slideInterval);

                    // Canvas Modal Logic
                    const modal = document.getElementById('canvasModal');
                    const openBtn = document.getElementById('openCanvasBtn');
                    const closeBtn = document.getElementById('closeCanvasBtn');
                    const canvas = document.getElementById('imageCanvas');
                    const ctx = canvas.getContext('2d');
                    const prevBtn = document.getElementById('prevImageBtn');
                    const nextBtn = document.getElementById('nextImageBtn');

                    const imagePaths = [
                        '/images/COMPONENTS RANGE.png',
                        '/images/DATABASE DESIGN.png',
                        '/images/FLOW.png',
                        '/images/MEEPLE LEARNING PROCESS.png',
                    ];

                    let loadedImages = [];
                    let currentImageIndex = 0;

                    // Preload images
                    function preloadImages(paths) {
                        return Promise.all(
                            paths.map(src => new Promise((resolve) => {
                                const img = new Image();
                                img.src = src;
                                img.onload = () => resolve(img);
                            }))
                        );
                    }

                    function drawImageScaled(img) {
                        // Clear canvas
                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                        // Calculate scale to fit inside canvas (keeping aspect ratio)
                        let hRatio = canvas.width / img.width;
                        let vRatio = canvas.height / img.height;
                        let ratio = Math.min(hRatio, vRatio);
                        let centerX = (canvas.width - img.width * ratio) / 2;
                        let centerY = (canvas.height - img.height * ratio) / 2;
                        ctx.drawImage(img, 0, 0, img.width, img.height,
                            centerX, centerY, img.width * ratio, img.height * ratio);
                    }

                    function showCurrentImage() {
                        const img = loadedImages[currentImageIndex];
                        drawImageScaled(img);
                    }

                    function nextImage() {
                        currentImageIndex = (currentImageIndex + 1) % loadedImages.length;
                        showCurrentImage();
                    }

                    function prevImage() {
                        currentImageIndex = (currentImageIndex - 1 + loadedImages.length) % loadedImages.length;
                        showCurrentImage();
                    }

                    openBtn.addEventListener('click', () => {
                        modal.classList.remove('hidden');
                        // Load images if not already loaded
                        if (loadedImages.length === 0) {
                            preloadImages(imagePaths).then(images => {
                                loadedImages = images;
                                currentImageIndex = 0;
                                showCurrentImage();
                            });
                        } else {
                            showCurrentImage();
                        }
                    });

                    closeBtn.addEventListener('click', () => {
                        modal.classList.add('hidden');
                    });

                    nextBtn.addEventListener('click', nextImage);
                    prevBtn.addEventListener('click', prevImage);

                    // Optional: Close modal on ESC key
                    window.addEventListener('keydown', (e) => {
                        if (e.key === "Escape" && !modal.classList.contains('hidden')) {
                            modal.classList.add('hidden');
                        }
                    });
                })();
            </script>
        </section>



        <!-- What is MEEPLE -->
        <section id="section-what" class="bg-gray-50 dark:bg-gray-900 py-20 min-h-screen flex items-center">
            <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 w-full flex flex-col gap-12">
                <!-- What is MEEPLE (Full Width Row) -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-8 mb-2">
                    <h2 class="text-3xl font-extrabold text-gray-800 dark:text-white mb-4">
                        What is <span class="text-yellow-600 dark:text-yellow-300">X-Scaffold</span>?
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300 leading-relaxed">
                        X-Scaffold is an integrated "Predict-Explain-Act" framework that bridges the gap between
                        analytics and intervention.
                        It moves beyond static grading by combining Machine Learning for real-time performance
                        prediction,
                        Explainable AI (XAI) for transparent teacher insights, and LLM-driven adaptive scaffolding to
                        support students during assessments.
                    </p>
                    <a href="#how-it-works"
                        class="mt-6 inline-block px-6 py-2 border border-yellow-500 text-yellow-700 dark:text-yellow-300 dark:border-yellow-300 font-medium rounded-md hover:bg-yellow-50 dark:hover:bg-yellow-300/10 transition">
                        How X-Scaffold Works
                    </a>
                </div>

                <!-- Carousel Row (Meaning, Features, Video) -->
                <div class="flex flex-col items-center justify-center w-full">
                    <div x-data="{
                        slides: [{
                                type: 'meaning',
                                html: `<div class='bg-white dark:bg-gray-800 rounded-xl shadow-md p-8 border-l-4 border-yellow-400 group min-h-[340px] flex flex-col justify-center'>
                                    <h3 class='text-xl font-semibold text-gray-800 dark:text-white mb-6 flex items-center gap-2'>
                                        <span>🔄</span> The X-Scaffold Framework
                                    </h3>
                                    <ul class='space-y-4 text-sm text-gray-700 dark:text-gray-200'>
                                        <li class='flex items-start gap-3 transition-all duration-200 hover:pl-2 hover:text-yellow-600 dark:hover:text-yellow-300'>
                                            <span class='text-yellow-500 dark:text-yellow-300 font-bold text-base'>P</span>
                                            <span><strong>Predict (ML):</strong> Real-time Random Forest models analyze behavior (time/hints) to predict student risk scores.</span>
                                        </li>
                                        <li class='flex items-start gap-3 transition-all duration-200 hover:pl-2 hover:text-yellow-600 dark:hover:text-yellow-300'>
                                            <span class='text-yellow-500 dark:text-yellow-300 font-bold text-base'>E</span>
                                            <span><strong>Explain (XAI):</strong> SHAP/LIME values generate 'reason codes' explaining <em>why</em> a student is at risk.</span>
                                        </li>
                                        <li class='flex items-start gap-3 transition-all duration-200 hover:pl-2 hover:text-yellow-600 dark:hover:text-yellow-300'>
                                            <span class='text-yellow-500 dark:text-yellow-300 font-bold text-base'>A</span>
                                            <span><strong>Act (LLM):</strong> AI uses the specific risk profile to generate adaptive scaffolding hints (Basic to Advanced).</span>
                                        </li>
                                    </ul>
                                </div>`
                            },
                            {
                                type: 'features',
                                html: `<div class='bg-white dark:bg-gray-800 rounded-xl shadow-md p-8 min-h-[340px] flex flex-col justify-center'>
                                    <h3 class='text-xl font-semibold text-gray-800 dark:text-white mb-4'>🎯 Core Features of X-Scaffold</h3>
                                    <ul class='list-disc pl-5 space-y-2 text-sm text-gray-700 dark:text-gray-300'>
                                        <li class='transition-all duration-200 hover:pl-2 hover:text-yellow-600 dark:hover:text-yellow-300'><strong>Fine-Grained Tracking:</strong> Monitors <code>time_per_question</code>, hint clicks, and accuracy in real-time.</li>
                                        <li class='transition-all duration-200 hover:pl-2 hover:text-yellow-600 dark:hover:text-yellow-300'><strong>Closed-Loop AI:</strong> Bridges the gap between static analytics and active student intervention.</li>
                                        <li class='transition-all duration-200 hover:pl-2 hover:text-yellow-600 dark:hover:text-yellow-300'><strong>Generative Scaffolding:</strong> Hints aren't static; they are generated by an LLM based on the XAI risk explanation.</li>
                                        <li class='transition-all duration-200 hover:pl-2 hover:text-yellow-600 dark:hover:text-yellow-300'><strong>Tech Stack:</strong> Powered by Laravel 11, React 18, and Python 3.10 (Scikit-learn/TensorFlow).</li>
                                        <li class='transition-all duration-200 hover:pl-2 hover:text-yellow-600 dark:hover:text-yellow-300'><strong>Example:</strong> High Hint Usage + Low Time = 'Gaming the system' risk → System restricts answer visibility.</li>
                                    </ul>
                                </div>`
                            },
                            {
                                type: 'video',
                                html: `<div class='w-full max-w-full rounded-xl overflow-hidden shadow-xl min-h-[340px] flex flex-col justify-center'>
                                    <iframe class='w-full h-64 md:h-80 rounded-xl' src='https://www.youtube.com/embed/dQw4w9WgXcQ' title='Intro to X-Scaffold' allowfullscreen></iframe>
                                </div>`
                            }
                        ],
                        current: 0,
                        next() {
                            this.current = (this.current + 1) % this.slides.length
                        },
                        prev() {
                            this.current = (this.current - 1 + this.slides.length) % this.slides.length
                        }
                    }" class="w-full">
                        <div class="relative w-full min-h-[340px]">
                            <template x-for="(slide, idx) in slides" :key="slide.type">
                                <div x-show="current === idx" x-html="slide.html"
                                    class="transition-all duration-500 absolute inset-0 z-0"></div>
                            </template>
                            <div class="flex justify-center mt-4 space-x-4 absolute left-0 right-0 bottom-0 z-10">
                                <button type="button" @click="prev"
                                    class="px-3 py-1 rounded-full bg-gray-200 dark:bg-gray-700 hover:bg-yellow-400 dark:hover:bg-yellow-500 transition">
                                    <i class="fa fa-chevron-left"></i>
                                </button>
                                <template x-for="(slide, idx) in slides" :key="slide.type + '-dot'">
                                    <span @click="current = idx"
                                        :class="{
                                            'bg-yellow-500 dark:bg-yellow-400': current ===
                                                idx,
                                            'bg-gray-300 dark:bg-gray-600': current !== idx
                                        }"
                                        class="inline-block w-3 h-3 rounded-full mx-1 cursor-pointer transition"></span>
                                </template>
                                <button type="button" @click="next"
                                    class="px-3 py-1 rounded-full bg-gray-200 dark:bg-gray-700 hover:bg-yellow-400 dark:hover:bg-yellow-500 transition">
                                    <i class="fa fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>



        <!-- Interactive How It Works -->
        <section id="how-it-works"
            class="min-h-screen bg-gradient-to-tr from-gray-100 to-gray-50 dark:from-gray-800 dark:to-gray-700 flex items-center">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full space-y-12">
                <h2 class="text-3xl font-bold text-gray-800 dark:text-white text-center">How X-Scaffold Works</h2>
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
                                'note' => 'Teacher Login Required',
                            ],
                            [
                                'title' => 'ML Recommendations',
                                'desc' =>
                                    'MEEPLE learns from student performance and recommends tailored question sets with smart difficulty scaling.',
                                'btn' => 'Simulate ML Assist',
                                'href' => route('test-exam.index'),
                                'bg' => 'border-yellow-500 text-yellow-700 dark:text-yellow-300 dark:border-yellow-400',
                            ],
                            [
                                'title' => 'Performance Engine',
                                'desc' =>
                                    'Find out Student performance, prediction score determines if they are at risk of failing or not.',
                                'btn' => 'View Sample Stats',
                                'href' => route('risk.predictor'),
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
                            @if (isset($card['note']))
                                <div class="mb-4">
                                    <span
                                        class="block text-xs font-semibold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900 px-2 py-1 rounded">
                                        {{ $card['note'] }}
                                    </span>
                                </div>
                            @endif
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

        <div class="space-y-6">
            <h2 class="text-3xl font-bold text-gray-800 dark:text-white text-center md:text-left">
                Trusted by Data-Driven Educators
            </h2>
            <div class="space-y-6">
                <blockquote class="bg-white dark:bg-gray-800 border-l-4 border-indigo-600 pl-6 pr-4 py-4 rounded-md shadow-sm">
                    <p class="text-lg text-gray-700 dark:text-gray-100 leading-relaxed italic">
                        "X-Scaffold’s <strong>Risk Prediction</strong> saved my semester. The system flagged struggling students weeks before the exam, and the 'Explainable AI' showed me exactly why—low confidence and high hint usage."
                    </p>
                    <footer class="mt-3 text-sm text-gray-500">– Dr. Aruna, Senior Lecturer</footer>
                </blockquote>

                <blockquote class="bg-white dark:bg-gray-800 border-l-4 border-yellow-500 pl-6 pr-4 py-4 rounded-md shadow-sm">
                    <p class="text-lg text-gray-700 dark:text-gray-100 leading-relaxed italic">
                        "The <strong>AI Scaffolding</strong> is brilliant. It doesn't just give me the answer; it gives me a hint based on my exact mistake. It feels like a personalized tutor is sitting right next to me."
                    </p>
                    <footer class="mt-3 text-sm text-gray-500">– Kasun D., 3rd Year CS Student</footer>
                </blockquote>
            </div>
        </div>

        <div class="text-center space-y-6">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Ready to Close the Learning Loop?</h2>
            <p class="text-gray-600 dark:text-gray-300 max-w-md mx-auto">
                Predict Risk. Explain Causes. Act with AI.<br>
                Experience the next generation of LMS intervention.
            </p>

            @auth
                @if(auth()->user()->role === 'student')
                    {{-- Student Link --}}
                    <a href="{{ route('student.dashboard') }}"
                        class="inline-block px-6 py-3 border border-indigo-600 text-indigo-600 dark:text-indigo-400 dark:border-indigo-400 font-medium rounded-md hover:bg-indigo-50 dark:hover:bg-indigo-400/10 transition">
                        Go to Student Dashboard
                    </a>
                @else
                    {{-- Teacher & Admin Link --}}
                    <a href="{{ route('dashboard') }}"
                        class="inline-block px-6 py-3 border border-red-600 text-red-600 dark:text-red-400 dark:border-red-400 font-medium rounded-md hover:bg-red-50 dark:hover:bg-red-400/10 transition">
                        Go to Instructor Dashboard
                    </a>
                @endif
            @else
                {{-- Guest Link --}}
                <a href="{{ route('login') }}"
                    class="inline-block px-6 py-3 border border-gray-600 text-gray-600 dark:text-gray-400 dark:border-gray-400 font-medium rounded-md hover:bg-gray-50 dark:hover:bg-gray-400/10 transition">
                    Log In to Get Started
                </a>
            @endauth
        </div>
    </div>
</section>

    </div>

</x-app-layout>
