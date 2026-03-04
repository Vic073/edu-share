<?php

namespace App\Http\Controllers;
use App\Models\Rating;
use App\Models\Material;
use App\Notifications\NewRating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request, $materialId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $material = Material::findOrFail($materialId);
        
        $rating = Rating::updateOrCreate(
            ['user_id' => auth()->id(), 'material_id' => $materialId],
            ['rating' => $request->rating]
        );
        
        // Send notification to the uploader if this is a new rating or the rating changed
        if ($material->user_id != auth()->id()) {
            $material->user->notify(new NewRating($material, $rating, auth()->user()));
        }
        
        return back()->with('success', 'Rating submitted!');
    }
    
    public function destroy($materialId)
    {
        Rating::where('user_id', auth()->id())
            ->where('material_id', $materialId)
            ->delete();
            
        return back()->with('success', 'Rating removed!');
    }
}