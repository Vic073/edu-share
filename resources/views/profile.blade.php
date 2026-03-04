@extends('layouts.app')

@section('title', 'My Profile — EduShare')

@section('content')
<div class="min-h-screen bg-dark-50 dark:bg-dark-900 pt-8 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-dark-900 dark:text-white mb-2">My Profile</h1>
            <p class="text-dark-500 dark:text-dark-400">Manage your account settings, institution details, and verify your identity.</p>
        </div>

        @if(session('success'))
            <div class="alert-success mb-8">
                <i class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                <p class="text-sm text-green-700 dark:text-green-300">{{ session('success') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Left Column: User Info & Subscription --}}
            <div class="lg:col-span-1 space-y-8">
                
                {{-- User Badge Card --}}
                <div class="card p-6 text-center shadow-lg relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-br from-primary-600 to-primary-800"></div>
                    
                    <div class="relative mt-8 mb-4 mx-auto w-24 h-24 rounded-full bg-white dark:bg-dark-800 border-4 border-white dark:border-dark-800 shadow-md flex items-center justify-center">
                        <div class="w-full h-full rounded-full bg-gradient-to-br from-dark-100 to-dark-200 dark:from-dark-700 dark:to-dark-800 flex items-center justify-center text-3xl font-bold text-dark-600 dark:text-dark-300 shadow-inner">
                            {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                        </div>
                        
                        @if($user->kyc_status === 'verified')
                            <div class="absolute bottom-0 right-0 w-7 h-7 bg-white dark:bg-dark-800 rounded-full flex items-center justify-center shadow-sm">
                                <i class="fas fa-check-circle text-green-500 text-lg" title="Verified Account"></i>
                            </div>
                        @endif
                    </div>

                    <h2 class="text-xl font-bold text-dark-900 dark:text-white">
                        {{ $user->first_name }} {{ $user->last_name }}
                    </h2>
                    <p class="text-sm text-dark-500 dark:text-dark-400 mb-4">{{ $user->email }}</p>

                    <div class="flex justify-center flex-wrap gap-2">
                        <span class="badge bg-dark-100 dark:bg-dark-700 text-dark-600 dark:text-dark-300 capitalize text-xs">
                            <i class="fas fa-user text-[10px] mr-1"></i> User
                        </span>
                        
                        @if($user->subscription_tier === 'premium')
                            <span class="badge badge-gold text-xs">
                                <i class="fas fa-crown text-[10px] mr-1"></i> Premium
                            </span>
                        @else
                            <span class="badge bg-dark-100 dark:bg-dark-700 text-dark-600 dark:text-dark-300 text-xs">
                                Free Plan
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Subscription Box --}}
                <div class="card p-6 border-2 {{ $user->subscription_tier === 'premium' ? 'border-amber-400 dark:border-amber-600 bg-amber-50 dark:bg-amber-900/10' : 'border-dark-200 dark:border-dark-700' }}">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold {{ $user->subscription_tier === 'premium' ? 'text-amber-800 dark:text-amber-300' : 'text-dark-900 dark:text-white' }}">
                            <i class="fas fa-{{ $user->subscription_tier === 'premium' ? 'crown' : 'ticket-alt' }} mr-2"></i> Subscription
                        </h3>
                    </div>
                    
                    @if($user->subscription_tier === 'premium')
                        <p class="text-sm text-amber-700 dark:text-amber-400/80 mb-4">
                            You have full access to unlimited downloads, priority AI chat, and advanced search features.
                        </p>
                        <div class="flex items-center justify-between text-xs text-amber-800 dark:text-amber-300 font-medium bg-amber-100 dark:bg-amber-900/40 p-3 rounded-lg">
                            <span>Status: Active</span>
                            <span>Renews automatically</span>
                        </div>
                    @else
                        <p class="text-sm text-dark-600 dark:text-dark-400 mb-5 leading-relaxed">
                            Upgrade to <strong class="text-amber-600 dark:text-amber-400">Premium</strong> to unlock unlimited downloads and full AI Study Assistant capabilities.
                        </p>
                        <a href="{{ route('pricing') }}" class="btn-primary w-full justify-center bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 border-0 shadow-lg shadow-amber-500/30">
                            Upgrade Now
                        </a>
                    @endif
                </div>

                {{-- KYC Box --}}
                <div class="card p-6">
                    <h3 class="font-bold text-dark-900 dark:text-white mb-4">
                        <i class="fas fa-shield-alt mr-2 text-primary-500"></i> Verification Status
                    </h3>
                    
                    @if($user->kyc_status === 'verified')
                        <div class="p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg flex items-start gap-3">
                            <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                            <div>
                                <p class="text-sm font-semibold text-green-800 dark:text-green-300">Identity Verified</p>
                                <p class="text-xs text-green-700 dark:text-green-400/80 mt-1">Your account is fully verified. You enjoy maximum upload limits.</p>
                            </div>
                        </div>
                    @elseif($user->kyc_status === 'pending')
                        <div class="p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg flex items-start gap-3">
                            <i class="fas fa-clock text-amber-500 mt-0.5 animate-pulse"></i>
                            <div>
                                <p class="text-sm font-semibold text-amber-800 dark:text-amber-300">Verification Pending</p>
                                <p class="text-xs text-amber-700 dark:text-amber-400/80 mt-1">Our admins are reviewing your submitted documents. This usually takes 24 hours.</p>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-dark-600 dark:text-dark-400 mb-5 leading-relaxed">
                            Verifying your identity proves you are a real user from a Malawian institution, unlocking credibility.
                        </p>
                        <a href="{{ route('kyc.submit') }}" class="btn-secondary w-full justify-center text-primary-600 dark:text-primary-400 border border-primary-200 dark:border-primary-800 hover:bg-primary-50 dark:hover:bg-primary-900/20">
                            Verify Identity
                        </a>
                    @endif
                </div>

            </div>

            {{-- Right Column: Forms --}}
            <div class="lg:col-span-2 space-y-8">
                
                {{-- Personal Information --}}
                <div class="card p-6 md:p-8">
                    <h3 class="text-lg font-bold text-dark-900 dark:text-white mb-6 border-b border-dark-100 dark:border-dark-700 pb-4">
                        Personal Information
                    </h3>
                    
                    <form action="{{ route('profile.update') }}" method="POST" class="space-y-5">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-dark-700 dark:text-dark-300 mb-1.5">First Name</label>
                                <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required class="input">
                            </div>
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-dark-700 dark:text-dark-300 mb-1.5">Last Name</label>
                                <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required class="input">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="email" class="block text-sm font-medium text-dark-700 dark:text-dark-300 mb-1.5">Email Address</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="input">
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium text-dark-700 dark:text-dark-300 mb-1.5">Phone Number</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="input" placeholder="+265...">
                            </div>
                        </div>

                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save mr-2"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Academic Information --}}
                <div class="card p-6 md:p-8">
                    <h3 class="text-lg font-bold text-dark-900 dark:text-white mb-6 border-b border-dark-100 dark:border-dark-700 pb-4">
                        Academic Information
                    </h3>
                    
                    <form action="{{ route('profile.academic.update') }}" method="POST" class="space-y-5">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label for="institution_id" class="block text-sm font-medium text-dark-700 dark:text-dark-300 mb-1.5">Institution</label>
                            <select id="institution_id" name="institution_id" required class="input bg-white dark:bg-dark-800">
                                <option value="">Select Institution</option>
                                @foreach($institutions as $inst)
                                    <option value="{{ $inst->id }}" {{ (old('institution_id') ?? $user->institution_id) == $inst->id ? 'selected' : '' }}>
                                        {{ $inst->abbreviation }} — {{ $inst->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="department" class="block text-sm font-medium text-dark-700 dark:text-dark-300 mb-1.5">Department / Faculty</label>
                            <input type="text" id="department" name="department" value="{{ old('department', $user->department) }}" class="input" placeholder="e.g., Computer Science">
                        </div>

                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save mr-2"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
                
                {{-- Password Update --}}
                <div class="card p-6 md:p-8">
                    <h3 class="text-lg font-bold text-dark-900 dark:text-white mb-6 border-b border-dark-100 dark:border-dark-700 pb-4">
                        Security
                    </h3>
                    
                    <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-5">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-dark-700 dark:text-dark-300 mb-1.5">Current Password</label>
                            <input type="password" id="current_password" name="current_password" required class="input">
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="password" class="block text-sm font-medium text-dark-700 dark:text-dark-300 mb-1.5">New Password</label>
                                <input type="password" id="password" name="password" required class="input">
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-dark-700 dark:text-dark-300 mb-1.5">Confirm New Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" required class="input">
                            </div>
                        </div>

                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="btn-secondary">
                                <i class="fas fa-key mr-2"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
