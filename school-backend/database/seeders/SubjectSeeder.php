<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder {
    public function run(): void
    {
        $subjects = [
            ['name' => 'Language Activities', 'category' => 'Early Years'],
            ['name' => 'Mathematical Activities', 'category' => 'Early Years'],
            ['name' => 'Environmental Activities', 'category' => 'Early Years'],
            ['name' => 'Religious Activities', 'category' => 'Early Years'],
            ['name' => 'Psychomotor and Creative Activities', 'category' => 'Early Years'],

            ['name' => 'English', 'category' => 'Languages'],
            ['name' => 'Kiswahili', 'category' => 'Languages'],
            ['name' => 'Kenya Sign Language', 'category' => 'Languages'],
            ['name' => 'Mathematics Activities', 'category' => 'Sciences'],
            ['name' => 'Hygiene and Nutrition Activities', 'category' => 'Health'],
            ['name' => 'Religious Education Activities', 'category' => 'Humanities'],
            ['name' => 'Movement and Creative Activities', 'category' => 'Arts'],
            ['name' => 'Mathematics', 'category' => 'Sciences'],
            ['name' => 'Science and Technology', 'category' => 'Sciences'],
            ['name' => 'Social Studies', 'category' => 'Humanities'],
            ['name' => 'Christian Religious Education', 'category' => 'Humanities'],
            ['name' => 'Islamic Religious Education', 'category' => 'Humanities'],
            ['name' => 'Hindu Religious Education', 'category' => 'Humanities'],
            ['name' => 'Home Science', 'category' => 'Vocational'],
            ['name' => 'Agriculture', 'category' => 'Vocational'],
            ['name' => 'Creative Arts', 'category' => 'Arts'],
            ['name' => 'Physical Education', 'category' => 'PE'],
            ['name' => 'ICT', 'category' => 'Technology'],
            ['name' => 'Indigenous Languages', 'category' => 'Languages'],
            ['name' => 'Life Skills', 'category' => 'Other'],
            ['name' => 'Visual Arts', 'category' => 'Arts'],
            ['name' => 'Performing Arts', 'category' => 'Arts'],
            ['name' => 'Integrated Science', 'category' => 'Sciences'],
            ['name' => 'Pre-Technical and Pre-Career Education', 'category' => 'Vocational'],
            ['name' => 'Business Studies', 'category' => 'Humanities'],
            ['name' => 'Religious Education', 'category' => 'Humanities'],
            ['name' => 'Computer Science', 'category' => 'Technology'],
            ['name' => 'Health Education', 'category' => 'Health'],
            ['name' => 'Sports and Physical Education', 'category' => 'PE'],
            ['name' => 'Kenya Sign Language (Intermediate)', 'category' => 'Languages'],
            ['name' => 'Foreign Languages (French, Arabic, German)', 'category' => 'Languages'],
        ];

        foreach ($subjects as $subject) {
            Subject::firstOrCreate(['name' => $subject['name']], [
                'category' => $subject['category']
            ]);
        }
    }
}
