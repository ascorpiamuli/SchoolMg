<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use HasFactory, SoftDeletes;

    /* -----------------------------------------------------------------
     | Table & fillable
     |----------------------------------------------------------------- */
    protected $table = 'teachers';

    protected $fillable = [
        'user_id',           // FK → users.id  (nullable)
        'staff_id',
        'tsc_number',
        'qualification',
        'subjects',          // JSON list     ["Math","Science"]
        'dept',           // FK → departments.id (optional)
        'employer',
        'salutation',
        'first_name',
        'middle_name',
        'last_name',
        'date_of_birth',
    ];

    /* -----------------------------------------------------------------
     | Casts
     |----------------------------------------------------------------- */
    protected $casts = [
        'subjects'     => 'array',     // auto-JSON ↔ PHP array
        'date_of_birth'=> 'date',
    ];

    /* -----------------------------------------------------------------
     | Accessors/Getter Methods
     |----------------------------------------------------------------- */
    protected $appends = ['full_name'];

    public function getFullNameAttribute(): string
    {
        $user = $this->user;

        if (!$user) {
            return ''; // Avoid error if relationship is not loaded
        }

        return trim(
            ($this->salutation ? $this->salutation . ' ' : '') .
            "{$user->first_name} {$user->middle_name} {$user->last_name}"
        );
    }


    /* -----------------------------------------------------------------
     | Relationships
     |----------------------------------------------------------------- */

    /** One teacher  → many students */
    public function students()
    {
        return $this->hasMany(Student::class, 'teacher_id');
    }

    /** Optional auth account */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Optional department link (uncomment if you add table & FK) */
    // public function department()
    // {
    //     return $this->belongsTo(Department::class, 'dept_id');
    // }
    public function attendances()
    {
        return $this->hasMany(TeacherAttendance::class, 'teacher_id');
    }
    public function class_stream()
    {
        return $this->hasOne(ClassStream::class, 'teacher_id');
    }
    public function subjectsTeaching()
    {
        return $this->belongsToMany(Subject::class, 'class_stream_subject_teacher')
            ->withPivot('class_stream_id')
            ->withTimestamps();
    }
        // Subjects the teacher is qualified/specialized in
    public function specializedSubjects()
    {
        return $this->belongsToMany(Subject::class, 'teacher_subject')
                    ->withTimestamps();
    }




}
