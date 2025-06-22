<?php
// Subject Model: Subject.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'category'];
    public function classStreams()
    {
        return $this->belongsToMany(ClassStream::class, 'class_stream_subject_teacher')
                    ->withPivot('teacher_id')
                    ->withTimestamps();
    }
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'class_stream_subject_teacher')
            ->withPivot('class_stream_id')
            ->withTimestamps();
    }
    

}

