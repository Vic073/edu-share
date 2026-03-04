<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Material;
use App\Models\Institution;
use App\Models\Comment;
use App\Models\Rating;
use App\Notifications\NewDownload;
use App\Notifications\NewRating;

class MaterialController extends Controller
{
    public function create()
    {
        $institutions = Institution::where('is_active', true)->orderBy('name')->get();
        return view('materials.upload', compact('institutions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'course_code' => 'required|string',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,txt,pptx,zip|max:512000',
            'visibility' => 'required|in:public,private',
            'institution_id' => 'nullable|exists:institutions,id',
            'faculty_id' => 'nullable|exists:faculties,id',
            'course_id' => 'nullable|exists:courses,id',
        ]);

        try {
            $file = $request->file('file');
            $path = $file->store('materials', 'public');
            
            $userRole = auth()->user()->role ?? 'user';
            
            Material::create([
                'user_id' => auth()->id(),
                'title' => $request->title,
                'course_code' => $request->course_code,
                'description' => $request->description,
                'file_path' => $path,
                'file_type' => $file->getClientOriginalExtension(),
                'file_size' => $file->getSize(),
                'visibility' => $request->visibility,
                'uploader_role' => $userRole,
                'institution_id' => $request->institution_id ?? auth()->user()->institution_id,
                'faculty_id' => $request->faculty_id,
                'course_id' => $request->course_id,
            ]);

            return redirect()->route('my-materials')->with('success', 'File uploaded successfully!');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['file' => 'Upload failed: ' . $e->getMessage()]);
        }
    }
    

    public function show($id)
    {
        $material = Material::findOrFail($id);

        if ($material->visibility === 'private' && $material->user_id !== auth()->id()) {
            abort(403);
        }

        if ($material->user_id != auth()->id()) {
            $material->user->notify(new NewDownload($material, auth()->user()));
        }

        return view('materials.show', compact('material'));
    }

    public function destroy($id)
    {
        $material = Material::findOrFail($id);

        if ($material->user_id !== auth()->id()) {
            abort(403);
        }

        Storage::disk('public')->delete($material->file_path);
        $material->delete();

        return redirect()->back()->with('success', 'Material deleted successfully.');
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Material::with(['ratings', 'institution'])->where('status', 'approved');

        // Institution-based filtering for free tier
        if (!$user->isPremium() && $user->institution_id) {
            // Free users: full access to own institution, preview for others
            if (!$request->filled('institution')) {
                $query->where('institution_id', $user->institution_id);
            }
        }

        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->filled('course')) {
            $query->where('course_code', $request->course);
        }

        if ($request->filled('institution')) {
            $query->where('institution_id', $request->institution);
        }

        if ($request->has('file_type') && $request->file_type !== '') {
            $fileTypes = explode(',', $request->file_type);
            $query->where(function ($q) use ($fileTypes) {
                foreach ($fileTypes as $type) {
                    $q->orWhere('file_type', $type);
                }
            });
        }

        if ($request->filled('uploader')) {
            $query->where('uploader_role', $request->uploader);
        }

        switch ($request->sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'az':
                $query->orderBy('title', 'asc');
                break;
            case 'za':
                $query->orderBy('title', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $materials = $query->paginate(9);
        $institutions = Institution::where('is_active', true)->orderBy('name')->get();

        return view('materials', compact('materials', 'institutions'));
    }

    public function view($id)
    {
        $material = Material::with(['ratings', 'comments.user', 'institution'])->findOrFail($id);

        $averageRating = round($material->ratings->avg('rating'), 1);
        $userRating = $material->ratings->where('user_id', auth()->id())->first()->rating ?? null;

        return view('materials.view', compact('material', 'averageRating', 'userRating'));
    }

    public function download($id)
    {
        $material = Material::findOrFail($id);

        if ($material->visibility === 'private' && $material->user_id !== auth()->id()) {
            abort(403, 'Unauthorized download attempt.');
        }

        // Premium check — free users can't download from other institutions
        $user = auth()->user();
        if (!$user->isPremium() && $material->institution_id !== $user->institution_id) {
            return back()->with('error', 'Upgrade to Premium to download materials from other institutions.');
        }

        \App\Models\Download::create([
            'user_id' => auth()->id(),
            'material_id' => $material->id,
        ]);

        $material->increment('download_count');

        if ($material->user_id !== auth()->id()) {
            $material->user->notify(new \App\Notifications\NewDownload($material, auth()->user()));
        }

        $path = Storage::disk('public')->path($material->file_path);
        return response()->download($path, $material->title . '.' . $material->file_type);
    }

    public function removeRating(Material $material)
    {
        $material->ratings()->where('user_id', auth()->id())->delete();
        return back()->with('success', 'Your rating has been removed.');
    }
}