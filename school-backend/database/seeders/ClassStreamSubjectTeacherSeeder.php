<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Teacher;
use App\Models\ClassStream;
use App\Models\Subject;

class ClassStreamSubjectTeacherSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate existing records if needed
        Schema::disableForeignKeyConstraints();
        DB::table('class_stream_subject_teacher')->truncate();
        Schema::enableForeignKeyConstraints();

        $teachers = Teacher::all();
        $classStreams = ClassStream::all();
        $subjects = Subject::all();

        $data = [];

        foreach ($teachers as $teacher) {
            // Pick a random class stream
            $classStream = $classStreams->random();

            // Pick 1 to 3 random subjects for this teacher
            $randomSubjects = $subjects->random(rand(1, 3));

            foreach ($randomSubjects as $subject) {
                $data[] = [
                    'class_stream_id' => $classStream->id,
                    'teacher_id' => $teacher->id,
                    'subject_id' => $subject->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('class_stream_subject_teacher')->insert($data);
    }
}
