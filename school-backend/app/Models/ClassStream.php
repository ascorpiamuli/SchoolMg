<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassStream extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'stream_id',
        'teacher_id',
    ];

    // Relationships

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function stream()
    {
        return $this->belongsTo(Stream::class, 'stream_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
    
    public function subjects()
    {
        return $this->belongsToMany(Teacher::class, 'class_stream_subject_teacher')
                    ->withPivot('subject_id')
                    ->withTimestamps();
    }
    public function subjectAssignments()
    {
        return $this->hasMany(ClassStreamSubjectTeacher::class);
    }


}
