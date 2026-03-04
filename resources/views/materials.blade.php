@extends('layouts.app')

@section('title', 'Browse Materials — EduShare')

@section('content')
<div class="min-h-screen bg-dark-50 dark:bg-dark-900 pt-8 pb-12">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header & Search --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-dark-900 dark:text-white mb-2">Browse Materials</h1>
            <p class="text-dark-500 dark:text-dark-400">Find lecture notes, past papers, and study guides from universities across Malawi.</p>
        </div>

        <div class="card p-4 sm:p-5 mb-8">
            <form action="{{ route('materials') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                
                {{-- Search Input --}}
                <div class="flex-1 relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-dark-400">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" name="title" value="{{ request('title') }}" placeholder="Search by title, course code, or topic..." 
                           class="w-full pl-11 pr-4 py-3 rounded-xl bg-dark-50 dark:bg-dark-900 border border-dark-200 dark:border-dark-700 text-sm text-dark-900 dark:text-white focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-colors">
                </div>

                {{-- Filters --}}
                <div class="flex gap-3">
                    <select name="type" class="px-4 py-3 rounded-xl bg-dark-50 dark:bg-dark-900 border border-dark-200 dark:border-dark-700 text-sm focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 text-dark-800 dark:text-dark-200">
                        <option value="">All Types</option>
                        <option value="lecture_notes" {{ request('type') == 'lecture_notes' ? 'selected' : '' }}>Lecture Notes</option>
                        <option value="past_paper" {{ request('type') == 'past_paper' ? 'selected' : '' }}>Past Papers</option>
                        <option value="assignment" {{ request('type') == 'assignment' ? 'selected' : '' }}>Assignments</option>
                        <option value="study_guide" {{ request('type') == 'study_guide' ? 'selected' : '' }}>Study Guides</option>
                        <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Others</option>
                    </select>

                    <button type="submit" class="btn-primary py-3 px-6 whitespace-nowrap">
                        <i class="fas fa-filter text-sm"></i> <span class="hidden sm:inline">Filter</span>
                    </button>
                    @if(request()->hasAny(['title', 'type', 'institution']))
                        <a href="{{ route('materials') }}" class="btn-ghost py-3 px-4 whitespace-nowrap" title="Clear filters">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>

            </form>
        </div>

        {{-- Results --}}
        <div class="flex items-center justify-between mb-6">
            <p class="text-sm font-medium text-dark-600 dark:text-dark-300">
                Found <span class="text-primary-600 dark:text-primary-400 font-bold">{{ $materials->total() }}</span> materials
            </p>
            
            {{-- Views toggle (Grid vs List) --}}
            <div class="flex bg-dark-200 dark:bg-dark-800 p-1 rounded-lg">
                <button type="button" onclick="setViewMode('grid')" id="btn-grid" class="p-1.5 px-3 bg-white dark:bg-dark-700 shadow-sm rounded-md text-dark-900 dark:text-white transition-all text-sm">
                    <i class="fas fa-th-large"></i>
                </button>
                <button type="button" onclick="setViewMode('list')" id="btn-list" class="p-1.5 px-3 text-dark-500 hover:text-dark-900 dark:hover:text-white transition-all text-sm">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>

        @if($materials->count() > 0)
            
            {{-- Grid View --}}
            <div id="view-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">
                @foreach($materials as $material)
                <div class="material-card w-full">
                    <div class="material-card-cover">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <i class="fas fa-file-{{ getFileIcon($material->file_type) }} text-5xl text-dark-400 dark:text-dark-500"></i>
                        </div>
                        <div class="material-card-overlay"></div>
                        <div class="absolute bottom-2 left-2 right-2 flex flex-wrap gap-1">
                            <span class="badge badge-primary text-[10px]">{{ $material->course_code }}</span>
                            @if($material->institution)
                                <span class="badge bg-dark-800/80 text-dark-200 text-[9px]">{{ $material->institution->abbreviation }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="p-3">
                        <h3 class="font-medium text-dark-900 dark:text-white text-sm truncate mb-1">
                            <a href="{{ route('materials.view', $material->id) }}" class="hover:text-primary-600 dark:hover:text-primary-400" title="{{ $material->title }}">
                                {{ $material->title }}
                            </a>
                        </h3>
                        <p class="text-[10px] text-dark-500 dark:text-dark-400 mb-2 truncate">
                            By {{ $material->uploader->first_name ?? 'Unknown User' }} {{ $material->uploader->last_name ?? '' }}
                        </p>
                        <div class="flex items-center justify-between text-[11px] text-dark-500 dark:text-dark-400">
                            <span><i class="fas fa-download mr-1 text-primary-500"></i>{{ $material->download_count }}</span>
                            <span>{{ $material->created_at->shortRelativeDiffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- List View --}}
            <div id="view-list" class="hidden space-y-3">
                @foreach($materials as $material)
                <div class="bg-white dark:bg-dark-800 rounded-xl border border-dark-200 dark:border-dark-700 p-4 hover:-translate-y-0.5 transition-transform flex items-center gap-4 shadow-sm group">
                    <div class="w-12 h-12 rounded-xl bg-dark-100 dark:bg-dark-700 flex items-center justify-center flex-shrink-0 group-hover:bg-primary-50 dark:group-hover:bg-primary-900/30 transition-colors">
                         <i class="fas fa-file-{{ getFileIcon($material->file_type) }} text-2xl text-dark-400 dark:text-dark-500 group-hover:text-primary-500 transition-colors"></i>
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="badge badge-primary text-[10px]">{{ $material->course_code }}</span>
                            <span class="badge bg-dark-100 dark:bg-dark-700 text-dark-600 dark:text-dark-300 text-[10px]">{{ str_replace('_', ' ', title_case($material->type)) }}</span>
                        </div>
                        <h3 class="font-semibold text-dark-900 dark:text-white text-base truncate">
                            <a href="{{ route('materials.view', $material->id) }}" class="hover:text-primary-600 dark:hover:text-primary-400">
                                {{ $material->title }}
                            </a>
                        </h3>
                        <p class="text-xs text-dark-500 dark:text-dark-400 truncate mt-1">
                            Uploaded by {{ $material->uploader->first_name ?? 'Unknown User' }} {{ $material->uploader->last_name ?? '' }}
                            @if($material->institution) • {{ $material->institution->name }} @endif
                            • {{ $material->created_at->diffForHumans() }}
                        </p>
                    </div>

                    <div class="flex items-center gap-4 flex-shrink-0 text-sm text-dark-500 dark:text-dark-400">
                        <div class="text-center px-3 border-r border-dark-200 dark:border-dark-700 hidden sm:block">
                            <span class="block font-medium text-dark-900 dark:text-white">{{ $material->download_count }}</span>
                            <span class="text-[10px] uppercase tracking-wider">Downloads</span>
                        </div>
                        <a href="{{ route('materials.view', $material->id) }}" class="btn-secondary py-2">
                            View <i class="fas fa-chevron-right ml-1 text-xs"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $materials->links() }}
            </div>
            
        @else
            <div class="card p-12 text-center">
                <div class="w-20 h-20 bg-dark-100 dark:bg-dark-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-search text-3xl text-dark-400"></i>
                </div>
                <h3 class="text-lg font-bold text-dark-900 dark:text-white mb-2">No materials found</h3>
                <p class="text-dark-500 dark:text-dark-400 max-w-md mx-auto mb-6">We couldn't find any materials matching your search criteria. Try adjusting your filters or upload a new material yourself.</p>
                <a href="{{ route('materials.create') }}" class="btn-primary">
                    <i class="fas fa-cloud-upload-alt mr-2"></i> Upload Material
                </a>
            </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
    function setViewMode(mode) {
        const grid = document.getElementById('view-grid');
        const list = document.getElementById('view-list');
        const btnGrid = document.getElementById('btn-grid');
        const btnList = document.getElementById('btn-list');
        
        if (mode === 'grid') {
            grid.classList.remove('hidden');
            list.classList.add('hidden');
            
            btnGrid.className = 'p-1.5 px-3 bg-white dark:bg-dark-700 shadow-sm rounded-md text-dark-900 dark:text-white transition-all text-sm';
            btnList.className = 'p-1.5 px-3 text-dark-500 hover:text-dark-900 dark:hover:text-white transition-all text-sm';
            localStorage.setItem('edushare-material-view', 'grid');
        } else {
            grid.classList.add('hidden');
            list.classList.remove('hidden');
            
            btnList.className = 'p-1.5 px-3 bg-white dark:bg-dark-700 shadow-sm rounded-md text-dark-900 dark:text-white transition-all text-sm';
            btnGrid.className = 'p-1.5 px-3 text-dark-500 hover:text-dark-900 dark:hover:text-white transition-all text-sm';
            localStorage.setItem('edushare-material-view', 'list');
        }
    }

    // Initialize on load
    document.addEventListener('DOMContentLoaded', () => {
        const saved = localStorage.getItem('edushare-material-view') || 'grid';
        setViewMode(saved);
        
        // Stagger grid animations
        const gridCards = document.querySelectorAll('#view-grid .material-card');
        gridCards.forEach((card, i) => {
            card.style.opacity = '0';
            card.style.animation = `fadeIn .4s ease-out ${i * 0.05}s forwards`;
        });
        
        // Stagger list animations
        const listCards = document.querySelectorAll('#view-list > div');
        listCards.forEach((card, i) => {
            card.style.opacity = '0';
            card.style.animation = `slideUp .3s ease-out ${i * 0.05}s forwards`;
        });
    });
</script>
@endpush
