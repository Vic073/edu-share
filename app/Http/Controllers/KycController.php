<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KycSubmission;
use Illuminate\Support\Facades\Auth;

class KycController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        $user = Auth::user();
        if ($user->kyc_status === 'verified') {
            return redirect()->route('student.dashboard')->with('success', 'Your identity is already verified.');
        }

        $existingSubmission = KycSubmission::where('user_id', $user->id)->latest()->first();

        return view('kyc.submit', compact('existingSubmission'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'document_type' => 'required|in:student_id,national_id',
            'document' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        $user = Auth::user();

        $path = $request->file('document')->store('kyc_documents', 'public');

        KycSubmission::create([
            'user_id' => $user->id,
            'document_type' => $request->document_type,
            'document_path' => $path,
            'status' => 'pending',
        ]);

        $user->update(['kyc_status' => 'pending']);

        return redirect()->back()->with('success', 'KYC document submitted successfully. Please wait for admin approval.');
    }
}
