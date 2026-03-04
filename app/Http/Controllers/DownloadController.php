<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Material;

class DownloadController extends Controller
{
    public function download($id)
    {
        $material = Material::findOrFail($id);

        // If private, check access
        if ($material->visibility === 'private' && $material->user_id !== auth()->id()) {
            abort(403, 'Unauthorized download attempt.');
        }

        // Create download record
        \App\Models\Download::create([
            'user_id' => auth()->id(),
            'material_id' => $material->id,
        ]);

        // Track download count
        $material->increment('download_count');

        // Send notification to uploader (if not the same user)
        if ($material->user_id !== auth()->id()) {
            $material->user->notify(new \App\Notifications\NewDownload($material, auth()->user()));
        }

        $path = Storage::disk('public')->path($material->file_path);
        return response()->download($path, $material->title . '.' . $material->file_type);
    }
}
