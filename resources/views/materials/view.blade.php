@extends('layouts.app')

@section('title', $material->title . ' — EduShare')

@section('content')

{{-- Hero Section --}}
<div class="relative bg-dark-900 pt-20 pb-16 overflow-hidden">
    {{-- Background image/blur --}}
    <div class="absolute inset-0 select-none pointer-events-none">
        <div class="absolute inset-0 bg-gradient-to-t from-dark-900 via-dark-900/80 to-dark-800/40 z-10"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full opacity-20 blur-3xl flex items-center justify-center">
            <i class="fas fa-file-{{ getFileIcon($material->file_type) }} text-[400px] text-primary-500"></i>
        </div>
    </div>

    <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-sm text-dark-400 mb-6 font-medium animate-fade-in" style="animation-delay: 0.1s;">
            <a href="{{ route('materials') }}" class="hover:text-primary-400 transition-colors">Materials</a>
            <i class="fas fa-chevron-right text-[10px]"></i>
            <a href="{{ route('materials', ['type' => $material->type]) }}" class="hover:text-primary-400 transition-colors capitalize">
                {{ str_replace('_', ' ', $material->type) }}
            </a>
            <i class="fas fa-chevron-right text-[10px]"></i>
            <span class="text-dark-300">{{ $material->course_code }}</span>
        </nav>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 items-end">
            {{-- Title & Info --}}
            <div class="md:col-span-3 animate-slide-up" style="animation-delay: 0.2s;">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-4 leading-tight">
                    {{ $material->title }}
                </h1>
                
                <p class="text-lg text-dark-300 mb-6 max-w-3xl leading-relaxed">
                    {{ $material->description }}
                </p>
                
                <div class="flex flex-wrap items-center gap-3">
                    <span class="px-3 py-1 bg-primary-600 outline outline-2 outline-offset-2 outline-primary-600/30 rounded text-xs font-bold text-white tracking-wider">
                        {{ $material->course_code }}
                    </span>
                    <span class="badge bg-dark-800 border border-dark-700 text-dark-200">
                        <i class="fas fa-file-{{ getFileIcon($material->file_type) }} mr-1.5 text-primary-400"></i>
                        {{ strtoupper($material->file_extension ?? $material->file_type) }}
                    </span>
                    @if($material->institution)
                        <span class="badge bg-dark-800 border border-dark-700 text-dark-200" title="{{ $material->institution->name }}">
                            <i class="fas fa-university mr-1.5 text-primary-400"></i>
                            {{ $material->institution->abbreviation }}
                        </span>
                    @endif
                    <span class="text-sm text-dark-400 font-medium">
                        {{ formatBytes($material->file_size) }} • {{ $material->created_at->diffForHumans() }}
                    </span>
                </div>
            </div>

            {{-- Primary Actions --}}
            <div class="md:col-span-1 flex flex-col gap-3 animate-slide-up" style="animation-delay: 0.3s;">
                <a href="{{ route('materials.download', $material->id) }}" class="btn-primary w-full justify-center py-3.5 text-base shadow-lg shadow-primary-600/20 hover:shadow-primary-600/40 hover:-translate-y-0.5">
                    <i class="fas fa-download mr-2 text-lg"></i> Download PDF
                </a>
                
                <form action="{{ route('favorites.toggle', $material->id) }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="w-full py-3.5 rounded-xl font-medium flex items-center justify-center gap-2 transition-all border
                        {{ auth()->user()->favorites->contains($material) 
                            ? 'bg-red-500/10 text-red-500 border-red-500/30 hover:bg-red-500/20' 
                            : 'bg-dark-800 text-white border-dark-700 hover:bg-dark-700' }}">
                        <i class="fas fa-plus {{ auth()->user()->favorites->contains($material) ? 'hidden' : 'inline-block' }}"></i>
                        <i class="fas fa-check {{ auth()->user()->favorites->contains($material) ? 'inline-block' : 'hidden' }}"></i>
                        {{ auth()->user()->favorites->contains($material) ? 'Saved to My List' : 'Add to My List' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Main Content Array --}}
