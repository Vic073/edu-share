<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use App\Models\Faculty;
use Illuminate\Http\Request;

class InstitutionController extends Controller
{
    /**
     * Get all active institutions (for registration dropdown).
     */
    public function index()
    {
        $institutions = Institution::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'abbreviation', 'location']);

        return response()->json($institutions);
    }

    /**
     * Get faculties for a specific institution.
     */
    public function faculties(Institution $institution)
    {
        $faculties = $institution->faculties()
            ->orderBy('name')
            ->get(['id', 'name', 'code']);

        return response()->json($faculties);
    }

    /**
     * Get courses for a specific faculty.
     */
    public function courses(Faculty $faculty)
    {
        $courses = $faculty->courses()
            ->orderBy('year_level')
            ->orderBy('name')
            ->get(['id', 'name', 'code', 'year_level']);

        return response()->json($courses);
    }
}
