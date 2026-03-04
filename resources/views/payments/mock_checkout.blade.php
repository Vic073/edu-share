@extends('layouts.app')

@section('title', 'Paychangu Sandbox Checkout — EduShare')

@section('content')
<div class="min-h-screen bg-dark-50 dark:bg-dark-900 pt-16 pb-24">
    <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="card p-8 md:p-10 text-center shadow-xl border-t-4 border-amber-500">
            <div class="w-32 h-10 mx-auto bg-dark-200 dark:bg-white rounded flex items-center justify-center font-bold text-dark-500 mb-6 font-mono text-sm shadow-inner overflow-hidden">
                <span class="text-xs tracking-widest uppercase">Paychangu</span>
            </div>
            
            <h3 class="text-2xl font-bold text-dark-900 dark:text-white mb-3">Test Payment Gateway</h3>
            <p class="text-sm text-dark-500 dark:text-dark-400 mb-8 leading-relaxed">
                You are in sandbox development mode. No real funds will be charged. Click below to simulate a successful payment webhook callback from Airtel Money or TNM Mpamba for <strong class="text-dark-900 dark:text-white">MWK {{ number_format($amount) }}</strong>.
            </p>
            
            <hr class="border-dark-100 dark:border-dark-700 mb-6">
            
            <div class="space-y-4 mb-8">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-dark-500 dark:text-dark-400">Transaction Ref:</span>
                    <span class="font-mono font-bold text-dark-900 dark:text-white">{{ $ref }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-dark-500 dark:text-dark-400">Plan:</span>
                    <span class="font-bold text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20 px-2 py-1 rounded">{{ str_replace('_', ' ', Str::title($plan)) }}</span>
                </div>
            </div>

            <!-- Simulation Form -->
            <form action="{{ route('payment.success') }}" method="GET">
                <input type="hidden" name="ref" value="{{ $ref }}">
                <input type="hidden" name="simulate_webhook" value="1">
                
                <button type="submit" class="w-full py-4 rounded-xl font-bold bg-green-500 hover:bg-green-600 text-white shadow-lg shadow-green-500/30 transition-all flex items-center justify-center gap-2 mb-4">
                    <i class="fas fa-check-circle text-lg"></i> Simulate Successful Payment
                </button>
            </form>
            
            <a href="{{ route('pricing') }}" class="btn-ghost w-full justify-center py-3">
                Cancel Payment
            </a>
        </div>

    </div>
</div>
@endsection
