<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('class_stream_subject_teacher', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_stream_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

           $table->unique(['class_stream_id', 'teacher_id', 'subject_id'], 'class_teacher_subject_unique');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_stream_subject_teacher');
    }
};

