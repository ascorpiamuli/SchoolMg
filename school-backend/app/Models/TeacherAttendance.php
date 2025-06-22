<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TeacherAttendance extends Model
{
    use HasFactory;

    protected $fillable = ['teacher_id', 'date', 'status'];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}


