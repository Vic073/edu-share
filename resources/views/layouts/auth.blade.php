<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'EduShare')</title>
    <meta name="description" content="EduShare — Malawi's national platform for sharing academic materials across universities.">

    <!-- FOUC prevention -->
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @yield('styles')
</head>
<body class="min-h-screen bg-dark-900 flex items-center justify-center p-4">

    <!-- Decorative background -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden" aria-hidden="true">
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-primary-600/10 rounded-full -translate-y-1/3 translate-x-1/3 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-primary-700/10 rounded-full translate-y-1/3 -translate-x-1/3 blur-3xl"></div>
    </div>

    <!-- Theme toggle (top right) -->
    <button id="theme-toggle" class="theme-toggle fixed top-4 right-4 z-50" aria-label="Toggle dark/light mode">
        <span class="theme-toggle-thumb">
            <i class="fas fa-sun text-[10px] text-amber-500"></i>
        </span>
    </button>

    <div class="relative z-10 w-full max-w-5xl">
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5">
                <span class="text-4xl font-extrabold text-primary-400">Edu</span>
                <span class="text-4xl font-bold text-white">Share</span>
            </a>
            <p class="text-dark-400 text-sm mt-1">Malawi's Academic Materials Network</p>
        </div>

        <!-- Main card -->
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-2xl overflow-hidden border border-dark-700/30">
            <div class="grid grid-cols-1 lg:grid-cols-5">

                <!-- Left decorative panel -->
                <div class="hidden lg:flex lg:col-span-2 bg-gradient-to-br from-primary-700 via-primary-600 to-primary-800 p-8 flex-col justify-between relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-48 h-48 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                    <div class="absolute bottom-0 left-0 w-36 h-36 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/2"></div>

                    <div class="relative z-10">
                        <h2 class="text-2xl font-bold text-white mb-2">Welcome to EduShare</h2>
                        <p class="text-primary-100 text-sm leading-relaxed">
                            Malawi's national platform for sharing and accessing quality academic materials across universities.
                        </p>
                    </div>

                    <div class="relative z-10 space-y-5">
                        @foreach([
                            ['icon'=>'university',  'title'=>'Multi-University Access', 'desc'=>'Access materials from all major universities in Malawi'],
                            ['icon'=>'robot',       'title'=>'AI-Powered Learning',     'desc'=>'Get instant summaries and answers about any material'],
                            ['icon'=>'shield-alt',  'title'=>'Secure & Verified',       'desc'=>'Identity-verified access for quality content'],
                        ] as $feat)
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-white/20 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-{{ $feat['icon'] }} text-white text-sm"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-white text-sm">{{ $feat['title'] }}</h4>
                                <p class="text-primary-200 text-xs leading-relaxed">{{ $feat['desc'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="relative z-10 pt-4 border-t border-white/20">
                        <p class="text-primary-200 text-xs">© {{ date('Y') }} EduShare. All rights reserved.</p>
                    </div>
                </div>

                <!-- Right form panel -->
                <div class="col-span-1 lg:col-span-3 p-8 lg:p-12">
                    @yield('content')
                </div>
            </div>
        </div>

        <!-- Back link -->
        <div class="text-center mt-6">
            <a href="{{ route('login') }}" class="text-dark-500 hover:text-primary-400 text-sm transition-colors">
                <i class="fas fa-arrow-left mr-1"></i> Back to sign in
            </a>
        </div>
    </div>

    @yield('scripts')
</body>
</html>
