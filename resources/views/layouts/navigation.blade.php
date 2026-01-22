<nav x-data="{ open: false, darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
    class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 shadow-sm sticky top-0 z-50 transition duration-300">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Logo + Nav -->
            <div class="flex items-center">
                <!-- Logo -->
                <a href="/" class="flex items-center space-x-2">
                    <img src="{{ asset('images/X-Scaffold_Logo_2.png') }}" alt="MEEPLE Logo" class="h-10 w-auto">
                </a>

                <!-- Navigation Links -->
                <div class="hidden sm:flex sm:ml-10 space-x-6">

                    {{-- 1. Main Dashboard (Visible to Teachers and Admins) --}}
                    @if (auth()->user()->role === 'admin' || auth()->user()->role === 'teacher')
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                            class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-yellow-400 font-medium transition">
                            {{ __('Instructor Dashboard') }}
                        </x-nav-link>
                    @endif

                    {{-- 2. Student Dashboard (Visible to Students and Admins) --}}
                    @if (auth()->user()->role === 'admin' || auth()->user()->role === 'student')
                        <x-nav-link :href="route('student.dashboard')" :active="request()->routeIs('student.dashboard')"
                            class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-yellow-400 font-medium transition">
                            {{ __('Student Dashboard') }}
                        </x-nav-link>
                    @endif

                </div>
            </div>

            <!-- Right Section -->
            <div class="hidden sm:flex items-center space-x-4">
                <!-- Dark Mode Toggle -->
                <button @click="darkMode = !darkMode; window.location.reload();"
                    class="p-2 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:scale-110 hover:bg-gray-300 dark:hover:bg-gray-600 transition"
                    title="Toggle Dark Mode">
                    <template x-if="!darkMode">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="5" />
                            <path
                                d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42" />
                        </svg>
                    </template>
                    <template x-if="darkMode">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z" />
                        </svg>
                    </template>
                </button>

                <!-- Profile Dropdown -->
                @if (Auth::check())
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <div class="flex items-center gap-3">

                                <button
                                    class="group inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-meepleBlue dark:hover:text-meepleYellow focus:outline-none transition ease-in-out duration-150">

                                    <div class="flex flex-col items-start text-left">
                                        <span class="font-semibold text-gray-800 dark:text-gray-200">
                                            {{ Auth::user()->name }}
                                        </span>

                                        <span
                                            class="text-xs text-gray-500 dark:text-gray-400 font-normal uppercase tracking-wide group-hover:text-meepleBlue dark:group-hover:text-meepleYellow transition-colors">
                                            {{ Auth::user()->role }}
                                        </span>
                                    </div>

                                    <div class="ml-2">
                                        <svg class="h-4 w-4 text-gray-400 group-hover:text-gray-500 transition-transform duration-200 group-hover:rotate-180"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>

                                <button
                                    @click="document.getElementById('messagesDrawer').classList.toggle('translate-x-full')"
                                    class="relative p-2 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-meepleBlue transition"
                                    title="Messages">

                                    <i class="fa fa-envelope text-lg"></i>

                                    <span id="messageCount"
                                        class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white ring-2 ring-white dark:ring-gray-900">
                                        2
                                    </span>
                                </button>
                            </div>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}"
                        class="px-4 py-2 rounded-md bg-meepleBlue text-white font-semibold hover:bg-meepleYellow transition">
                        Login
                    </a>
                @endif
            </div>

            <!-- Mobile Menu Toggle -->
            <div class="sm:hidden flex items-center">
                <button @click="open = !open"
                    class="p-2 rounded-md text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden bg-white dark:bg-gray-900">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-700 px-4">
            @if (Auth::check())
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-base font-medium text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}
                        </div>
                        <div class="text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                    <button @click="darkMode = !darkMode"
                        class="p-2 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 transition"
                        title="Toggle Dark Mode">
                        <template x-if="!darkMode">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 3v1m0 16v1m8.66-12.66l-.7.7M4.04 19.96l-.7-.7m16.24 1.06h-1M6 12H5m12 0h-1M5.64 5.64l-.7-.7M19.96 19.96l-.7-.7M12 7a5 5 0 000 10a5 5 0 000-10z" />
                            </svg>
                        </template>
                        <template x-if="darkMode">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path d="M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z" />
                            </svg>
                        </template>
                    </button>
                </div>
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">{{ __('Profile') }}</x-responsive-nav-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="flex items-center justify-between">
                    <span class="text-base font-medium text-gray-800 dark:text-gray-200">Guest</span>
                    <button @click="darkMode = !darkMode"
                        class="p-2 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 transition"
                        title="Toggle Dark Mode">
                        <template x-if="!darkMode">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 3v1m0 16v1m8.66-12.66l-.7.7M4.04 19.96l-.7-.7m16.24 1.06h-1M6 12H5m12 0h-1M5.64 5.64l-.7-.7M19.96 19.96l-.7-.7M12 7a5 5 0 000 10a5 5 0 000-10z" />
                            </svg>
                        </template>
                        <template x-if="darkMode">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path d="M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z" />
                            </svg>
                        </template>
                    </button>
                </div>
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('login')">{{ __('Login') }}</x-responsive-nav-link>
                </div>
            @endif
        </div>
    </div>

    <!-- Messages Drawer -->
    <div id="messagesDrawer"
        class="fixed top-0 right-0 h-full w-80 bg-white dark:bg-gray-900 shadow-lg border-l border-gray-200 dark:border-gray-700 z-50 transform translate-x-full transition-transform duration-300"
        style="max-width: 100vw;">
        <div class="flex items-center justify-between px-4 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Messages</h3>
            <button @click="document.getElementById('messagesDrawer').classList.add('translate-x-full')"
                class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                <i class="fa fa-times text-xl"></i>
            </button>
        </div>
        <div id="messagesContainer" class="p-4 space-y-4 overflow-y-auto h-[calc(100vh-64px)]">
            <!-- Example messages, replace with dynamic content -->
            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                <div class="font-semibold text-indigo-600 dark:text-indigo-300">Admin</div>
                <div class="text-gray-700 dark:text-gray-300 text-sm mt-1">Welcome to X-Scaffold! Let us know if you need
                    help.
                </div>
                <div class="text-xs text-gray-400 mt-2">2 hours ago</div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                <div class="font-semibold text-indigo-600 dark:text-indigo-300">Support</div>
                <div class="text-gray-700 dark:text-gray-300 text-sm mt-1">Your dashboard has been updated.</div>
                <div class="text-xs text-gray-400 mt-2">1 day ago</div>
            </div>
            <!-- Add more messages here -->
        </div>
    </div>
    {{-- <script>
        const messagesContainer = document.getElementById('messagesContainer');
        const messageCountEl = document.getElementById('messageCount');

        let messages = []; // store messages

        // Function to fetch messages from server
        async function fetchMessages() {
            try {
                const res = await fetch('/api/messages'); // Laravel API endpoint
                const data = await res.json(); // expect an array of messages
                if (JSON.stringify(data) !== JSON.stringify(messages)) {
                    messages = data;
                    renderMessages();
                }
            } catch (err) {
                console.error(err);
            }
        }

        // Render messages in the drawer
        function renderMessages() {
            messagesContainer.innerHTML = messages.map(msg => `
        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
            <div class="font-semibold text-indigo-600 dark:text-indigo-300">${msg.sender}</div>
            <div class="text-gray-700 dark:text-gray-300 text-sm mt-1">${msg.text}</div>
            <div class="text-xs text-gray-400 mt-2">${msg.time}</div>
        </div>
    `).join('');

            messageCountEl.textContent = messages.length;
        }

        // Poll every 5 seconds
        setInterval(fetchMessages, 5000);
        fetchMessages(); // initial fetch
    </script> --}}

    {{-- <script>
        const messagesContainer = document.getElementById('messagesContainer');

        async function loadStudentWarnings() {
            try {
                const res = await fetch('/student/warnings');
                const messages = await res.json();

                if (messages.length === 0) {
                    messagesContainer.innerHTML =
                        `<div class="text-gray-500 dark:text-gray-400 text-sm">No new messages.</div>`;
                    return;
                }

                messagesContainer.innerHTML = messages.map(msg => `
            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                <div class="font-semibold text-indigo-600 dark:text-indigo-300">${msg.sender}</div>
                <div class="text-gray-700 dark:text-gray-300 text-sm mt-1">${msg.text}</div>
                <div class="text-xs text-gray-400 mt-2">${msg.time}</div>
            </div>
        `).join('');
            
                document.getElementById('messageCount').textContent = messages.length;
            } catch (err) {
                console.error(err);
            }
        }

        // Load on page load
        document.addEventListener('DOMContentLoaded', loadStudentWarnings);
        // Poll every 5 seconds
        setInterval(loadStudentWarnings, 5000);
        loadStudentWarnings(); // initial fetch
    </script> --}}

</nav>
