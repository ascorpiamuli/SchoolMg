<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\ClassStream;
use Illuminate\Support\Facades\DB;

class TeacherSubjectSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = Teacher::all();
        $subjects = Subject::all();
        $classStreams = ClassStream::all();

        foreach ($teachers as $teacher) {
            // Random number of subjects per teacher
            $subjectSubset = $subjects->random(rand(1, 3));

            foreach ($subjectSubset as $subject) {
                // Pick a random class_stream_id
                $classStream = $classStreams->random();

                DB::table('teacher_subject')->insert([
                    'teacher_id'       => $teacher->id,
                    'subject_id'       => $subject->id,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);
            }
        }
    }
}
