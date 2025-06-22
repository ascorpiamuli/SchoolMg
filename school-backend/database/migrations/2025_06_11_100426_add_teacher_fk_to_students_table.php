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
        /*
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('teacher_id')
                ->nullable()                 // until you assign the class teacher
                ->constrained('teachers')    // references teachers.id
                ->nullOnDelete();            // if teacher deleted → set null
        });*/
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->dropColumn('teacher_id');
        });

    }
};
