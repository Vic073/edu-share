@extends('layouts.auth')

@section('title', 'Create Account — EduShare')

@section('content')
<div class="max-w-lg mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-dark-900 dark:text-white mb-1">Create Account</h1>
        <p class="text-dark-500 dark:text-dark-400 text-sm">Join Malawi's academic materials network</p>
    </div>

    <!-- Errors -->
    @if($errors->any())
    <div class="alert-error mb-6">
        <i class="fas fa-exclamation-circle text-red-500 mt-0.5 flex-shrink-0"></i>
        <ul class="text-sm text-red-700 dark:text-red-300 space-y-0.5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Form -->
    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Names -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="first_name" class="block text-sm font-medium text-dark-700 dark:text-dark-300 mb-1.5">
                    First Name <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="first_name"
                       name="first_name"
                       value="{{ old('first_name') }}"
                       required
                       autocomplete="given-name"
                       placeholder="John"
                       class="input @error('first_name') input-error @enderror">
            </div>
            <div>
                <label for="last_name" class="block text-sm font-medium text-dark-700 dark:text-dark-300 mb-1.5">
                    Last Name <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="last_name"
                       name="last_name"
                       value="{{ old('last_name') }}"
                       required
                       autocomplete="family-name"
                       placeholder="Doe"
                       class="input @error('last_name') input-error @enderror">
            </div>
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-dark-700 dark:text-dark-300 mb-1.5">
                Email Address <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-dark-400">
                    <i class="fas fa-envelope text-sm"></i>
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
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Institution -->
        <div>
            <label for="institution_id" class="block text-sm font-medium text-dark-700 dark:text-dark-300 mb-1.5">
                Institution <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-dark-400">
                    <i class="fas fa-university text-sm"></i>
                </span>
                <select id="institution_id"
                        name="institution_id"
                        required
                        class="input pl-10 @error('institution_id') input-error @enderror">
                    <option value="">Select your institution</option>
                    @foreach($institutions as $institution)
                        <option value="{{ $institution->id }}" {{ old('institution_id') == $institution->id ? 'selected' : '' }}>
                            {{ $institution->abbreviation }} — {{ $institution->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @error('institution_id')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-dark-700 dark:text-dark-300 mb-1.5">
                Password <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-dark-400">
                    <i class="fas fa-lock text-sm"></i>
                </span>
                <input type="password"
                       id="password"
                       name="password"
                       required
                       autocomplete="new-password"
                       placeholder="Minimum 8 characters"
                       class="input pl-10 pr-10 @error('password') input-error @enderror">
                <button type="button" id="togglePassword"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-dark-400 hover:text-dark-600 dark:hover:text-dark-300 transition-colors">
                    <i class="fas fa-eye text-sm"></i>
                </button>
            </div>
            @error('password')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-dark-700 dark:text-dark-300 mb-1.5">
                Confirm Password <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-dark-400">
                    <i class="fas fa-lock text-sm"></i>
                </span>
                <input type="password"
                       id="password_confirmation"
                       name="password_confirmation"
                       required
                       autocomplete="new-password"
                       placeholder="Re-enter your password"
                       class="input pl-10">
            </div>
        </div>

        <!-- Terms -->
        <div class="flex items-start gap-2.5">
            <input type="checkbox"
                   name="terms"
                   id="terms"
                   required
                   class="w-4 h-4 mt-0.5 rounded border-dark-300 text-primary-600 focus:ring-primary-500">
            <label for="terms" class="text-sm text-dark-600 dark:text-dark-400 leading-relaxed">
                I agree to the
                <a href="{{ route('terms') }}" class="text-primary-600 hover:text-primary-700 dark:text-primary-400 underline-offset-2 hover:underline">Terms of Service</a>
                and I confirm all uploaded content will comply with copyright laws.
            </label>
        </div>

        <!-- Submit -->
        <button type="submit" class="btn-primary w-full py-3">
            <i class="fas fa-user-plus"></i> Create Account
        </button>
    </form>

    <!-- Login link -->
    <p class="text-center mt-6 text-dark-500 dark:text-dark-400 text-sm">
        Already have an account?
        <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700 dark:text-primary-400 font-medium">
            Sign in
        </a>
    </p>
</div>
@endsection

@section('scripts')
<script>
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput  = document.getElementById('password');
    togglePassword?.addEventListener('click', function() {
        const isText = passwordInput.type === 'text';
        passwordInput.type = isText ? 'password' : 'text';
        const icon = this.querySelector('i');
        icon.classList.toggle('fa-eye',       isText);
        icon.classList.toggle('fa-eye-slash', !isText);
    });
</script>
@endsection
