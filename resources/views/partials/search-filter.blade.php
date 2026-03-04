<!-- Search & Filter bar -->
<div class="card mb-6">
    <div class="p-4">
        <form method="GET" action="{{ route('materials') }}">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 items-end">
                <!-- Search -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-dark-700 dark:text-dark-300 mb-1">Search</label>
                    <input type="text" 
                           name="title" 
                           placeholder="Search materials..." 
                           value="{{ request('title') }}"
                           class="input">
                </div>

                <!-- Course -->
                <div>
                    <label class="block text-sm font-medium text-dark-700 dark:text-dark-300 mb-1">Course</label>
                    <input type="text" 
                           name="course" 
                           placeholder="e.g. COM211" 
                           value="{{ request('course') }}"
                           class="input">
                </div>

                <!-- File Type -->
                <div>
                    <label class="block text-sm font-medium text-dark-700 dark:text-dark-300 mb-1">Type</label>
                    <select name="type" class="input">
                        <option value="">All types</option>
                        <option value="pdf" {{ request('type') == 'pdf' ? 'selected' : '' }}>PDF</option>
                        <option value="doc" {{ request('type') == 'doc' ? 'selected' : '' }}>Word</option>
                        <option value="ppt" {{ request('type') == 'ppt' ? 'selected' : '' }}>PowerPoint</option>
                        <option value="zip" {{ request('type') == 'zip' ? 'selected' : '' }}>ZIP</option>
                    </select>
                </div>

                <!-- Sort -->
                <div>
                    <label class="block text-sm font-medium text-dark-700 dark:text-dark-300 mb-1">Sort</label>
                    <select name="sort" class="input">
                        <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                        <option value="az" {{ request('sort') == 'az' ? 'selected' : '' }}>A → Z</option>
                        <option value="za" {{ request('sort') == 'za' ? 'selected' : '' }}>Z → A</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex gap-2">
                    <button type="submit" class="btn-primary flex-1">
                        <i class="fas fa-filter mr-1"></i>Filter
                    </button>
                    @if(request()->has('title') || request()->has('course') || request()->has('type'))
                        <a href="{{ route('materials') }}" class="btn-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
