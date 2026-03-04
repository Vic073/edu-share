@extends('layouts.app')

@section('title', 'Identity Verification (KYC) — EduShare')

@section('content')
<div class="min-h-screen bg-dark-50 dark:bg-dark-900 pt-12 pb-24">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-10">
            <h1 class="text-3xl lg:text-4xl font-extrabold text-dark-900 dark:text-white mb-4">
                Identity Verification (KYC)
            </h1>
            <p class="text-dark-500 dark:text-dark-400 text-lg">
                To maintain academic integrity, all users must verify their identity.
            </p>
        </div>

        <div class="card p-6 md:p-10 shadow-xl overflow-hidden relative">
            
            {{-- Decorative Bg Element --}}
            <div class="absolute top-0 right-0 w-64 h-64 bg-primary-500/10 rounded-full blur-3xl pointer-events-none transform translate-x-1/3 -translate-y-1/3"></div>

            <div class="relative z-10">
                @if(session('success'))
                    <div class="alert-success mb-8">
                        <i class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                        <p class="text-sm text-green-700 dark:text-green-300">{{ session('success') }}</p>
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 mb-8 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-500"></i>
                            </div>
                            <div class="ml-3">
                                <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-400 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                @if(!$existingSubmission || $existingSubmission->status === 'rejected')
                    
                    @if($existingSubmission && $existingSubmission->status === 'rejected')
                        <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 p-4 rounded-xl flex items-start gap-3 mb-8">
                            <i class="fas fa-exclamation-triangle text-amber-500 mt-0.5"></i>
                            <div>
                                <h4 class="text-sm font-bold text-amber-800 dark:text-amber-300">Prior Submission Rejected</h4>
                                <p class="text-sm text-amber-700 dark:text-amber-400/90 mt-1">{{ $existingSubmission->rejection_reason ?? 'Invalid document. Please upload a clear photo of your ID.' }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="bg-dark-50 dark:bg-dark-800/50 rounded-xl p-5 border border-dark-100 dark:border-dark-700 mb-8 flexitems-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center flex-shrink-0 text-primary-600 dark:text-primary-400 mt-1 float-left mr-4 inline-block">
                             <i class="fas fa-info-circle"></i>
                        </div>
                        <p class="text-sm text-dark-600 dark:text-dark-300 leading-relaxed">
                            Please upload a clear, legible photo of either your University Student ID or your National Identity Card. This information will only be used to verify your account.
                        </p>
                        <div class="clear-both"></div>
                    </div>
                    
                    <form action="{{ route('kyc.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <div>
                            <label for="document_type" class="block text-sm font-semibold text-dark-900 dark:text-white mb-2">
                                Document Type <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-id-badge text-dark-400"></i>
                                </div>
                                <select id="document_type" name="document_type" required class="input pl-11 bg-white dark:bg-dark-900 text-dark-900 dark:text-white cursor-pointer appearance-none">
                                    <option value="" disabled selected>Select an ID type</option>
                                    <option value="student_id">University Student ID</option>
                                    <option value="national_id">National ID Card</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-dark-400 text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="document" class="block text-sm font-semibold text-dark-900 dark:text-white mb-2">
                                Upload Photo <span class="text-red-500">*</span>
                            </label>
                            
                            <div class="mt-1 flex justify-center px-6 pt-8 pb-8 border-2 border-dashed border-dark-300 dark:border-dark-600 rounded-xl bg-dark-50 dark:bg-dark-800/50 hover:bg-dark-100 dark:hover:bg-dark-700/50 transition-colors group relative cursor-pointer">
                                <div class="space-y-2 text-center">
                                    <div class="mx-auto w-16 h-16 bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                        <i class="fas fa-cloud-upload-alt text-2xl"></i>
                                    </div>
                                    <div class="flex flex-col text-sm text-dark-600 dark:text-dark-400">
                                        <label for="document" class="relative cursor-pointer rounded-md font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 focus-within:outline-none">
                                            <span>Upload a file</span>
                                            <input id="document" name="document" type="file" class="sr-only" accept=".jpg,.jpeg,.png,.pdf" required onchange="document.getElementById('file-name').textContent = this.files[0].name">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-dark-500 dark:text-dark-500">
                                        PNG, JPG, PDF up to 5MB
                                    </p>
                                    <p id="file-name" class="text-sm font-semibold text-primary-600 dark:text-primary-400 mt-2 truncate max-w-[200px] mx-auto"></p>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-dark-100 dark:border-dark-800">
                            <button type="submit" class="btn-primary w-full justify-center py-4 text-base">
                                <i class="fas fa-shield-check mr-2"></i> Submit for Verification
                            </button>
                        </div>
                    </form>

                @elseif($existingSubmission->status === 'pending')
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-amber-100 dark:bg-amber-900/20 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-clock text-4xl animate-pulse"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-dark-900 dark:text-white mb-3">Verification Pending</h3>
                        <p class="text-dark-600 dark:text-dark-400 max-w-md mx-auto mb-8 leading-relaxed">
                            We have received your KYC document. It is currently being reviewed by an administrator. This normally takes 24-48 hours.
                        </p>
                        <a href="{{ route('dashboard') }}" class="btn-secondary px-8">
                            Return to Dashboard
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
