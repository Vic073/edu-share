<?php
// app/Http/Controllers/NotificationController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = $user->notifications();
        
        // Filter by type
        if ($request->type == 'comments') {
            $query->where('type', 'App\Notifications\NewComment');
        } elseif ($request->type == 'downloads') {
            $query->where('type', 'App\Notifications\NewDownload');
        } elseif ($request->type == 'ratings') {
            $query->where('type', 'App\Notifications\NewRating');
        }
        
        // Filter by read/unread status
        if ($request->filter == 'unread') {
            $query->whereNull('read_at');
        } elseif ($request->filter == 'read') {
            $query->whereNotNull('read_at');
        }
        
        // Sort by date
        if ($request->sort == 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }
        
        // Paginate the results
        $notifications = $query->paginate(10);
        
        // Count unread notifications
        $unreadCount = $user->unreadNotifications->count();
        $unreadCommentCount = $user->unreadNotifications->where('type', 'App\Notifications\NewComment')->count();
        $unreadDownloadCount = $user->unreadNotifications->where('type', 'App\Notifications\NewDownload')->count();
        $unreadRatingCount = $user->unreadNotifications->where('type', 'App\Notifications\NewRating')->count();
        
        return view('notifications.index', compact(
            'notifications',
            'unreadCount',
            'unreadCommentCount',
            'unreadDownloadCount',
            'unreadRatingCount'
        ));
    }
    
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        return back()->with('success', 'Notification marked as read');
    }
    
    public function markAllRead(Request $request)
    {
        $user = Auth::user();
        $query = $user->unreadNotifications();
        
        // Filter by type if needed
        if ($request->type == 'comments') {
            $query->where('type', 'App\Notifications\NewComment');
        } elseif ($request->type == 'downloads') {
            $query->where('type', 'App\Notifications\NewDownload');
        } elseif ($request->type == 'ratings') {
            $query->where('type', 'App\Notifications\NewRating');
        }
        
        $query->update(['read_at' => now()]);
        
        return back()->with('success', 'All notifications marked as read');
    }
}