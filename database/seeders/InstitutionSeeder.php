<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Institution;
use App\Models\Faculty;
use App\Models\Course;

class InstitutionSeeder extends Seeder
{
    public function run()
    {
        $institutions = [
            [
                'name' => 'Domasi College of Education',
                'abbreviation' => 'DCE',
                'location' => 'Zomba',
                'faculties' => [
                    [
                        'name' => 'Faculty of Science & Technology',
                        'code' => 'FST',
                        'courses' => [
                            ['name' => 'Introduction to Computer Science', 'code' => 'COM121', 'year_level' => 1],
                            ['name' => 'Introduction to Programming', 'code' => 'COM211', 'year_level' => 2],
                            ['name' => 'Database Systems', 'code' => 'COM322', 'year_level' => 3],
                            ['name' => 'Software Engineering', 'code' => 'COM321', 'year_level' => 3],
                            ['name' => 'Calculus I', 'code' => 'MAT211', 'year_level' => 2],
                            ['name' => 'Discrete Mathematics', 'code' => 'MAT222', 'year_level' => 2],
                            ['name' => 'Statistics', 'code' => 'MAT313', 'year_level' => 3],
                            ['name' => 'Introduction to Biology', 'code' => 'BIO111', 'year_level' => 1],
                            ['name' => 'General Chemistry', 'code' => 'CHE111', 'year_level' => 1],
                            ['name' => 'Physics Fundamentals', 'code' => 'PHY111', 'year_level' => 1],
                        ],
                    ],
                    [
                        'name' => 'Faculty of Humanities',
                        'code' => 'FH',
                        'courses' => [
                            ['name' => 'Introduction to English', 'code' => 'ENG111', 'year_level' => 1],
                            ['name' => 'African Literature', 'code' => 'ENG221', 'year_level' => 2],
                            ['name' => 'History of Malawi', 'code' => 'HIS111', 'year_level' => 1],
                        ],
                    ],
                    [
                        'name' => 'Faculty of Education',
                        'code' => 'FE',
                        'courses' => [
                            ['name' => 'Foundations of Education', 'code' => 'EDU111', 'year_level' => 1],
                            ['name' => 'Curriculum Studies', 'code' => 'EDU221', 'year_level' => 2],
                            ['name' => 'Educational Psychology', 'code' => 'EDU212', 'year_level' => 2],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'University of Malawi',
                'abbreviation' => 'UNIMA',
                'location' => 'Zomba',
                'faculties' => [
                    [
                        'name' => 'Faculty of Science',
                        'code' => 'FS',
                        'courses' => [
                            ['name' => 'Computer Science I', 'code' => 'CSC101', 'year_level' => 1],
                            ['name' => 'Data Structures', 'code' => 'CSC201', 'year_level' => 2],
                            ['name' => 'Mathematics I', 'code' => 'MTH101', 'year_level' => 1],
                        ],
                    ],
                    [
                        'name' => 'Faculty of Law',
                        'code' => 'FL',
                        'courses' => [
                            ['name' => 'Constitutional Law', 'code' => 'LAW101', 'year_level' => 1],
                            ['name' => 'Criminal Law', 'code' => 'LAW201', 'year_level' => 2],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Mzuzu University',
                'abbreviation' => 'MZUNI',
                'location' => 'Mzuzu',
                'faculties' => [
                    [
                        'name' => 'Faculty of Information Science & Communications',
                        'code' => 'FISC',
                        'courses' => [
                            ['name' => 'Information Systems', 'code' => 'ISC101', 'year_level' => 1],
                            ['name' => 'Web Development', 'code' => 'ISC202', 'year_level' => 2],
                        ],
                    ],
                    [
                        'name' => 'Faculty of Education',
                        'code' => 'FED',
                        'courses' => [
                            ['name' => 'Teaching Methods', 'code' => 'EDU101', 'year_level' => 1],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Kamuzu University of Health Sciences',
                'abbreviation' => 'KUHeS',
                'location' => 'Lilongwe / Blantyre',
                'faculties' => [
                    [
                        'name' => 'Faculty of Medicine',
                        'code' => 'FM',
                        'courses' => [
                            ['name' => 'Human Anatomy', 'code' => 'MED101', 'year_level' => 1],
                            ['name' => 'Pharmacology', 'code' => 'MED201', 'year_level' => 2],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Lilongwe University of Agriculture and Natural Resources',
                'abbreviation' => 'LUANAR',
                'location' => 'Lilongwe',
                'faculties' => [
                    [
                        'name' => 'Faculty of Agriculture',
                        'code' => 'FA',
                        'courses' => [
                            ['name' => 'Crop Science', 'code' => 'AGR101', 'year_level' => 1],
                            ['name' => 'Soil Science', 'code' => 'AGR201', 'year_level' => 2],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Malawi University of Business and Applied Sciences',
                'abbreviation' => 'MUBAS',
                'location' => 'Blantyre',
                'faculties' => [
                    [
                        'name' => 'Faculty of Applied Sciences',
                        'code' => 'FAS',
                        'courses' => [
                            ['name' => 'Engineering Mathematics', 'code' => 'EMA101', 'year_level' => 1],
                            ['name' => 'Software Development', 'code' => 'SWD201', 'year_level' => 2],
                        ],
                    ],
                    [
                        'name' => 'Faculty of Commerce',
                        'code' => 'FC',
                        'courses' => [
                            ['name' => 'Financial Accounting', 'code' => 'ACC101', 'year_level' => 1],
                            ['name' => 'Business Management', 'code' => 'BUS101', 'year_level' => 1],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($institutions as $instData) {
            $institution = Institution::create([
                'name' => $instData['name'],
                'abbreviation' => $instData['abbreviation'],
                'location' => $instData['location'],
            ]);

            foreach ($instData['faculties'] as $facData) {
                $faculty = Faculty::create([
                    'institution_id' => $institution->id,
                    'name' => $facData['name'],
                    'code' => $facData['code'],
                ]);

                foreach ($facData['courses'] as $courseData) {
                    Course::create([
                        'faculty_id' => $faculty->id,
                        'name' => $courseData['name'],
                        'code' => $courseData['code'],
                        'year_level' => $courseData['year_level'],
                    ]);
                }
            }
        }
    }
}
