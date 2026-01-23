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

                    @auth
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
                    @else
                        <x-nav-link :href="url('/')" :active="request()->is('/')"
                            class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-yellow-400 font-medium transition">
                            {{ __('Home') }}
                        </x-nav-link>
                    @endauth

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

    <!-- Dynamic Notifications Drawer -->
    <div id="messagesDrawer"
        x-data="notificationsPanel()"
        x-init="init()"
        class="fixed top-0 right-0 h-full w-96 bg-white dark:bg-gray-900 shadow-lg border-l border-gray-200 dark:border-gray-700 z-50 transform translate-x-full transition-transform duration-300"
        style="max-width: 100vw;">
        
        <!-- Header -->
        <div class="flex items-center justify-between px-4 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-2">
                <i class="fa fa-bell text-indigo-500"></i>
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Notifications</h3>
                <span x-show="unreadCount > 0" x-text="unreadCount" class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full"></span>
            </div>
            <div class="flex items-center gap-2">
                <button @click="markAllRead()" x-show="unreadCount > 0"
                    class="text-xs text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">
                    Mark all read
                </button>
                <button @click="document.getElementById('messagesDrawer').classList.add('translate-x-full')"
                    class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                    <i class="fa fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Tabs for Student/Instructor views -->
        @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'teacher'))
        <div class="flex border-b border-gray-200 dark:border-gray-700">
            <button @click="activeTab = 'notifications'" 
                :class="activeTab === 'notifications' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500'"
                class="flex-1 py-3 text-sm font-medium border-b-2 hover:text-indigo-600 transition">
                <i class="fa fa-inbox mr-1"></i> View
            </button>
            <button @click="activeTab = 'send'" 
                :class="activeTab === 'send' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500'"
                class="flex-1 py-3 text-sm font-medium border-b-2 hover:text-indigo-600 transition">
                <i class="fa fa-paper-plane mr-1"></i> Send Warning
            </button>
        </div>
        @endif

        <!-- Notifications List -->
        <div x-show="activeTab === 'notifications'" class="p-4 space-y-3 overflow-y-auto h-[calc(100vh-140px)]">
            <template x-if="loading">
                <div class="flex items-center justify-center py-8">
                    <i class="fa fa-spinner fa-spin text-2xl text-gray-400"></i>
                </div>
            </template>
            
            <template x-if="!loading && notifications.length === 0">
                <div class="text-center py-8">
                    <i class="fa fa-bell-slash text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                    <p class="text-gray-500 dark:text-gray-400">No notifications yet</p>
                </div>
            </template>
            
            <template x-for="n in notifications" :key="n.id">
                <div @click="markAsRead(n.id)" 
                    :class="n.is_read ? 'bg-gray-50 dark:bg-gray-800' : 'bg-indigo-50 dark:bg-indigo-900/30 border-l-4 border-indigo-500'"
                    class="rounded-lg p-3 cursor-pointer hover:shadow-md transition">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-2">
                            <span x-text="n.icon" class="text-lg"></span>
                            <span :class="n.color" class="font-semibold text-sm" x-text="n.title"></span>
                        </div>
                        <span class="text-xs text-gray-400" x-text="n.time"></span>
                    </div>
                    <p class="text-gray-700 dark:text-gray-300 text-sm mt-2 line-clamp-3" x-text="n.message"></p>
                    <div class="text-xs text-gray-400 mt-2">From: <span x-text="n.sender"></span></div>
                </div>
            </template>
        </div>

        <!-- Send Warning Form (Instructors Only) -->
        @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'teacher'))
        <div x-show="activeTab === 'send'" class="p-4 overflow-y-auto h-[calc(100vh-140px)]">
            <div class="space-y-4">
                <!-- Student Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Student</label>
                    <select x-model="sendForm.student_id" @change="updateSelectedStudentEmail()"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white text-sm">
                        <option value="">Choose a student...</option>
                        <template x-for="s in students" :key="s.id">
                            <option :value="s.id" x-text="`${s.name} (${s.student_id})`"></option>
                        </template>
                    </select>
                </div>

                <!-- Warning Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Warning Type</label>
                    <select x-model="sendForm.type"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white text-sm">
                        <option value="warning">⚠️ Performance Warning</option>
                        <option value="at_risk">🚨 At-Risk Alert</option>
                        <option value="info">ℹ️ Information</option>
                        <option value="success">✅ Positive Feedback</option>
                    </select>
                </div>

                <!-- Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title</label>
                    <input type="text" x-model="sendForm.title" placeholder="e.g., Academic Performance Alert"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white text-sm">
                </div>

                <!-- Message -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Message</label>
                    <textarea x-model="sendForm.message" rows="5" placeholder="Enter your message..."
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white text-sm"></textarea>
                </div>

                <!-- Send Email Toggle -->
                <div class="flex items-center gap-2">
                    <input type="checkbox" x-model="sendForm.send_email" id="sendEmailToggle"
                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <label for="sendEmailToggle" class="text-sm text-gray-700 dark:text-gray-300">
                        Also send email to: <span x-text="selectedStudentEmail || 'N/A'" class="font-semibold"></span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button @click="sendWarning()" :disabled="sending"
                    class="w-full py-2.5 bg-amber-500 hover:bg-amber-600 disabled:bg-amber-300 text-white rounded-lg font-medium transition flex items-center justify-center gap-2">
                    <i class="fa" :class="sending ? 'fa-spinner fa-spin' : 'fa-paper-plane'"></i>
                    <span x-text="sending ? 'Sending...' : 'Send Warning'"></span>
                </button>

                <!-- Success/Error Messages -->
                <div x-show="sendSuccess" class="p-3 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-lg text-sm">
                    <i class="fa fa-check-circle mr-1"></i> Warning sent successfully!
                </div>
                <div x-show="sendError" class="p-3 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-lg text-sm">
                    <i class="fa fa-exclamation-circle mr-1"></i> <span x-text="sendError"></span>
                </div>
            </div>
        </div>
        @endif
    </div>

    <script>
        function notificationsPanel() {
            return {
                activeTab: 'notifications',
                notifications: [],
                unreadCount: 0,
                loading: true,
                students: [],
                selectedStudentEmail: '',
                sendForm: {
                    student_id: '',
                    type: 'warning',
                    title: 'Academic Performance Warning',
                    message: '',
                    send_email: true,
                },
                sending: false,
                sendSuccess: false,
                sendError: '',

                async init() {
                    await this.fetchNotifications();
                    await this.fetchStudents();
                    // Poll for new notifications every 30 seconds
                    setInterval(() => this.fetchNotifications(), 30000);
                },

                async fetchNotifications() {
                    try {
                        const res = await fetch('/api/notifications', {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            }
                        });
                        if (!res.ok) {
                            // User not logged in or no permission - just show empty
                            this.notifications = [];
                            this.unreadCount = 0;
                            this.updateBadge();
                            return;
                        }
                        this.notifications = await res.json();
                        this.unreadCount = this.notifications.filter(n => !n.is_read).length;
                        this.updateBadge();
                    } catch (err) {
                        console.error('Failed to fetch notifications:', err);
                    } finally {
                        this.loading = false;
                    }
                },

                async fetchStudents() {
                    try {
                        const res = await fetch('/api/students/dropdown', {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            }
                        });
                        if (!res.ok) return; // Silently fail if not authorized
                        this.students = await res.json();
                    } catch (err) {
                        console.error('Failed to fetch students:', err);
                    }
                },

                updateSelectedStudentEmail() {
                    const student = this.students.find(s => s.id == this.sendForm.student_id);
                    this.selectedStudentEmail = student?.email || 'No email';
                },

                async markAsRead(id) {
                    try {
                        await fetch(`/api/notifications/${id}/read`, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                        });
                        const n = this.notifications.find(n => n.id === id);
                        if (n) n.is_read = true;
                        this.unreadCount = this.notifications.filter(n => !n.is_read).length;
                        this.updateBadge();
                    } catch (err) {
                        console.error(err);
                    }
                },

                async markAllRead() {
                    try {
                        await fetch('/api/notifications/mark-all-read', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                        });
                        this.notifications.forEach(n => n.is_read = true);
                        this.unreadCount = 0;
                        this.updateBadge();
                    } catch (err) {
                        console.error(err);
                    }
                },

                updateBadge() {
                    const badge = document.getElementById('messageCount');
                    if (badge) {
                        badge.textContent = this.unreadCount;
                        badge.style.display = this.unreadCount > 0 ? 'flex' : 'none';
                    }
                },

                async sendWarning() {
                    if (!this.sendForm.student_id || !this.sendForm.title || !this.sendForm.message) {
                        this.sendError = 'Please fill in all fields';
                        return;
                    }

                    this.sending = true;
                    this.sendSuccess = false;
                    this.sendError = '';

                    try {
                        const res = await fetch('/api/notifications/send-warning', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify(this.sendForm)
                        });

                        const data = await res.json();

                        if (data.success) {
                            this.sendSuccess = true;
                            this.sendForm = {
                                student_id: '',
                                type: 'warning',
                                title: 'Academic Performance Warning',
                                message: '',
                                send_email: true,
                            };
                            this.selectedStudentEmail = '';
                            setTimeout(() => this.sendSuccess = false, 3000);
                        } else {
                            this.sendError = data.message || 'Failed to send warning';
                        }
                    } catch (err) {
                        this.sendError = 'Network error. Please try again.';
                    } finally {
                        this.sending = false;
                    }
                }
            };
        }
    </script>

</nav>

