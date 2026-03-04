<!-- resources/views/notifications/index.blade.php -->
@extends('layouts.app')

@section('title', 'Notifications - EduShare')

@section('content')
<div class="container py-5" style="margin-top: 70px;">
    <div class="row">
        <div class="col-md-3">
            <!-- Notification Navigation Sidebar -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Notifications</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('notifications') }}" class="list-group-item list-group-item-action {{ request()->segment(1) == 'notifications' && !request()->has('type') ? 'active' : '' }}">
                        <i class="fas fa-bell me-2"></i> All Notifications
                        @if($unreadCount > 0)
                            <span class="badge bg-danger rounded-pill float-end">{{ $unreadCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('notifications', ['type' => 'comments']) }}" class="list-group-item list-group-item-action {{ request()->query('type') == 'comments' ? 'active' : '' }}">
                        <i class="fas fa-comment-alt me-2"></i> Comments
                        @if($unreadCommentCount > 0)
                            <span class="badge bg-danger rounded-pill float-end">{{ $unreadCommentCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('notifications', ['type' => 'downloads']) }}" class="list-group-item list-group-item-action {{ request()->query('type') == 'downloads' ? 'active' : '' }}">
                        <i class="fas fa-download me-2"></i> Downloads
                        @if($unreadDownloadCount > 0)
                            <span class="badge bg-danger rounded-pill float-end">{{ $unreadDownloadCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('notifications', ['type' => 'ratings']) }}" class="list-group-item list-group-item-action {{ request()->query('type') == 'ratings' ? 'active' : '' }}">
                        <i class="fas fa-star me-2"></i> Ratings
                        @if($unreadRatingCount > 0)
                            <span class="badge bg-danger rounded-pill float-end">{{ $unreadRatingCount }}</span>
                        @endif
                    </a>
                </div>
                <div class="card-footer bg-white">
                    <form action="{{ route('notifications.markAllRead') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-primary w-100">
                            <i class="fas fa-check-double me-1"></i> Mark All as Read
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <!-- Notification Content -->
            <div class="card shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        @if(request()->query('type') == 'comments')
                            Comment Notifications
                        @elseif(request()->query('type') == 'downloads')
                            Download Notifications
                        @elseif(request()->query('type') == 'ratings')
                            Rating Notifications
                        @else
                            All Notifications
                        @endif
                    </h5>
                    <div class="d-flex">
                        <div class="dropdown me-2">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item {{ request()->query('filter') == 'unread' ? 'active' : '' }}" href="{{ route('notifications', ['type' => request()->query('type'), 'filter' => 'unread']) }}">Unread Only</a></li>
                                <li><a class="dropdown-item {{ request()->query('filter') == 'read' ? 'active' : '' }}" href="{{ route('notifications', ['type' => request()->query('type'), 'filter' => 'read']) }}">Read Only</a></li>
                                <li><a class="dropdown-item {{ !request()->query('filter') ? 'active' : '' }}" href="{{ route('notifications', ['type' => request()->query('type')]) }}">All</a></li>
                            </ul>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-sort me-1"></i> Sort
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item {{ request()->query('sort') == 'newest' || !request()->query('sort') ? 'active' : '' }}" href="{{ route('notifications', ['type' => request()->query('type'), 'filter' => request()->query('filter'), 'sort' => 'newest']) }}">Newest First</a></li>
                                <li><a class="dropdown-item {{ request()->query('sort') == 'oldest' ? 'active' : '' }}" href="{{ route('notifications', ['type' => request()->query('type'), 'filter' => request()->query('filter'), 'sort' => 'oldest']) }}">Oldest First</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="list-group list-group-flush">
                    @forelse($notifications as $notification)
                        <div class="list-group-item list-group-item-action py-3 px-4 {{ $notification->read_at ? '' : 'unread-notification bg-light' }}">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <!-- Notification Icon -->
                                    @if($notification->type == 'App\Notifications\NewComment')
                                        <span class="notification-icon bg-info text-white">
                                            <i class="fas fa-comment-alt"></i>
                                        </span>
                                    @elseif($notification->type == 'App\Notifications\NewDownload')
                                        <span class="notification-icon bg-success text-white">
                                            <i class="fas fa-download"></i>
                                        </span>
                                    @elseif($notification->type == 'App\Notifications\NewRating')
                                        <span class="notification-icon bg-warning text-white">
                                            <i class="fas fa-star"></i>
                                        </span>
                                    @endif
                                    
                                    <!-- Notification Content -->
                                    <div class="ms-3">
                                        <h6 class="mb-1">
                                            @if($notification->type == 'App\Notifications\NewComment')
                                                New comment on <strong>{{ $notification->data['material_title'] }}</strong>
                                            @elseif($notification->type == 'App\Notifications\NewDownload')
                                                Your material <strong>{{ $notification->data['material_title'] }}</strong> was downloaded
                                            @elseif($notification->type == 'App\Notifications\NewRating')
                                                New {{ $notification->data['rating'] }}-star rating on <strong>{{ $notification->data['material_title'] }}</strong>
                                            @endif
                                        </h6>
                                        
                                        <p class="mb-1 text-muted small">
                                            @if($notification->type == 'App\Notifications\NewComment')
                                                {{ $notification->data['user_name'] }}: "{{ Str::limit($notification->data['comment'], 100) }}"
                                            @elseif($notification->type == 'App\Notifications\NewDownload')
                                                Downloaded by {{ $notification->data['user_name'] }}
                                            @elseif($notification->type == 'App\Notifications\NewRating')
                                                Rated by {{ $notification->data['user_name'] }}
                                            @endif
                                        </p>
                                        
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</small>
                                    </div>
                                </div>
                                
                                <div class="d-flex">
                                    <a href="{{ route('materials.view', $notification->data['material_id']) }}" class="btn btn-sm btn-outline-primary me-2">
                                        View
                                    </a>
                                    
                                    @if(!$notification->read_at)
                                        <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                Mark Read
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                            <h5>No notifications found</h5>
                            <p class="text-muted">You don't have any notifications in this category right now.</p>
                        </div>
                    @endforelse
                </div>
                
                <!-- Pagination -->
                @if($notifications->count() > 0)
                    <div class="card-footer bg-white">
                        {{ $notifications->appends(['type' => request()->query('type'), 'filter' => request()->query('filter'), 'sort' => request()->query('sort')])->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .notification-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
    }
    
    .unread-notification {
        border-left: 4px solid #0d6efd;
    }
    
    .list-group-item.active {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
</style>
@endsection