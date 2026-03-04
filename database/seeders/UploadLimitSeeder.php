<?php

namespace Database\Seeders;

use App\Models\UploadLimit;
use Illuminate\Database\Seeder;

class UploadLimitSeeder extends Seeder
{
    public function run()
    {
        UploadLimit::create([
            'role' => 'student',
            'monthly_limit' => 10, // Students can upload 10 files per month
        ]);
        
        UploadLimit::create([
            'role' => 'lecturer',
            'monthly_limit' => 50, // Lecturers can upload 50 files per month
        ]);
    }
}