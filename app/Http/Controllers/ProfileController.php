<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Institution;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $institutions = Institution::where('is_active', true)->orderBy('name')->get();
        return view('profile', compact('user', 'institutions'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . auth()->id(),
            'phone' => 'nullable|string|max:20',
        ]);

        auth()->user()->update($request->only('first_name', 'last_name', 'email', 'phone'));
        return back()->with('success', 'Personal information updated successfully.');
    }

    public function updateAcademic(Request $request)
    {
        $request->validate([
            'institution_id' => 'required|exists:institutions,id',
            'department' => 'nullable|string|max:255',
        ]);

        auth()->user()->update($request->only('institution_id', 'department'));
        return back()->with('success', 'Academic information updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Current password does not match']);
        }

        auth()->user()->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password updated successfully.');
    }
}


