<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Computer Science', 'icon' => 'fas fa-laptop-code'],
            ['name' => 'Mathematics', 'icon' => 'fas fa-calculator'],
            ['name' => 'Sciences', 'icon' => 'fas fa-flask'],
            ['name' => 'Humanities', 'icon' => 'fas fa-landmark'],
            ['name' => 'Languages', 'icon' => 'fas fa-globe'],
        ];
        
        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}

