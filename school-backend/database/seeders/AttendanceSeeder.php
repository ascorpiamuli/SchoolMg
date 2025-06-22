<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\Student;

class AttendanceSeeder extends Seeder
{
    public function run()
    {
        $students = Student::all();
        foreach ($students as $student) {
            Attendance::create([
                'student_id' => $student->id,
                'date' => now()->toDateString(),
                'status' => collect(['present', 'absent', 'late'])->random(),
            ]);
        }
    }
}

