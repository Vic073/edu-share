<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KycSubmission;
use App\Models\Material;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'pending_kyc' => KycSubmission::where('status', 'pending')->count(),
            'pending_materials' => Material::where('status', 'pending')->count(),
            'total_materials' => Material::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function kycList()
    {
        $submissions = KycSubmission::with('user')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.kyc', compact('submissions'));
    }

    public function kycApprove($id)
    {
        $submission = KycSubmission::findOrFail($id);
        $submission->update([
            'status' => 'approved',
            'reviewed_by' => Auth::id(),
        ]);

        $submission->user->update(['kyc_status' => 'verified']);

        return back()->with('success', 'KYC Approved successfully.');
    }

    public function kycReject(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string']);
        
        $submission = KycSubmission::findOrFail($id);
        $submission->update([
            'status' => 'rejected',
            'reviewed_by' => Auth::id(),
            'rejection_reason' => $request->reason,
        ]);

        $submission->user->update(['kyc_status' => 'rejected']);

        return back()->with('success', 'KYC Rejected.');
    }

    public function materialsList()
    {
        $materials = Material::with(['user', 'institution'])->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.materials', compact('materials'));
    }

    public function materialApprove($id)
    {
        $material = Material::findOrFail($id);
        $material->update(['status' => 'approved']);
        return back()->with('success', 'Material Approved successfully.');
    }

    public function materialReject($id)
    {
        $material = Material::findOrFail($id);
        $material->update(['status' => 'rejected']);
        return back()->with('success', 'Material Rejected.');
    }
}
