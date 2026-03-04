<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MyMaterialsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        // Uploaded materials by the user
        $query = $user->materials()->latest();

        if ($request->filled('course')) {
            $query->where('course_code', $request->course);
        }

        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'downloads':
                    $query->orderByDesc('download_count');
                    break;
                case 'rating':
                    $query->withAvg('ratings', 'rating')->orderByDesc('ratings_avg_rating');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        }

        $materials = $query->paginate(10);

        // Downloaded materials by user
        $downloadedMaterials = $user->downloadedMaterials()
            ->withPivot('created_at')
            ->orderByPivot('created_at', 'desc')
            ->take(5)
            ->get();

        // Stats
        $totalUploads = $user->materials()->count();
        $totalDownloads = $user->materials()->sum('download_count'); // Downloads of user's materials
        $avgRating = $user->materials()->withAvg('ratings', 'rating')->get()->avg('ratings_avg_rating') ?: '0.0';
        $totalComments = $user->materials()->withCount('comments')->get()->sum('comments_count') ?: 0;


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

        return view('my-materials', compact(
            'materials',
            'downloadedMaterials',
            'totalUploads',
            'totalDownloads',
            'avgRating',
            'totalComments'
        ));
    }
}