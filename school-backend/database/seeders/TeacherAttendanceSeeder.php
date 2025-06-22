<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;
use App\Models\TeacherAttendance;
use Illuminate\Support\Carbon;

class TeacherAttendanceSeeder extends Seeder
{
    public function run()
    {
        $teachers = Teacher::all();
        $statuses = ['present', 'absent', 'late', 'on_leave', 'remote'];
        $date = Carbon::now()->toDateString();

        foreach ($teachers as $teacher) {
            TeacherAttendance::updateOrCreate(
                ['teacher_id' => $teacher->id, 'date' => $date],
                ['status' => collect($statuses)->random()]
            );
        }
    }
}

