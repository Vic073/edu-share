<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;

class FavoriteController extends Controller
{
    

public function toggle($id)
{
    $material = Material::findOrFail($id);
    $user = auth()->user();

    if ($user->favorites()->where('material_id', $id)->exists()) {
        $user->favorites()->detach($id);
        return back()->with('success', 'Material removed from favorites.');
    } else {
        $user->favorites()->attach($id);
        return back()->with('success', 'Material added to favorites.');
    }
}

public function index()
{
    $favorites = auth()->user()->favorites()->latest()->paginate(10);
    return view('favorites.index', compact('favorites'));
}

}
