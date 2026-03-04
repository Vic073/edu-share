<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'EduShare — Academic Materials Platform')</title>
    <meta name="description" content="EduShare — Malawi's national platform for sharing academic materials across universities.">

    <!-- FOUC prevention: apply theme before render -->
    <script>
        (function(){
            var t = localStorage.getItem('edushare-theme') ||
                (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            if (t === 'dark') document.documentElement.classList.add('dark');
        })();
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- App Styles (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @yield('styles')
</head>
<body class="min-h-screen bg-dark-50 dark:bg-dark-900 transition-colors duration-300">

    <!-- Navbar -->
    <nav class="navbar" id="main-navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                <!-- Logo + Desktop Nav -->
                <div class="flex items-center gap-6">
                    <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="flex items-center gap-1.5">
                        <span class="text-2xl font-extrabold text-primary-600 dark:text-primary-400">Edu</span>
                        <span class="text-2xl font-bold text-dark-900 dark:text-white">Share</span>
                    </a>

                    @auth
                    <div class="hidden md:flex items-center gap-1">
                        <a href="{{ route('dashboard') }}"
                           class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-home"></i> Home
                        </a>
                        <a href="{{ route('materials') }}"
                           class="nav-item {{ request()->routeIs('materials', 'materials.view', 'materials.show') ? 'active' : '' }}">
                            <i class="fas fa-book"></i> Materials
                        </a>
                        <a href="{{ route('materials.create') }}"
                           class="nav-item {{ request()->routeIs('materials.create') ? 'active' : '' }}">
                            <i class="fas fa-cloud-upload-alt"></i> Upload
                        </a>
                        <a href="{{ route('favorites.index') }}"
                           class="nav-item relative {{ request()->routeIs('favorites.index') ? 'active' : '' }}">
                            <i class="fas fa-heart"></i> Saved
                            @if(isset($favoritesCount) && $favoritesCount > 0)
                                <span class="absolute -top-0.5 -right-0.5 w-4 h-4 flex items-center justify-center text-[9px] font-bold text-white bg-red-500 rounded-full">
                                    {{ $favoritesCount > 9 ? '9+' : $favoritesCount }}
                                </span>
                            @endif
                        </a>
                    </div>
                    @endauth
                </div>

                <!-- Right Side -->
                <div class="flex items-center gap-2">

                    <!-- Search (desktop) -->
                    @auth
                    <form method="GET" action="{{ route('materials') }}" class="hidden lg:block">
                        <div class="relative">
                            <input type="text"
                                   name="title"
                                   placeholder="Search materials…"
                                   value="{{ request('title') }}"
                                   class="w-56 pl-9 pr-4 py-2 rounded-full bg-dark-100 dark:bg-dark-800 border border-dark-200 dark:border-dark-700 text-sm text-dark-900 dark:text-dark-100 placeholder:text-dark-400 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-all">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-dark-400 text-xs"></i>
                        </div>
                    </form>
                    @endauth

                    <!-- Premium badge -->
                    @auth
                        @if(Auth::user()->subscription_tier !== 'premium' && !Auth::user()->isAdmin())
                            <a href="{{ route('pricing') }}"
                               class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-gradient-to-r from-amber-500 to-orange-500 text-white text-xs font-semibold hover:from-amber-600 hover:to-orange-600 transition-all shadow-sm">
                                <i class="fas fa-crown text-[10px]"></i> Premium
                            </a>
                        @endif
                    @endauth

                    <!-- Theme toggle -->
                    <button id="theme-toggle" class="theme-toggle" aria-label="Toggle dark/light mode">
                        <span class="theme-toggle-thumb">
                            <i class="fas fa-sun text-[10px] text-amber-500"></i>
                        </span>
                    </button>

                    <!-- Notification bell -->
                    @auth
                    <a href="{{ route('notifications') }}"
                       class="relative p-2 rounded-full hover:bg-dark-100 dark:hover:bg-dark-700 transition-colors"
                       aria-label="Notifications">
                        <i class="fas fa-bell text-dark-500 dark:text-dark-400"></i>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        @endif
                    </a>

                    <!-- User Menu -->
                    <div class="relative">
                        <button id="user-menu-btn"
                                class="flex items-center gap-2 p-1 rounded-full hover:bg-dark-100 dark:hover:bg-dark-700 transition-colors">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-400 to-primary-700 flex items-center justify-center text-white font-bold text-sm select-none">
                                {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->last_name, 0, 1)) }}
                            </div>
                            <i class="fas fa-chevron-down text-[10px] text-dark-400 hidden sm:block"></i>
                        </button>

                        <!-- Dropdown -->
                        <div id="user-dropdown"
                             class="hidden absolute right-0 mt-2 w-56 bg-white dark:bg-dark-800 rounded-xl shadow-xl border border-dark-200 dark:border-dark-700 py-1.5 z-50">
                            <!-- User info -->
                            <div class="px-4 py-3 border-b border-dark-100 dark:border-dark-700">
                                <p class="text-sm font-semibold text-dark-900 dark:text-white truncate">
                                    {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                                </p>
                                <p class="text-xs text-dark-400 dark:text-dark-500 truncate">{{ Auth::user()->email }}</p>
                                <div class="mt-1.5 flex items-center gap-1.5">
                                    <span class="badge {{ Auth::user()->subscription_tier === 'premium' ? 'badge-gold' : 'bg-dark-100 dark:bg-dark-700 text-dark-600 dark:text-dark-300' }} text-[10px]">
                                        {{ ucfirst(Auth::user()->subscription_tier ?? 'free') }}
                                    </span>
                                    <span class="badge bg-dark-100 dark:bg-dark-700 text-dark-600 dark:text-dark-300 text-[10px] capitalize">
                                        {{ Auth::user()->role }}
                                    </span>
                                </div>
                            </div>

                            <!-- Menu items -->
                            @if(Auth::user()->kyc_status !== 'verified')
                                <a href="{{ route('kyc.submit') }}"
                                   class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-amber-600 dark:text-amber-400 hover:bg-dark-50 dark:hover:bg-dark-700 transition-colors">
                                    <i class="fas fa-exclamation-triangle w-4 text-center"></i> Complete KYC
                                </a>
                            @endif

                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}"
                                   class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-dark-700 dark:text-dark-200 hover:bg-dark-50 dark:hover:bg-dark-700 transition-colors">
                                    <i class="fas fa-shield-alt w-4 text-center text-primary-500"></i> Admin Panel
                                </a>
                                <div class="border-t border-dark-100 dark:border-dark-700 my-1"></div>
                            @endif

                            <a href="{{ route('profile') }}"
                               class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-dark-700 dark:text-dark-200 hover:bg-dark-50 dark:hover:bg-dark-700 transition-colors">
                                <i class="fas fa-user w-4 text-center"></i> Profile
                            </a>
                            <a href="{{ route('my-materials') }}"
                               class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-dark-700 dark:text-dark-200 hover:bg-dark-50 dark:hover:bg-dark-700 transition-colors">
                                <i class="fas fa-folder w-4 text-center"></i> My Materials
                            </a>

                            <div class="border-t border-dark-100 dark:border-dark-700 my-1"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="flex items-center gap-2.5 w-full px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-dark-50 dark:hover:bg-dark-700 transition-colors">
                                    <i class="fas fa-sign-out-alt w-4 text-center"></i> Sign Out
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Mobile menu button -->
                    <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg hover:bg-dark-100 dark:hover:bg-dark-700 transition-colors" aria-label="Toggle mobile menu">
                        <i class="fas fa-bars text-dark-600 dark:text-dark-300"></i>
                    </button>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        @auth
        <div id="mobile-menu" class="hidden md:hidden border-t border-dark-200 dark:border-dark-700 bg-white dark:bg-dark-900">
            <div class="px-4 py-3 space-y-1">
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-dark-700 dark:text-dark-200 hover:bg-dark-100 dark:hover:bg-dark-700 transition-colors {{ request()->routeIs('dashboard') ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-400' : '' }}">
                    <i class="fas fa-home w-5 text-center"></i> Home
                </a>
                <a href="{{ route('materials') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-dark-700 dark:text-dark-200 hover:bg-dark-100 dark:hover:bg-dark-700 transition-colors">
                    <i class="fas fa-book w-5 text-center"></i> Materials
                </a>
                <a href="{{ route('materials.create') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-dark-700 dark:text-dark-200 hover:bg-dark-100 dark:hover:bg-dark-700 transition-colors">
                    <i class="fas fa-cloud-upload-alt w-5 text-center"></i> Upload
                </a>
                <a href="{{ route('favorites.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-dark-700 dark:text-dark-200 hover:bg-dark-100 dark:hover:bg-dark-700 transition-colors">
                    <i class="fas fa-heart w-5 text-center"></i> Saved
                </a>
                <a href="{{ route('profile') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-dark-700 dark:text-dark-200 hover:bg-dark-100 dark:hover:bg-dark-700 transition-colors">
                    <i class="fas fa-user w-5 text-center"></i> Profile
                </a>
                @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-dark-700 dark:text-dark-200 hover:bg-dark-100 dark:hover:bg-dark-700 transition-colors">
                    <i class="fas fa-shield-alt w-5 text-center text-primary-500"></i> Admin Panel
                </a>
                @endif

                <div class="pt-2">
                    <form method="GET" action="{{ route('materials') }}">
                        <div class="relative">
                            <input type="text" name="title" placeholder="Search materials…"
                                   class="input py-2 pl-9 text-sm">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-dark-400 text-xs"></i>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endauth
    </nav>

    <!-- Global alert (session flash) -->
    @if(session('success') || session('error') || session('warning'))
    <div class="fixed top-20 left-1/2 -translate-x-1/2 z-40 w-full max-w-md px-4" data-auto-dismiss>
        @if(session('success'))
        <div class="alert-success animate-slide-up">
            <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
            <p class="text-sm text-green-700 dark:text-green-300">{{ session('success') }}</p>
        </div>
        @elseif(session('error'))
        <div class="alert-error animate-slide-up">
            <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
            <p class="text-sm text-red-700 dark:text-red-300">{{ session('error') }}</p>
        </div>
        @elseif(session('warning'))
        <div class="alert-warning animate-slide-up">
            <i class="fas fa-exclamation-triangle text-amber-500 mt-0.5"></i>
            <p class="text-sm text-amber-700 dark:text-amber-300">{{ session('warning') }}</p>
        </div>
        @endif
    </div>
    @endif

    <!-- Main Content -->
    <main class="pt-16 min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark-900 text-white mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 pb-8 border-b border-dark-700">
                <!-- Brand -->
                <div class="col-span-1 md:col-span-1">
                    <div class="flex items-center gap-1.5 mb-3">
                        <span class="text-xl font-extrabold text-primary-400">Edu</span>
                        <span class="text-xl font-bold">Share</span>
                    </div>
                    <p class="text-dark-400 text-sm mb-4 max-w-xs">
                        Malawi's national platform for sharing and accessing educational materials across universities.
                    </p>
                    <div class="flex gap-3">
                        <a href="#" class="w-8 h-8 rounded-full bg-dark-800 flex items-center justify-center text-dark-400 hover:text-primary-400 hover:bg-dark-700 transition-colors">
                            <i class="fab fa-facebook-f text-sm"></i>
                        </a>
                        <a href="#" class="w-8 h-8 rounded-full bg-dark-800 flex items-center justify-center text-dark-400 hover:text-primary-400 hover:bg-dark-700 transition-colors">
                            <i class="fab fa-twitter text-sm"></i>
                        </a>
                        <a href="#" class="w-8 h-8 rounded-full bg-dark-800 flex items-center justify-center text-dark-400 hover:text-primary-400 hover:bg-dark-700 transition-colors">
                            <i class="fab fa-instagram text-sm"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="font-semibold text-white mb-3 text-sm uppercase tracking-wider">Quick Links</h4>
                    <ul class="space-y-2 text-sm">
                        @auth
                        <li><a href="{{ route('dashboard') }}" class="text-dark-400 hover:text-primary-400 transition-colors">Dashboard</a></li>
                        @endauth
                        <li><a href="{{ route('materials') }}" class="text-dark-400 hover:text-primary-400 transition-colors">Browse Materials</a></li>
                        @auth
                        <li><a href="{{ route('pricing') }}" class="text-dark-400 hover:text-primary-400 transition-colors">Premium</a></li>
                        @endauth
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="font-semibold text-white mb-3 text-sm uppercase tracking-wider">Contact</h4>
                    <ul class="space-y-2 text-sm text-dark-400">
                        <li class="flex items-center gap-2"><i class="fas fa-envelope w-4 text-primary-500"></i> info@edushare.mw</li>
                        <li class="flex items-center gap-2"><i class="fas fa-phone w-4 text-primary-500"></i> +265 1234 5678</li>
                        <li class="flex items-center gap-2"><i class="fas fa-map-marker-alt w-4 text-primary-500"></i> Malawi</li>
                    </ul>
                </div>
            </div>

            <div class="pt-6 flex flex-col sm:flex-row justify-between items-center gap-3">
                <p class="text-dark-500 text-xs">© {{ date('Y') }} EduShare. All rights reserved.</p>
                <div class="flex gap-4 text-xs">
                    <a href="#" class="text-dark-500 hover:text-primary-400 transition-colors">Privacy Policy</a>
                    <a href="#" class="text-dark-500 hover:text-primary-400 transition-colors">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- AI Chat Bubble -->
    @auth
    <button id="ai-chat-toggle"
            class="fixed bottom-6 right-6 w-14 h-14 bg-gradient-to-br from-primary-500 to-primary-700 rounded-full shadow-xl flex items-center justify-center text-white hover:scale-110 transition-transform z-40 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
            aria-label="Open AI Assistant">
        <i class="fas fa-robot text-lg"></i>
    </button>

    <!-- AI Chat Panel -->
    <div id="ai-chat-panel" class="chat-panel hidden flex-col" role="dialog" aria-label="AI Study Assistant">
        <!-- Header -->
        <div class="flex items-center justify-between px-4 py-3 bg-gradient-to-r from-primary-600 to-primary-700">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                    <i class="fas fa-robot text-white text-sm"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-white text-sm">AI Study Assistant</h3>
                    <div class="flex items-center gap-1">
                        <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span>
                        <p class="text-xs text-primary-200">Online</p>
                    </div>
                </div>
            </div>
            <button id="chat-close" class="p-1.5 rounded-lg hover:bg-white/20 transition-colors" aria-label="Close">
                <i class="fas fa-times text-white text-sm"></i>
            </button>
        </div>

        <!-- Messages -->
        <div id="chat-messages" class="chat-messages flex-1 bg-dark-50 dark:bg-dark-900"></div>

        <!-- Input -->
        <div class="p-3 border-t border-dark-200 dark:border-dark-700 bg-white dark:bg-dark-800">
            @if(Auth::user()->subscription_tier !== 'premium' && !Auth::user()->isAdmin())
                <p class="text-xs text-dark-400 dark:text-dark-500 mb-2 flex items-center gap-1">
                    <i class="fas fa-info-circle"></i>
                    Free: 1 query/day.
                    <a href="{{ route('pricing') }}" class="text-primary-600 dark:text-primary-400 hover:underline">Upgrade for unlimited.</a>
                </p>
            @endif
            <div class="flex gap-2">
                <input type="text"
                       id="chat-input"
                       placeholder="Ask anything about your studies…"
                       class="flex-1 px-3 py-2 rounded-lg bg-dark-100 dark:bg-dark-700 border border-dark-200 dark:border-dark-600 text-sm text-dark-900 dark:text-dark-100 placeholder:text-dark-400 focus:outline-none focus:ring-2 focus:ring-primary-500">
                <button id="chat-send"
                        class="w-9 h-9 flex items-center justify-center bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors"
                        aria-label="Send">
                    <i class="fas fa-paper-plane text-sm"></i>
                </button>
            </div>
        </div>
    </div>
    @endauth

    @stack('scripts')
</body>
</html>
