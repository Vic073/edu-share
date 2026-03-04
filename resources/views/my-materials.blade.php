@extends('layouts.app')

@section('title', 'My Materials — EduShare')

@section('content')
<div class="min-h-screen bg-dark-50 dark:bg-dark-900 pt-8 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 border-b border-dark-200 dark:border-dark-800 pb-5">
            <div>
                <h1 class="text-3xl font-bold text-dark-900 dark:text-white mb-2">My Materials</h1>
                <p class="text-dark-500 dark:text-dark-400">Manage all the documents you have uploaded to EduShare.</p>
            </div>
            
            <a href="{{ route('materials.create') }}" class="btn-primary flex-shrink-0 inline-flex self-start sm:self-auto">
                <i class="fas fa-cloud-upload-alt mr-2"></i> Upload New
            </a>
        </div>

        @if(session('success'))
            <div class="alert-success mb-6">
                <i class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                <p class="text-sm text-green-700 dark:text-green-300">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Filters (Optional / Simple) --}}
        <div class="flex justify-end mb-6">
             <div class="bg-dark-200 dark:bg-dark-800 p-1 rounded-lg">
                <button class="p-1.5 px-4 bg-white dark:bg-dark-700 shadow-sm rounded-md text-dark-900 dark:text-white transition-all text-sm font-medium">All Uploads</button>
                <button class="p-1.5 px-4 text-dark-500 hover:text-dark-900 dark:hover:text-white transition-all text-sm">Most Downloaded</button>
            </div>
        </div>

        @if($materials->count() > 0)
            <div class="card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-dark-50 dark:bg-dark-800/50 border-b border-dark-200 dark:border-dark-700 text-xs uppercase tracking-wider text-dark-500 dark:text-dark-400 font-semibold">
                                <th class="py-4 px-6">Material</th>
                                <th class="py-4 px-6 text-center">Status</th>
                                <th class="py-4 px-6 text-center">Stats</th>
                                <th class="py-4 px-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-dark-100 dark:divide-dark-800">
                            @foreach($materials as $material)
                                <tr class="hover:bg-dark-50/50 dark:hover:bg-dark-800/20 transition-colors group">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-lg bg-dark-100 dark:bg-dark-800 flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-file-{{ getFileIcon($material->file_type) }} text-lg text-primary-500"></i>
                                            </div>
                                            <div>
                                                <h3 class="font-bold text-dark-900 dark:text-white text-sm">
                                                    <a href="{{ route('materials.view', $material->id) }}" class="hover:text-primary-600 dark:hover:text-primary-400">
                                                        {{ $material->title }}
                                                    </a>
                                                </h3>
                                                <div class="flex items-center gap-2 mt-1">
                                                    <span class="badge badge-primary text-[9px]">{{ $material->course_code }}</span>
                                                    <span class="text-[11px] text-dark-500">{{ $material->created_at->format('M d, Y') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="py-4 px-6 text-center">
                                        @if($material->status === 'approved')
                                            <span class="px-2.5 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded border border-green-200 dark:border-green-800 text-[11px] font-bold">Approved</span>
                                        @elseif($material->status === 'pending')
                                            <span class="px-2.5 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 rounded border border-amber-200 dark:border-amber-800 text-[11px] font-bold">Pending Review</span>
                                        @else
                                            <span class="px-2.5 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded border border-red-200 dark:border-red-800 text-[11px] font-bold">Rejected</span>
                                        @endif
                                    </td>

                                    <td class="py-4 px-6 text-center">
                                        <div class="flex items-center justify-center gap-4 text-xs text-dark-500 dark:text-dark-400">
                                            <span title="Downloads" class="flex items-center gap-1.5"><i class="fas fa-download text-primary-500"></i> {{ $material->download_count }}</span>
                                            <span title="Rating" class="flex items-center gap-1.5"><i class="fas fa-star text-amber-500"></i> {{ number_format($material->ratings->avg('rating') ?: 0, 1) }}</span>
                                        </div>
                                    </td>

                                    <td class="py-4 px-6 text-right space-x-2">
                                        <a href="{{ route('materials.view', $material->id) }}" class="p-2 text-dark-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors" title="View Material">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('materials.destroy', $material->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this material permanently?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-dark-400 hover:text-red-500 transition-colors" title="Delete Material">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
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
                <div class="w-20 h-20 bg-dark-100 dark:bg-dark-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-folder-open text-3xl text-dark-400"></i>
                </div>
                <h3 class="text-lg font-bold text-dark-900 dark:text-white mb-2">You haven't uploaded anything</h3>
                <p class="text-dark-500 dark:text-dark-400 max-w-sm mx-auto mb-6">Start sharing your lecture notes, past papers, or study guides to help others learn.</p>
                <a href="{{ route('materials.create') }}" class="btn-primary">
                    <i class="fas fa-plus mr-2"></i> Upload First Material
                </a>
            </div>
        @endif

    </div>
</div>
@endsection