<div class="min-h-screen bg-dark-50 dark:bg-dark-900 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Left Column (Docs & AI) --}}
            <div class="lg:col-span-2 space-y-8">
                
                {{-- AI Integration Card --}}
                <div class="card p-1 sm:p-6 bg-gradient-to-br from-dark-800 to-dark-900 border-primary-900/30 shadow-xl overflow-hidden relative">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-primary-600/10 rounded-full blur-3xl pointer-events-none"></div>
                    
                    <div class="relative z-10">
                        <div class="flex items-center gap-4 mb-5">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center shadow-lg shadow-primary-500/30">
                                <i class="fas fa-robot text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white">Ask EduShare AI</h3>
                                <p class="text-sm text-primary-200">Get instant summaries, key concepts, or exact answers from this document.</p>
                            </div>
                        </div>
                        
                        <div class="flex flex-wrap gap-3 mb-6">
                            <button id="btn-summarize" class="px-5 py-2.5 bg-dark-700 hover:bg-dark-600 text-white rounded-xl font-medium transition-colors border border-dark-600 flex items-center gap-2">
                                <i class="fas fa-align-left text-primary-400"></i> Summarize
                            </button>
                            <button id="btn-keypoints" class="px-5 py-2.5 bg-dark-700 hover:bg-dark-600 text-white rounded-xl font-medium transition-colors border border-dark-600 flex items-center gap-2">
                                <i class="fas fa-list-ul text-amber-400"></i> Key Concepts
                            </button>
                            @if(auth()->user()->subscription_tier === 'premium' || auth()->user()->isAdmin())
                                <button id="btn-ask" class="px-5 py-2.5 bg-primary-600 hover:bg-primary-500 text-white rounded-xl font-medium transition-colors border border-primary-500 flex items-center gap-2">
                                    <i class="fas fa-comment-dots"></i> Ask Question
                                </button>
                            @else
                                <a href="{{ route('pricing') }}" class="px-5 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white rounded-xl font-medium transition-all shadow-md shadow-amber-500/20 flex items-center gap-2">
                                    <i class="fas fa-crown"></i> Ask Question (Premium)
                                </a>
                            @endif
                        </div>
                        
                        {{-- AI Response Area --}}
                        <div id="ai-output" class="hidden rounded-xl bg-dark-900 border border-dark-700 p-5 mt-4 min-h-[100px]">
                            <div id="ai-loading" class="hidden flex items-center gap-3 text-dark-300">
                                <div class="w-5 h-5 border-2 border-primary-500 border-t-transparent rounded-full animate-spin"></div>
                                <span class="text-sm">Reading document and generating response...</span>
                            </div>
                            <div id="ai-response" class="prose prose-invert prose-sm max-w-none text-dark-200"></div>
                        </div>
                    </div>
                </div>

                {{-- Comments Section --}}
                <div class="card p-6">
                    <h3 class="text-xl font-bold text-dark-900 dark:text-white mb-6 border-b border-dark-100 dark:border-dark-700 pb-4">
                        Discussion ({{ $material->comments->count() }})
                    </h3>
                    
                    {{-- Add Comment --}}
                    <form action="{{ route('comments.store', $material->id) }}" method="POST" class="mb-8">
                        @csrf
                        <div class="flex gap-4">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-dark-200 to-dark-300 dark:from-dark-700 dark:to-dark-800 flex items-center justify-center font-bold text-dark-600 dark:text-dark-300 flex-shrink-0">
                                {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}{{ strtoupper(substr(auth()->user()->last_name, 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <textarea name="comment" rows="2" class="w-full bg-dark-50 dark:bg-dark-900 border border-dark-200 dark:border-dark-700 rounded-xl px-4 py-3 text-dark-900 dark:text-white placeholder-dark-400 focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 resize-none transition-colors mb-3" placeholder="Share your thoughts or ask a question..."></textarea>
                                <div class="flex justify-end">
                                    <button type="submit" class="btn-primary py-2 px-6">
                                        Post <i class="fas fa-paper-plane ml-1.5 text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    {{-- Comments List --}}
                    <div class="space-y-6">
                        @forelse($material->comments as $comment)
                        <div class="flex gap-4 group">
                            <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400 font-bold flex-shrink-0 text-sm">
                                {{ strtoupper(substr($comment->user->first_name, 0, 1)) }}{{ strtoupper(substr($comment->user->last_name, 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <div class="bg-dark-50 dark:bg-dark-800/50 rounded-2xl rounded-tl-none p-4 relative">
                                    <div class="flex items-center justify-between gap-4 mb-1.5">
                                        <span class="font-bold text-dark-900 dark:text-white">
                                            {{ $comment->user->first_name }} {{ $comment->user->last_name }}
                                            @if($comment->user->id === $material->uploader_id)
                                                <span class="badge badge-primary text-[9px] ml-1">Author</span>
                                            @endif
                                        </span>
                                        <span class="text-[11px] text-dark-400 whitespace-nowrap">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-dark-700 dark:text-dark-300 text-sm leading-relaxed">
                                        {{ $comment->comment }}
                                    </p>
                                    
                                    @if($comment->user_id === auth()->id())
                                        <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 rounded-full bg-white dark:bg-dark-700 shadow flex items-center justify-center text-dark-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors" title="Delete comment">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-10 bg-dark-50 dark:bg-dark-800/30 rounded-2xl border border-dashed border-dark-200 dark:border-dark-700">
                            <i class="far fa-comments text-3xl text-dark-300 dark:text-dark-600 mb-3 block"></i>
                            <p class="text-dark-500 dark:text-dark-400 text-sm">No discussion yet. Be the first to share your thoughts!</p>
                        </div>
                        @endforelse
                    </div>
                </div>

            </div>
            
            {{-- Right Column (Stats & Rating) --}}
            <div class="space-y-6">
                
                {{-- Uploader Info --}}
                <div class="card p-5 border border-dark-200 dark:border-dark-700">
                    <p class="text-[11px] uppercase tracking-wider text-dark-500 font-semibold mb-3">Uploaded By</p>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-dark-100 dark:bg-dark-800 flex items-center justify-center font-bold text-dark-600 dark:text-dark-300 shadow-sm">
                            {{ strtoupper(substr($material->uploader->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($material->uploader->last_name ?? 'ser', 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-bold text-dark-900 dark:text-white">{{ $material->uploader->first_name ?? 'Unknown' }} {{ $material->uploader->last_name ?? '' }}</p>
                            <p class="text-xs text-dark-500 flex items-center gap-1 mt-0.5">
                                @if(($material->uploader->kyc_status ?? '') === 'verified')
                                    <i class="fas fa-check-circle text-green-500 text-[10px]" title="Verified User"></i> 
                                @endif
                                {{ ucfirst($material->uploader->role ?? 'User') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Ratings Card --}}
                <div class="card p-6">
                    <h3 class="font-bold text-dark-900 dark:text-white mb-4">Reviews</h3>
                    
                    <div class="flex items-end gap-3 mb-6">
                        <span class="text-5xl font-black text-dark-900 dark:text-white leading-none">
                            {{ number_format($averageRating, 1) }}
                        </span>
                        <div class="pb-1">
                            <div class="flex text-lg mb-0.5">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $averageRating ? 'text-amber-400' : 'text-dark-300 dark:text-dark-600' }}"></i>
                                @endfor
                            </div>
                            <p class="text-xs text-dark-500">{{ $material->ratings->count() }} ratings</p>
                        </div>
                    </div>
                    
                    {{-- Rating Bars --}}
                    <div class="space-y-2 mb-8">
                        @php
                            $counts = [0, 0, 0, 0, 0];
                            foreach($material->ratings as $r) {
                                $counts[$r->rating - 1]++;
                            }
                            $total = $material->ratings->count() ?: 1;
                        @endphp
                        @for ($i = 5; $i >= 1; $i--)
                            <div class="flex items-center gap-3 text-xs">
                                <span class="text-dark-500 w-2">{{ $i }}</span>
                                <i class="fas fa-star text-dark-400 text-[10px]"></i>
                                <div class="flex-1 h-1.5 bg-dark-100 dark:bg-dark-700 rounded-full overflow-hidden">
                                    <div class="h-full bg-amber-400 rounded-full" style="width: {{ ($counts[$i-1] / $total) * 100 }}%"></div>
                                </div>
                                <span class="text-dark-400 w-4 text-right">{{ $counts[$i-1] }}</span>
                            </div>
                        @endfor
                    </div>
                    
                    {{-- Your Rating --}}
                    <div class="border-t border-dark-100 dark:border-dark-700 pt-5">
                        <p class="text-sm font-semibold text-dark-900 dark:text-white mb-3 text-center">Rate this material</p>
                        <form action="{{ route('materials.rate', $material->id) }}" method="POST" id="ratingForm">
                            @csrf
                            <div class="flex justify-center gap-2 mb-4">
                                @for ($i = 1; $i <= 5; $i++)
                                    <button type="button" class="rate-btn p-1 outline-none transform hover:scale-125 transition-transform" data-rating="{{ $i }}">
                                        <i class="fas fa-star text-3xl {{ $userRating >= $i ? 'text-amber-400' : 'text-dark-200 dark:text-dark-700 hover:text-amber-200' }} transition-colors"></i>
                                    </button>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" id="selectedRating" value="{{ $userRating }}">
                            <button type="submit" class="btn-secondary w-full justify-center" {{ $userRating ? '' : 'disabled' }}>
                                {{ $userRating ? 'Update Rating' : 'Submit Rating' }}
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Admin Controls (if admin) --}}
                @if(auth()->user()->isAdmin())
                <div class="card p-5 border border-red-500/20 bg-red-50/50 dark:bg-red-900/10">
                    <h3 class="font-bold text-red-800 dark:text-red-400 mb-3 flex items-center gap-2">
                        <i class="fas fa-shield-alt"></i> Admin Controls
                    </h3>
                    <form action="{{ route('admin.materials.delete', $material->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this material?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-2 bg-white dark:bg-dark-800 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 rounded-lg text-sm font-medium hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors">
                            <i class="fas fa-trash-alt mr-1.5"></i> Delete Material
                        </button>
                    </form>
                </div>
                @endif
                
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const materialId = {{ $material->id }};

    document.addEventListener('DOMContentLoaded', function() {
        // Star Rating Script
        const ratingBtns = document.querySelectorAll('.rate-btn');
        const selectedRating = document.getElementById('selectedRating');
        const submitBtn = document.querySelector('#ratingForm button[type="submit"]');
        
        ratingBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const rating = parseInt(this.dataset.rating);
                selectedRating.value = rating;
                submitBtn.removeAttribute('disabled');
                
                ratingBtns.forEach(b => {
                    const starIcon = b.querySelector('i');
                    const btnRating = parseInt(b.dataset.rating);
                    starIcon.classList.toggle('text-amber-400', btnRating <= rating);
                    starIcon.classList.toggle('text-dark-200', btnRating > rating);
                    starIcon.classList.toggle('dark:text-dark-700', btnRating > rating);
                });
            });
        });

        // AI Feature Integration
        function handleAiAction(btnId, endpoint, promptMessage = null) {
            document.getElementById(btnId)?.addEventListener('click', function() {
                let data = {};
                if (promptMessage) {
                    const question = prompt(promptMessage);
                    if (!question?.trim()) return;
                    data = { question: question };
                }
                triggerAiRequest(`/ai/material/${materialId}/${endpoint}`, data);
            });
        }

        handleAiAction('btn-summarize', 'summarize');
        handleAiAction('btn-keypoints', 'keypoints');
        handleAiAction('btn-ask', 'ask', 'What would you like to know about this document?');

        function triggerAiRequest(url, bodyData) {
            const outputDiv = document.getElementById('ai-output');
            const loadingDiv = document.getElementById('ai-loading');
            const responseDiv = document.getElementById('ai-response');
            
            outputDiv.classList.remove('hidden');
            loadingDiv.classList.remove('hidden');
            responseDiv.innerHTML = '';
            
            // Smooth scroll to AI box
            outputDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(bodyData)
            })
            .then(response => response.json())
            .then(data => {
                loadingDiv.classList.add('hidden');
                
                if (data.error) {
                    responseDiv.innerHTML = `<div class="text-red-500 flex items-start gap-2"><i class="fas fa-exclamation-triangle mt-1"></i> <span>${data.error}</span></div>`;
                } else if (data.summary) {
                    responseDiv.innerHTML = `<h4 class="font-bold text-white mb-2 pb-2 border-b border-dark-700"><i class="fas fa-align-left mr-2 text-primary-400"></i>Summary</h4><p class="leading-relaxed">${data.summary.replace(/\n/g, '<br>')}</p>`;
                } else if (data.keypoints) {
                    responseDiv.innerHTML = `<h4 class="font-bold text-white mb-2 pb-2 border-b border-dark-700"><i class="fas fa-list-ul mr-2 text-amber-400"></i>Key Concepts</h4><div class="leading-relaxed pl-4">${data.keypoints.replace(/\n/g, '<br>')}</div>`;
                } else if (data.answer) {
                    responseDiv.innerHTML = `<h4 class="font-bold text-white mb-2 pb-2 border-b border-dark-700"><i class="fas fa-comment-dots mr-2 text-primary-400"></i>Answer</h4><p class="leading-relaxed">${data.answer.replace(/\n/g, '<br>')}</p>`;
                }
            })
            .catch(error => {
                loadingDiv.classList.add('hidden');
                responseDiv.innerHTML = `<div class="text-red-500 flex items-start gap-2"><i class="fas fa-plug mt-1"></i> <span>Failed to connect to AI service. Please try again later.</span></div>`;
            });
        }
    });
</script>
@endpush
