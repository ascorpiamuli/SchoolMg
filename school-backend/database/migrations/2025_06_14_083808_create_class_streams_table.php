<?php
// database/migrations/xxxx_xx_xx_create_class_streams_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassStreamsTable extends Migration
{
    public function up()
    {
        Schema::create('class_streams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('school_classes')->onDelete('cascade');
            $table->foreignId('stream_id')->constrained('streams')->onDelete('cascade');
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->onDelete('set null');
            $table->timestamps();

            $table->unique(['class_id', 'stream_id']);
            $table->unique('teacher_id');
        });

    }

    public function down()
    {
        Schema::dropIfExists('class_streams');
    }
}

