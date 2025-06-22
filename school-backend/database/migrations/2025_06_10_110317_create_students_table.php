<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            $table->string('admission_number')->unique();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');

            $table->enum('gender', ['male', 'female', 'other'])->default('other');
            $table->date('date_of_birth')->nullable();
            $table->string('avatar')->nullable();

            // Optional FK placeholders
           // $table->uuid('class_id')->nullable();
            //$table->uuid('stream_id')->nullable();

            $table->enum('status', ['active', 'suspended', 'graduated'])->default('active');
            $table->timestamp('enrolled_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
