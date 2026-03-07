@extends('layouts.auth')

@section('title', 'Sign In - EduShare')

@section('content')
<div class="max-w-md mx-auto">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-dark-900 dark:text-white mb-2">Welcome Back</h1>
        <p class="text-dark-500 dark:text-dark-400">Sign in to access your academic materials</p>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
            <p class="text-sm text-red-600 dark:text-red-400">
                <i class="fas fa-exclamation-circle mr-1"></i>
                {{ $errors->first() }}
            </p>
        </div>
    @endif

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
            <p class="text-sm text-green-600 dark:text-green-400">
                <i class="fas fa-check-circle mr-1"></i>
                {{ session('success') }}
            </p>
        </div>
    @endif

    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-dark-700 dark:text-dark-300 mb-1.5">Email Address</label>
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-dark-400">
                    <i class="fas fa-envelope"></i>
                </span>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}"
                       required
                       autocomplete="email"
                       placeholder="you@example.com"
                       class="input pl-10 @error('email') input-error @enderror">
            </div>
            @error('email')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-dark-700 dark:text-dark-300 mb-1.5">Password</label>
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-dark-400">
                    <i class="fas fa-lock"></i>
                </span>
                <input type="password" 
                       id="password" 
                       name="password" 
                       required
                       autocomplete="current-password"
                       placeholder="••••••••"
                       class="input pl-10 @error('password') input-error @enderror">
                <button type="button" 
                        id="togglePassword"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-dark-400 hover:text-dark-600 dark:hover:text-dark-300">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            @error('password')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me & Forgot -->
        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" 
                       name="remember" 
                       id="remember"
                       {{ old('remember') ? 'checked' : '' }}
                       class="w-4 h-4 rounded border-dark-300 text-primary-600 focus:ring-primary-500">
                <span class="text-sm text-dark-600 dark:text-dark-400">Remember me</span>
            </label>
            
            @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400">
                    Forgot password?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-primary w-full py-3">
            <i class="fas fa-sign-in-alt mr-2"></i>Sign In
        </button>
    </form>

    <!-- Register Link -->
    <p class="text-center mt-8 text-dark-600 dark:text-dark-400">
        Don't have an account?
        <a href="{{ route('register') }}" class="text-primary-600 hover:text-primary-700 font-medium">
            Create one
        </a>
    </p>
    
    
</div>

@endsection

@section('scripts')
<script>
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    
    togglePassword?.addEventListener('click', function() {
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;
        
        const icon = this.querySelector('i');
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    });
</script>
@endsection
