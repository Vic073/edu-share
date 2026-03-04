<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ViewController extends Controller
{
    public function show(Material $material)
{
    // Get the user's rating for this material, if any
    $userRating = auth()->user()->ratings()
        ->where('material_id', $material->id)
        ->value('rating') ?? 0;
    
    // Calculate average rating
    $averageRating = round($material->ratings->avg('rating'), 1);
    
    return view('materials.show', compact('material', 'userRating', 'averageRating'));
}
}
