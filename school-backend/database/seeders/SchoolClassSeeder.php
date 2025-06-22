<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SchoolClass;

class SchoolClassSeeder extends Seeder
{
    public function run(): void
    {
        $classes = [
            ['name' => 'Preprimary 1', 'level' => 'Primary'],
            ['name' => 'Preprimary 2', 'level' => 'Primary'],
            ['name' => 'Grade 1', 'level' => 'Primary'],
            ['name' => 'Grade 2', 'level' => 'Primary'],
            ['name' => 'Grade 3', 'level' => 'Primary'],
            ['name' => 'Grade 4', 'level' => 'Primary'],
            ['name' => 'Grade 5', 'level' => 'Primary'],
            ['name' => 'Grade 6', 'level' => 'Primary'],
            ['name' => 'Grade 7', 'level' => 'Junior Secondary'],
            ['name' => 'Grade 8', 'level' => 'Junior Secondary'],
            ['name' => 'Grade 9', 'level' => 'Junior Secondary'],
        ];

        foreach ($classes as $class) {
            SchoolClass::create([
                'name' => $class['name'],
                'level' => $class['level'],
                'description' => fake()->sentence(),
            ]);
        }
    }
}
