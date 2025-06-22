<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guardians', function (Blueprint $table) {
            $table->id();    // BIGINT UNSIGNED – matches students.id type

            // If guardians also log in, add a user_id FK here:
            // $table->foreignId('user_id')->nullable()
            //       ->constrained()->nullOnDelete();

            $table->string('occupation')->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guardians');
    }
};
