<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();

            // FK to users *if* teachers log in
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()      // == references users(id)
                ->nullOnDelete();

            $table->string('staff_id')->unique();
            $table->string('tsc_number')->unique();
            $table->string('dept')->nullable(); // e.g. Mathematics, Science, etc.
            $table->string('qualification')->nullable();
            $table->json('subjects')->nullable();   // JSON is fine on MySQL 5.7+

            // Remove these; a teacher rarely belongs to ONE class/stream forever,
            // instead you’d have a timetable or pivot. Keep them only if business-logic demands it.
            // $table->unsignedBigInteger('class_id')->nullable();
            // $table->unsignedBigInteger('stream_id')->nullable();
            // $table->unsignedBigInteger('dept_id')->nullable();

            $table->string('employer')->nullable();

            $table->string('salutation')->nullable();
            $table->date('date_of_birth')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
