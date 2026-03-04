@extends('layouts.app')

@section('title', 'Material Approvals — Admin Panel')

@section('content')
<div class="min-h-screen bg-dark-50 dark:bg-dark-900 pt-8 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 border-b border-dark-200 dark:border-dark-800 pb-5">
            <div>
                <nav class="flex items-center gap-2 text-sm text-dark-400 mb-2 font-medium">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-primary-400 transition-colors">Admin Panel</a>
                    <i class="fas fa-chevron-right text-[10px]"></i>
                    <span class="text-dark-300">Material Approvals</span>
                </nav>
                <h1 class="text-3xl font-bold text-dark-900 dark:text-white mb-2">Material Approvals</h1>
                <p class="text-dark-500 dark:text-dark-400">Review pending uploads before they are published to the platform.</p>
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

        @if($materials->count() > 0)
            <div class="card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-dark-50 dark:bg-dark-800/50 border-b border-dark-200 dark:border-dark-700 text-xs uppercase tracking-wider text-dark-500 dark:text-dark-400 font-semibold">
                                <th class="py-4 px-6">Material Details</th>
                                <th class="py-4 px-6">Uploader</th>
                                <th class="py-4 px-6 text-center">Date</th>
                                <th class="py-4 px-6 text-right w-40">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-dark-100 dark:divide-dark-800">
                            @foreach($materials as $material)
                                <tr class="hover:bg-dark-50/50 dark:hover:bg-dark-800/20 transition-colors">
                                    <td class="py-4 px-6">
                                        <div class="flex items-start gap-4">
                                            <div class="w-10 h-10 rounded-lg bg-dark-100 dark:bg-dark-800 flex items-center justify-center flex-shrink-0 mt-1">
                                                <i class="fas fa-file-{{ getFileIcon($material->file_type) }} text-lg text-amber-500"></i>
                                            </div>
                                            <div>
                                                <h3 class="font-bold text-dark-900 dark:text-white text-sm">
                                                    <a href="{{ route('materials.view', $material->id) }}" target="_blank" class="hover:text-primary-600 dark:hover:text-primary-400 flex items-center gap-1.5" title="Preview Material">
                                                        {{ str_limit($material->title, 40) }} <i class="fas fa-external-link-alt text-[10px] text-dark-400"></i>
                                                    </a>
                                                </h3>
                                                <p class="text-[11px] text-dark-500 line-clamp-1 max-w-sm mt-0.5">{{ $material->description }}</p>
                                                <div class="flex items-center gap-2 mt-2">
                                                    <span class="badge badge-primary text-[9px]">{{ $material->course_code }}</span>
                                                    <span class="badge bg-dark-100 dark:bg-dark-800 text-dark-600 dark:text-dark-300 text-[9px] uppercase tracking-wider">
                                                        {{ formatBytes($material->file_size) }}
                                                    </span>
                                                    @if($material->institution)
                                                        <span class="badge bg-dark-100 dark:bg-dark-800 text-dark-600 dark:text-dark-300 text-[9px]">
                                                            {{ $material->institution->abbreviation }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-2 text-sm text-dark-900 dark:text-white font-medium">
                                            <div class="w-6 h-6 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400 text-[10px]">
                                                {{ strtoupper(substr($material->uploader->first_name, 0, 1)) }}
                                            </div>
                                            {{ $material->uploader->first_name }} {{ $material->uploader->last_name }}
                                        </div>
                                        <div class="text-[11px] text-dark-500 mt-1 pl-8">
                                            <a href="mailto:{{ $material->uploader->email }}" class="hover:text-primary-500">{{ $material->uploader->email }}</a>
                                        </div>
                                    </td>

                                    <td class="py-4 px-6 text-center">
                                        <div class="text-sm text-dark-600 dark:text-dark-300">
                                            {{ $material->created_at->format('M d, Y') }}
                                        </div>
                                        <div class="text-[10px] text-dark-400 mt-0.5">
                                            {{ $material->created_at->format('h:i A') }}
                                        </div>
                                    </td>

                                    <td class="py-4 px-6 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            {{-- Approve --}}
                                            <form action="{{ route('admin.materials.approve', $material->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="p-2 rounded-lg bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 hover:bg-green-100 dark:hover:bg-green-900/40 border border-green-200 dark:border-green-800/30 transition-colors" title="Approve Material">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            
                                            {{-- Reject --}}
                                            <form action="{{ route('admin.materials.reject', $material->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="p-2 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/40 border border-red-200 dark:border-red-800/30 transition-colors" title="Reject Material" onclick="return confirm('Are you sure you want to reject this upload?');">
                                                    <i class="fas fa-times"></i>
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
                {{ $materials->links() }}
            </div>

        @else
            <div class="card p-12 text-center border border-dashed border-dark-200 dark:border-dark-700 bg-transparent shadow-none">
                <div class="w-16 h-16 bg-green-100 dark:bg-green-900/20 text-green-500 rounded-full flex items-center justify-center mx-auto mb-4 border border-green-200 dark:border-green-800/30">
                    <i class="fas fa-check-double text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-dark-900 dark:text-white mb-2">All caught up!</h3>
                <p class="text-dark-500 dark:text-dark-400 max-w-sm mx-auto">There are no pending materials requiring your approval at this time.</p>
            </div>
        @endif

    </div>
</div>
@endsection
