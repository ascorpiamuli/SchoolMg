<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassStream;
use App\Models\SchoolClass;
use App\Models\Stream;
use App\Models\Teacher;

class ClassStreamSeeder extends Seeder
{
    public function run(): void
    {
        $classes = SchoolClass::all();
        $streams = Stream::all();
        $teachers = Teacher::all()->shuffle(); // Randomly assign teachers

        $teacherIndex = 0;

        foreach ($classes as $class) {
            foreach ($streams as $stream) {
                ClassStream::create([
                    'class_id'   => $class->id,
                    'stream_id'  => $stream->id,
                    'teacher_id' => $teachers[$teacherIndex % $teachers->count()]->id ?? null,
                ]);

                $teacherIndex++;
            }
        }
    }
}
