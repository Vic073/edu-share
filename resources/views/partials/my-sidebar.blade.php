<!-- Sidebar -->
<div class="card p-4 mb-4">
    <h5 class="font-semibold text-dark-900 dark:text-white mb-3">Quick Access</h5>
    
    <div class="space-y-1">
        <a href="{{ route('materials.create') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-dark-700 dark:text-dark-300 hover:bg-dark-100 dark:hover:bg-dark-700 transition-colors">
            <i class="fas fa-upload w-5 text-center text-primary-500"></i>
            Upload Material
        </a>
        <a href="{{ route('my-materials') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-dark-700 dark:text-dark-300 hover:bg-dark-100 dark:hover:bg-dark-700 transition-colors">
            <i class="fas fa-folder w-5 text-center text-primary-500"></i>
            My Materials
        </a>
        <a href="{{ route('favorites.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-dark-700 dark:text-dark-300 hover:bg-dark-100 dark:hover:bg-dark-700 transition-colors">
            <i class="fas fa-heart w-5 text-center text-primary-500"></i>
            Saved Materials
        </a>
        <a href="{{ route('notifications') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-dark-700 dark:text-dark-300 hover:bg-dark-100 dark:hover:bg-dark-700 transition-colors">
            <i class="fas fa-bell w-5 text-center text-primary-500"></i>
            Notifications
            @if(auth()->user()->unreadNotifications->count() > 0)
                <span class="ml-auto w-5 h-5 flex items-center justify-center text-[10px] font-bold text-white bg-red-500 rounded-full">
                    {{ auth()->user()->unreadNotifications->count() > 9 ? '9+' : auth()->user()->unreadNotifications->count() }}
                </span>
            @endif
        </a>
        <a href="{{ route('profile') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-dark-700 dark:text-dark-300 hover:bg-dark-100 dark:hover:bg-dark-700 transition-colors">
            <i class="fas fa-user w-5 text-center text-primary-500"></i>
            Profile
        </a>
    </div>
</div>

<!-- Institution Info -->
@if(auth()->user()->institution)
<div class="card p-4">
    <h6 class="font-semibold text-dark-900 dark:text-white mb-2">
        <i class="fas fa-university mr-1 text-primary-500"></i> Your Institution
    </h6>
    <p class="text-dark-700 dark:text-dark-300 text-sm mb-1">{{ auth()->user()->institution->name }}</p>
    <p class="text-dark-400 dark:text-dark-500 text-xs mb-2">{{ auth()->user()->institution->location }}</p>
    <div>
        <span class="badge {{ auth()->user()->subscription_tier === 'premium' ? 'badge-gold' : 'badge-primary' }}">
            <i class="fas fa-crown mr-1"></i>{{ ucfirst(auth()->user()->subscription_tier ?? 'free') }} Plan
        </span>
    </div>
</div>
@endif
