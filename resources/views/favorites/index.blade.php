@extends('layouts.app')

@section('title', 'Saved Materials — EduShare')

@section('content')
<div class="min-h-screen bg-dark-50 dark:bg-dark-900 pt-8 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 border-b border-dark-200 dark:border-dark-800 pb-5">
            <div>
                <h1 class="text-3xl font-bold text-dark-900 dark:text-white mb-2">Saved Materials</h1>
                <p class="text-dark-500 dark:text-dark-400">View and manage the materials you've saved for later access.</p>
            </div>
            
            <a href="{{ route('materials') }}" class="btn-ghost flex-shrink-0 inline-flex self-start sm:self-auto">
                <i class="fas fa-search mr-2"></i> Browse More
            </a>
        </div>

        @if(session('success'))
            <div class="alert-success mb-6">
                <i class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                <p class="text-sm text-green-700 dark:text-green-300">{{ session('success') }}</p>
            </div>
        @endif

        @if($favorites->count() > 0)
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($favorites as $material)
                <div class="card p-0 overflow-hidden flex flex-col h-full group hover:-translate-y-1 transition-transform">
                    
                    {{-- Graphic / Top Half --}}
                    <div class="h-32 bg-dark-100 dark:bg-dark-800 relative flex items-center justify-center border-b border-dark-200 dark:border-dark-700">
                        <div class="absolute inset-0 bg-gradient-to-br from-primary-500/10 to-transparent"></div>
                        <i class="fas fa-file-{{ getFileIcon($material->file_type) }} text-5xl text-dark-300 dark:text-dark-600 group-hover:text-primary-500 transition-colors z-10"></i>
                        
                        {{-- Badges --}}
                        <div class="absolute top-3 left-3 flex gap-1.5 z-20">
                            <span class="badge badge-primary text-[10px] shadow-sm">{{ $material->course_code }}</span>
                        </div>
                        
                        {{-- Unfavorite Action --}}
                        <div class="absolute top-2 right-2 z-20 opacity-0 group-hover:opacity-100 transition-opacity">
                            <form action="{{ route('favorites.toggle', $material->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-8 h-8 rounded-full bg-white dark:bg-dark-700 shadow flex items-center justify-center text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors" title="Remove from saved">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    {{-- Content / Bottom Half --}}
                    <div class="p-4 flex-1 flex flex-col">
                        <h3 class="font-bold text-dark-900 dark:text-white text-base mb-1 line-clamp-2">
                            <a href="{{ route('materials.view', $material->id) }}" class="hover:text-primary-600 dark:hover:text-primary-400">
                                {{ $material->title }}
                            </a>
                        </h3>
                        <p class="text-xs text-dark-500 dark:text-dark-400 mb-4 line-clamp-2 flex-1">
                            {{ $material->description ?? 'No description provided.' }}
                        </p>
                        
                        <div class="flex items-center justify-between mt-auto pt-4 border-t border-dark-100 dark:border-dark-800">
                            <span class="text-[11px] text-dark-400 font-medium pb-0.5">
                                <i class="far fa-clock mr-1"></i> {{ $material->created_at->diffForHumans() }}
                            </span>
                            <a href="{{ route('materials.download', $material->id) }}" class="text-[11px] font-bold text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 uppercase tracking-wide bg-primary-50 dark:bg-primary-900/20 px-3 py-1.5 rounded-md transition-colors">
                                Download
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $favorites->links() }}
            </div>

        @else
            <div class="card bg-transparent border-2 border-dashed border-dark-200 dark:border-dark-700 p-12 text-center max-w-2xl mx-auto shadow-none">
                <div class="w-20 h-20 bg-dark-100 dark:bg-dark-800 rounded-full flex items-center justify-center mx-auto mb-5">
                    <i class="far fa-heart text-3xl text-dark-400"></i>
                </div>
                <h3 class="text-xl font-bold text-dark-900 dark:text-white mb-2">No Saved Materials</h3>
                <p class="text-dark-500 dark:text-dark-400 mb-8 max-w-md mx-auto leading-relaxed">
                    You haven't saved any materials yet. Browse the library and click the <i class="fas fa-heart text-red-400 mx-1"></i> icon on any document you want to keep here for quick access.
                </p>
                <a href="{{ route('materials') }}" class="btn-primary inline-flex justify-center px-8">
                    Browse Materials
                </a>
            </div>
        @endif
        
    </div>
</div>
@endsection
