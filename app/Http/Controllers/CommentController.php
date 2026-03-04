<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Notifications\NewComment; // Correct namespace

class CommentController extends Controller
{
    public function store(Request $request, Material $material)
    {
        $validatedData = $request->validate([
            'comment' => 'required|string|max:1000',
        ]);
        
        $comment = $material->comments()->create([
            'user_id' => auth()->id(),
            'comment' => $validatedData['comment'],
        ]);
        
        // Send notification to the uploader
        if ($material->user_id != auth()->id()) {
            $material->user->notify(new NewComment($material, $comment, auth()->user()));
        }
        
        return back()->with('success', 'Comment added successfully');
    }


    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        if ($comment->user_id !== auth()->id()) {
            abort(403);
        }

        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted.');
    }
}
