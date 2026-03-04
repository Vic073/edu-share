@extends('layouts.app')

@section('title', 'Upgrade to Premium — EduShare')

@section('content')
<div class="min-h-screen bg-dark-50 dark:bg-dark-900 pt-16 pb-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-block py-1 px-3 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-400 text-xs font-bold tracking-widest uppercase mb-4 border border-amber-200 dark:border-amber-800/50">
                Premium Unlock
            </span>
            <h1 class="text-4xl md:text-5xl font-extrabold text-dark-900 dark:text-white mb-6">
                Accelerate Your Academic Journey
            </h1>
            <p class="text-lg md:text-xl text-dark-600 dark:text-dark-300 leading-relaxed">
                Get unlimited access across all Malawian universities, downloadable materials, and AI-powered study tools.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-5xl mx-auto items-center">
            
            {{-- Free Tier Card --}}
            <div class="card p-8 md:p-10 border-2 border-dark-200 dark:border-dark-700 bg-white dark:bg-dark-800/50">
                <div class="mb-8">
                    <h3 class="text-2xl font-bold text-dark-900 dark:text-white">Basic Access</h3>
                    <p class="text-sm text-dark-500 dark:text-dark-400 mt-2">Standard account for local campus study.</p>
                </div>
                
                <div class="mb-8 pb-8 border-b border-dark-100 dark:border-dark-700">
                    <span class="text-5xl font-black text-dark-900 dark:text-white">Free</span>
                </div>
                
                <ul class="space-y-4 mb-10">
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                        <span class="text-dark-700 dark:text-dark-300">View your university's materials</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                        <span class="text-dark-700 dark:text-dark-300">30 mins daily reading limit</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                        <span class="text-dark-700 dark:text-dark-300">1 AI query per day</span>
                    </li>
                    <li class="flex items-start opacity-50">
                        <i class="fas fa-times text-red-500 mt-1 mr-3"></i>
                        <span class="text-dark-700 dark:text-dark-300 line-through">No cross-university access</span>
                    </li>
                    <li class="flex items-start opacity-50">
                        <i class="fas fa-times text-red-500 mt-1 mr-3"></i>
                        <span class="text-dark-700 dark:text-dark-300 line-through">No file downloads</span>
                    </li>
                </ul>

                <button class="w-full py-4 rounded-xl font-bold bg-dark-100 dark:bg-dark-700 text-dark-500 dark:text-dark-400 cursor-not-allowed">
                    Current Plan
                </button>
            </div>

            {{-- Premium Tier Card --}}
            <div class="rounded-2xl shadow-2xl relative overflow-hidden bg-gradient-to-br from-primary-800 to-primary-950 border border-primary-700">
                
                {{-- Decorative bg --}}
                <div class="absolute top-0 right-0 p-8 opacity-20 transform translate-x-1/4 -translate-y-1/4">
                    <i class="fas fa-crown text-[150px] text-amber-500"></i>
                </div>
                
                <div class="relative z-10 p-8 md:p-10">
                    <div class="mb-6">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/20 text-amber-400 text-sm font-bold mb-4 border border-amber-500/30">
                            <i class="fas fa-star text-[10px]"></i> Most Popular
                        </div>
                        <h3 class="text-3xl font-bold text-white">EduShare Premium</h3>
                        <p class="text-primary-200 mt-2">The ultimate toolkit for Malawian students.</p>
                    </div>
                    
                    <div class="mb-8 pb-8 border-b border-primary-700/50">
                        <div class="flex items-baseline gap-2">
                            <span class="text-5xl font-black text-white">MWK 2,500</span>
                            <span class="text-xl text-primary-300">/mo</span>
                        </div>
                    </div>
                    
                    <ul class="space-y-4 mb-10">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-amber-400 mt-1.5 mr-3 text-lg"></i>
                            <span class="text-white text-lg font-medium">Unlimited cross-university access</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-amber-400 mt-1.5 mr-3 text-lg"></i>
                            <span class="text-white text-lg font-medium">Unlimited file downloads</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-amber-400 mt-1.5 mr-3 text-lg"></i>
                            <span class="text-white text-lg font-medium">Unlimited AI Summaries & Q&A</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-amber-400 mt-1.5 mr-3 text-lg"></i>
                            <span class="text-white text-lg font-medium">Priority upload queues</span>
                        </li>
                    </ul>

                    <form action="{{ route('payment.initiate') }}" method="POST">
                        @csrf
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-primary-200 mb-2">Select Duration</label>
                            <select name="plan_duration" class="w-full bg-primary-900/50 border border-primary-600 text-white rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-amber-500 transition-shadow">
                                <option value="1_month" class="text-dark-900">1 Month - MWK 2,500</option>
                                <option value="4_months" class="text-dark-900">1 Semester (4 Mos) - MWK 8,000</option>
                                <option value="12_months" class="text-dark-900">1 Year - MWK 20,000 (Best Value)</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="w-full py-4 rounded-xl font-bold bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-dark-900 shadow-xl shadow-amber-500/20 transform hover:-translate-y-0.5 transition-all flex items-center justify-center text-lg">
                            Upgrade via Paychangu <i class="fas fa-arrow-right ml-2 opacity-70"></i>
                        </button>
                    </form>
                    
                    <div class="text-center mt-6 flex flex-col items-center justify-center gap-2">
                        <span class="text-xs text-primary-300 flex items-center gap-1.5">
                            <i class="fas fa-lock"></i> Secured by Airtel Money & Mpamba
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
