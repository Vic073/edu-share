@extends('layouts.app')

@section('title', 'KYC Verifications — Admin Panel')

@section('content')
<div class="min-h-screen bg-dark-50 dark:bg-dark-900 pt-8 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 border-b border-dark-200 dark:border-dark-800 pb-5">
            <div>
                <nav class="flex items-center gap-2 text-sm text-dark-400 mb-2 font-medium">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-primary-400 transition-colors">Admin Panel</a>
                    <i class="fas fa-chevron-right text-[10px]"></i>
                    <span class="text-dark-300">KYC Verifications</span>
                </nav>
                <h1 class="text-3xl font-bold text-dark-900 dark:text-white mb-2">KYC Verifications</h1>
                <p class="text-dark-500 dark:text-dark-400">Review user identity documents and grant verified status.</p>
            </div>
            
            <div class="flex gap-2">
                <a href="{{ route('admin.dashboard') }}" class="btn-ghost text-sm py-2">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert-success mb-6">
                <i class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                <p class="text-sm text-green-700 dark:text-green-300">{{ session('success') }}</p>
            </div>
        @endif

        @if($kycRequests->count() > 0)
            <div class="card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-dark-50 dark:bg-dark-800/50 border-b border-dark-200 dark:border-dark-700 text-xs uppercase tracking-wider text-dark-500 dark:text-dark-400 font-semibold">
                                <th class="py-4 px-6">User Details</th>
                                <th class="py-4 px-6">Document Info</th>
                                <th class="py-4 px-6 text-center">Submitted</th>
                                <th class="py-4 px-6 text-right w-40">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-dark-100 dark:divide-dark-800">
                            @foreach($kycRequests as $kyc)
                                <tr class="hover:bg-dark-50/50 dark:hover:bg-dark-800/20 transition-colors">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center font-bold text-primary-600 dark:text-primary-400 flex-shrink-0">
                                                {{ strtoupper(substr($kyc->user->first_name, 0, 1)) }}{{ strtoupper(substr($kyc->user->last_name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <h3 class="font-bold text-dark-900 dark:text-white text-sm">
                                                    {{ $kyc->user->first_name }} {{ $kyc->user->last_name }}
                                                </h3>
                                                <p class="text-[11px] text-dark-500 mt-0.5">{{ $kyc->user->email }}</p>
                                                <div class="flex items-center gap-2 mt-1.5">
                                                    @if($kyc->user->institution)
                                                        <span class="badge bg-dark-100 dark:bg-dark-800 border border-dark-200 dark:border-dark-700 text-dark-600 dark:text-dark-300 text-[9px]">
                                                            {{ $kyc->user->institution->abbreviation }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="py-4 px-6">
                                        <div class="flex flex-col gap-1.5 align-start">
                                            <strong class="text-sm text-dark-800 dark:text-dark-200">{{ strtoupper(str_replace('_', ' ', $kyc->document_type)) }}</strong>
                                            
                                            <a href="{{ Storage::url($kyc->document_path) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 group">
                                                <i class="fas fa-external-link-alt group-hover:-translate-y-0.5 group-hover:translate-x-0.5 transition-transform"></i>
                                                View Document Fullscreen
                                            </a>
                                        </div>
                                    </td>

                                    <td class="py-4 px-6 text-center">
                                        <div class="text-sm text-dark-600 dark:text-dark-300">
                                            {{ $kyc->created_at->format('M d, Y') }}
                                        </div>
                                        <div class="text-[10px] text-dark-400 mt-0.5">
                                            {{ $kyc->created_at->format('h:i A') }}
                                        </div>
                                    </td>

                                    <td class="py-4 px-6 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            {{-- Approve --}}
                                            <form action="{{ route('admin.kyc.approve', $kyc->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="p-2 rounded-lg bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 hover:bg-green-100 dark:hover:bg-green-900/40 border border-green-200 dark:border-green-800/30 transition-colors" title="Approve Verification" onclick="return confirm('Approve this user\'s identity? They will be granted full verified powers.');">
                                                    <i class="fas fa-user-check"></i>
                                                </button>
                                            </form>
                                            
                                            {{-- Reject --}}
                                            <form action="{{ route('admin.kyc.reject', $kyc->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="p-2 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/40 border border-red-200 dark:border-red-800/30 transition-colors" title="Reject Verification" onclick="return confirm('Reject this verification request?');">
                                                    <i class="fas fa-user-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="mt-6">
                {{ $kycRequests->links() }}
            </div>

        @else
            <div class="card p-12 text-center border border-dashed border-dark-200 dark:border-dark-700 bg-transparent shadow-none">
                <div class="w-16 h-16 bg-purple-100 dark:bg-purple-900/20 text-purple-500 rounded-full flex items-center justify-center mx-auto mb-4 border border-purple-200 dark:border-purple-800/30">
                    <i class="fas fa-id-card-alt text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-dark-900 dark:text-white mb-2">Queue is empty!</h3>
                <p class="text-dark-500 dark:text-dark-400 max-w-sm mx-auto">There are no pending identity verification requests at this time.</p>
            </div>
        @endif

    </div>
</div>
@endsection
