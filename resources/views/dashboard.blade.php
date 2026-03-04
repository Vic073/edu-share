@extends('layouts.app')

@section('title', 'Dashboard — EduShare')

@section('content')

{{-- Hero Section --}}
<div class="relative min-h-[45vh] flex items-end overflow-hidden">
    {{-- Background --}}
    <div class="absolute inset-0 bg-gradient-to-br from-dark-900 via-dark-800 to-primary-900/40">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-primary-500/10 rounded-full -translate-y-1/2 translate-x-1/3 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-[350px] h-[350px] bg-primary-600/10 rounded-full translate-y-1/2 -translate-x-1/3 blur-3xl pointer-events-none"></div>
    </div>

    {{-- Hero Content --}}
    <div class="relative z-10 w-full px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-7xl mx-auto">
            <div class="animate-fade-in">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-3">
                    Welcome back, {{ auth()->user()->first_name }} 👋
                </h1>

                <div class="flex flex-wrap items-center gap-3 mb-6">
                    <p class="text-dark-300">
                        {{ auth()->user()->institution->name ?? 'No Institution' }}
                    </p>
                    @if(auth()->user()->subscription_tier === 'premium')
                        <span class="badge badge-gold">
                            <i class="fas fa-crown"></i> Premium
                        </span>
                    @else
                        <span class="badge bg-dark-700 text-dark-300">Free Plan</span>
                    @endif
                </div>

                {{-- Quick Stats --}}
                <div class="flex flex-wrap gap-6 text-dark-300 text-sm mb-8">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-file-upload text-primary-400"></i>
                        <span><strong class="text-white text-base">{{ $totalUploads ?? 0 }}</strong> Uploads</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-download text-primary-400"></i>
                        <span><strong class="text-white text-base">{{ $totalDownloads ?? 0 }}</strong> Downloads</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-star text-primary-400"></i>
                        <span><strong class="text-white text-base">{{ $avgRating ?? '0.0' }}</strong> Avg Rating</span>
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('materials.create') }}" class="btn-primary">
                        <i class="fas fa-cloud-upload-alt"></i> Upload Material
                    </a>
                    <a href="{{ route('materials') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white rounded-lg font-medium transition-colors border border-white/20">
                        <i class="fas fa-compass"></i> Browse Materials
                    </a>
                    @if(auth()->user()->subscription_tier !== 'premium')
                    <a href="{{ route('pricing') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white rounded-lg font-medium transition-all hover:scale-105">
                        <i class="fas fa-crown"></i> Go Premium
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Main Content --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="space-y-12">

        {{-- Activity Stats --}}
        <section>
            <h2 class="content-row-title mb-5">Your Activity</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach([
                    ['label'=>'Uploads',    'value'=>$totalUploads ?? 0,    'icon'=>'file-upload',   'from'=>'from-blue-500',   'to'=>'to-blue-600'],
                    ['label'=>'Downloads',  'value'=>$totalDownloads ?? 0,  'icon'=>'download',      'from'=>'from-green-500',  'to'=>'to-green-600'],
                    ['label'=>'Avg Rating', 'value'=>$avgRating ?? '0.0',   'icon'=>'star',          'from'=>'from-amber-500',  'to'=>'to-amber-600'],
                    ['label'=>'Comments',   'value'=>$totalComments ?? 0,   'icon'=>'comment',       'from'=>'from-purple-500', 'to'=>'to-purple-600'],
                ] as $stat)
                <div class="card p-5">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br {{ $stat['from'] }} {{ $stat['to'] }} flex items-center justify-center text-white flex-shrink-0">
                            <i class="fas fa-{{ $stat['icon'] }}"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-dark-900 dark:text-white">{{ $stat['value'] }}</p>
                            <p class="text-xs text-dark-500 dark:text-dark-400">{{ $stat['label'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

        {{-- My Recent Uploads --}}
        @if(isset($materials) && $materials->count() > 0)
        <section class="content-row">
            <div class="flex items-center justify-between mb-5">
                <h2 class="content-row-title">My Recent Uploads</h2>
                <a href="{{ route('my-materials') }}" class="text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400 font-medium">
                    View All <i class="fas fa-arrow-right ml-1 text-xs"></i>
                </a>
            </div>
            <div class="content-scroller">
                @foreach($materials->take(8) as $material)
                <div class="material-card">
                    <div class="material-card-cover">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <i class="fas fa-file-{{ getFileIcon($material->file_type) }} text-4xl text-dark-400 dark:text-dark-500"></i>
                        </div>
                        <div class="material-card-overlay"></div>
                        <div class="absolute bottom-2 left-2 right-2">
                            <span class="badge badge-primary text-[10px]">{{ $material->course_code }}</span>
                        </div>
                    </div>
                    <div class="p-3">
                        <h3 class="font-medium text-dark-900 dark:text-white text-xs truncate mb-1">
                            <a href="{{ route('materials.view', $material->id) }}" class="hover:text-primary-600 dark:hover:text-primary-400">
                                {{ $material->title }}
                            </a>
                        </h3>
                        <div class="flex items-center justify-between text-[10px] text-dark-500 dark:text-dark-400">
                            <span><i class="fas fa-download mr-0.5"></i>{{ $material->download_count }}</span>
                            <span>{{ $material->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- Latest from Community --}}
        <section class="content-row">
            <div class="flex items-center justify-between mb-5">
                <h2 class="content-row-title">
                    Latest from
                    @if(auth()->user()->isPremium())
                        All Universities
                    @else
                        {{ auth()->user()->institution->abbreviation ?? 'Your University' }}
                    @endif
                </h2>
                <a href="{{ route('materials') }}" class="text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400 font-medium">
                    Browse All <i class="fas fa-arrow-right ml-1 text-xs"></i>
                </a>
            </div>
            <div class="content-scroller">
                @forelse(isset($recentMaterials) ? $recentMaterials->take(12) : [] as $material)
                <div class="material-card">
                    {{-- Favorite button --}}
                    <form action="{{ route('favorites.toggle', $material->id) }}" method="POST" class="absolute top-2 right-2 z-10">
                        @csrf
                        <button type="submit"
                                class="w-7 h-7 flex items-center justify-center rounded-full transition-colors
                                {{ auth()->user()->favorites->contains($material) ? 'bg-red-500 text-white' : 'bg-black/40 text-white hover:bg-black/60' }}"
                                title="{{ auth()->user()->favorites->contains($material) ? 'Remove from saved' : 'Save material' }}">
                            <i class="fas fa-heart text-[10px]"></i>
                        </button>
                    </form>

                    <div class="material-card-cover">
                        <div class="absolute inset-0 flex items-center justify-center bg-dark-200 dark:bg-dark-700">
                            <i class="fas fa-file-{{ getFileIcon($material->file_type) }} text-4xl text-dark-400 dark:text-dark-500"></i>
                        </div>
                        <div class="material-card-overlay"></div>
                        <div class="absolute bottom-2 left-2 right-2 flex flex-wrap gap-1">
                            <span class="badge badge-primary text-[9px]">{{ $material->course_code }}</span>
                            @if($material->institution)
                                <span class="badge bg-dark-700/80 text-dark-200 text-[9px]">{{ $material->institution->abbreviation }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="p-3">
                        <h3 class="font-medium text-dark-900 dark:text-white text-xs truncate">
                            <a href="{{ route('materials.view', $material->id) }}" class="hover:text-primary-600 dark:hover:text-primary-400">
                                {{ $material->title }}
                            </a>
                        </h3>
                        <div class="flex items-center justify-between mt-2">
                            <div class="flex items-center gap-0.5">
                                @php $avg = round($material->ratings->avg('rating'), 1); @endphp
                                @for($i=1;$i<=5;$i++)
                                    <i class="fas fa-star text-[8px]" style="color: {{ $i <= $avg ? '#f59e0b' : '#4b5563' }};"></i>
                                @endfor
                            </div>
                            <a href="{{ route('materials.download', $material->id) }}"
                               class="text-[10px] text-primary-600 hover:text-primary-700 dark:text-primary-400 font-medium">
                                <i class="fas fa-download mr-0.5"></i>Get
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="flex-1 py-16 text-center min-w-[300px]">
                    <i class="fas fa-inbox text-4xl text-dark-300 dark:text-dark-600 mb-3"></i>
                    <p class="text-dark-500 dark:text-dark-400">No materials available yet</p>
                    <a href="{{ route('materials.create') }}" class="btn-primary mt-4 inline-flex">
                        <i class="fas fa-upload"></i> Be the first to upload
                    </a>
                </div>
                @endforelse
            </div>
        </section>

        {{-- KYC Warning --}}
        @if(auth()->user()->kyc_status !== 'verified')
        <div class="alert-warning">
            <i class="fas fa-exclamation-triangle text-amber-500 mt-0.5 flex-shrink-0"></i>
            <div class="flex-1">
                <p class="font-medium text-amber-800 dark:text-amber-200 text-sm">Complete Identity Verification</p>
                <p class="text-xs text-amber-700 dark:text-amber-300 mt-0.5">Verify your identity to unlock all features and increase your upload limit.</p>
            </div>
            <a href="{{ route('kyc.submit') }}" class="flex-shrink-0 text-xs bg-amber-600 hover:bg-amber-700 text-white px-3 py-1.5 rounded-lg transition-colors">
                Verify Now
            </a>
        </div>
        @endif

    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Staggered card animations
    document.querySelectorAll('.material-card').forEach((card, i) => {
        card.style.opacity = '0';
        card.style.animation = `fadeIn .4s ease-out ${i * 0.06}s forwards`;
    });
});
</script>
@endpush
