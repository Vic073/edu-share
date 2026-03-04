@extends('layouts.app')

@section('title', 'Admin Dashboard — EduShare')

@section('content')
<div class="min-h-screen bg-dark-50 dark:bg-dark-900 pt-20 pb-12">

    {{-- Header --}}
    <div class="bg-gradient-to-r from-dark-900 via-dark-800 to-primary-900/20 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-8 h-8 rounded-lg bg-primary-600/20 flex items-center justify-center">
                            <i class="fas fa-shield-alt text-primary-400 text-sm"></i>
                        </div>
                        <span class="text-primary-400 text-sm font-medium uppercase tracking-wider">Admin Panel</span>
                    </div>
                    <h1 class="text-2xl font-bold text-white">Platform Administration</h1>
                    <p class="text-dark-300 text-sm mt-1">Manage users, approve materials, and verify identities.</p>
                </div>
                <div class="hidden md:block text-right text-xs text-dark-400">
                    <p>{{ now()->format('l, F j') }}</p>
                    <p>{{ now()->format('g:i A') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Stat Cards --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-10">
            @foreach([
                ['label'=>'Total Users',         'value'=>$stats['total_users'],        'icon'=>'users',       'from'=>'from-blue-500',   'to'=>'to-blue-600',    'link'=>null],
                ['label'=>'Pending Uploads',      'value'=>$stats['pending_materials'],  'icon'=>'file-upload', 'from'=>'from-amber-500',  'to'=>'to-amber-600',   'link'=>route('admin.materials.list')],
                ['label'=>'Pending KYC',          'value'=>$stats['pending_kyc'],        'icon'=>'id-card',     'from'=>'from-purple-500', 'to'=>'to-purple-600',  'link'=>route('admin.kyc.list')],
                ['label'=>'Total Materials',      'value'=>$stats['total_materials'],    'icon'=>'book',        'from'=>'from-green-500',  'to'=>'to-green-600',   'link'=>null],
            ] as $stat)
            @if($stat['link'])
                <a href="{{ $stat['link'] }}" class="card p-5 card-hover block">
            @else
                <div class="card p-5">
            @endif
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br {{ $stat['from'] }} {{ $stat['to'] }} flex items-center justify-center text-white flex-shrink-0">
                        <i class="fas fa-{{ $stat['icon'] }}"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-dark-900 dark:text-white">{{ $stat['value'] }}</p>
                        <p class="text-xs text-dark-500 dark:text-dark-400">{{ $stat['label'] }}</p>
                    </div>
                </div>
                @if($stat['link'])
                    <p class="text-xs text-primary-600 dark:text-primary-400 mt-2 flex items-center gap-1">View all <i class="fas fa-arrow-right text-[9px]"></i></p>
                @endif
            @if($stat['link'])
                </a>
            @else
                </div>
            @endif
            @endforeach
        </div>

        {{-- Alerts when pending items exist --}}
        @if($stats['pending_materials'] > 0 || $stats['pending_kyc'] > 0)
        <div class="alert-warning mb-8">
            <i class="fas fa-bell text-amber-500 mt-0.5 flex-shrink-0 animate-pulse"></i>
            <p class="text-sm text-amber-700 dark:text-amber-300">
                You have
                @if($stats['pending_materials'] > 0)
                    <strong>{{ $stats['pending_materials'] }} materials</strong> awaiting approval
                @endif
                @if($stats['pending_materials'] > 0 && $stats['pending_kyc'] > 0)
                    and
                @endif
                @if($stats['pending_kyc'] > 0)
                    <strong>{{ $stats['pending_kyc'] }} KYC submissions</strong> to review
                @endif.
            </p>
        </div>
        @endif

        {{-- Action Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Materials --}}
            <div class="card overflow-hidden">
                <div class="h-2 bg-gradient-to-r from-amber-400 to-orange-500"></div>
                <div class="p-6">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-folder-open text-amber-600 dark:text-amber-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-dark-900 dark:text-white">Material Approvals</h3>
                            <p class="text-sm text-dark-500 dark:text-dark-400 mt-1">
                                Review and approve or reject uploaded academic materials to maintain platform quality.
                            </p>
                        </div>
                    </div>
                    @if($stats['pending_materials'] > 0)
                    <div class="mb-4 px-3 py-2 bg-amber-50 dark:bg-amber-900/20 rounded-lg border border-amber-200 dark:border-amber-800">
                        <p class="text-xs text-amber-700 dark:text-amber-300 font-medium">
                            <i class="fas fa-clock mr-1"></i> {{ $stats['pending_materials'] }} material(s) pending review
                        </p>
                    </div>
                    @endif
                    <a href="{{ route('admin.materials.list') }}" class="btn-primary w-full justify-center">
                        <i class="fas fa-folder"></i> Manage Materials
                    </a>
                </div>
            </div>

            {{-- KYC --}}
            <div class="card overflow-hidden">
                <div class="h-2 bg-gradient-to-r from-primary-500 to-primary-700"></div>
                <div class="p-6">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-12 h-12 rounded-xl bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-user-shield text-primary-600 dark:text-primary-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-dark-900 dark:text-white">KYC Verifications</h3>
                            <p class="text-sm text-dark-500 dark:text-dark-400 mt-1">
                                Review identity submissions and grant verified status to eligible users.
                            </p>
                        </div>
                    </div>
                    @if($stats['pending_kyc'] > 0)
                    <div class="mb-4 px-3 py-2 bg-purple-50 dark:bg-purple-900/20 rounded-lg border border-purple-200 dark:border-purple-800">
                        <p class="text-xs text-purple-700 dark:text-purple-300 font-medium">
                            <i class="fas fa-clock mr-1"></i> {{ $stats['pending_kyc'] }} user(s) awaiting KYC review
                        </p>
                    </div>
                    @endif
                    <a href="{{ route('admin.kyc.list') }}" class="btn-secondary w-full justify-center">
                        <i class="fas fa-id-check"></i> Manage KYC Queue
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
