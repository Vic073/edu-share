<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\UploadLimit;
use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $user = auth()->user();

        // Recent materials — filtered by user's institution for free users
        $recentQuery = Material::where('visibility', 'public')
            ->where('status', 'approved');

        if (!$user->isPremium() && $user->institution_id) {
            $recentQuery->where('institution_id', $user->institution_id);
        }

        $recentMaterials = $recentQuery->latest()->take(10)->get();

        // User's own uploads
        $materials = $user->materials()->latest()->take(3)->get();

        // Stats (cached)
        $totalUploads = Cache::remember('user_uploads_' . $user->id, 3600, function () use ($user) {
            return $user->materials()->count();
        });
        $totalDownloads = Cache::remember('user_downloads_' . $user->id, 3600, function () use ($user) {
            return $user->materials()->sum('download_count');
        });
        $avgRating = Cache::remember('user_avg_rating_' . $user->id, 3600, function () use ($user) {
            return $user->materials()->withAvg('ratings', 'rating')->get()->avg('ratings_avg_rating') ?: '0.0';
        });
        $totalComments = Cache::remember('user_comments_' . $user->id, 3600, function () use ($user) {
            return $user->materials()->withCount('comments')->get()->sum('comments_count') ?: 0;
        });

        return view('dashboard', compact(
            'recentMaterials',
            'materials',
            'totalUploads',
            'totalDownloads',
            'avgRating',
            'totalComments'
        ));
    }
    
}