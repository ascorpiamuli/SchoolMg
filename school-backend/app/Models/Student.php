<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Guardian;
use App\Models\SchoolClass as Classes;
use App\Models\Streams;
use App\Models\Teacher;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    /* -----------------------------------------------------------------
     | Core settings
     |----------------------------------------------------------------- */
    protected $table = 'students';           // default, but explicit is fine

    /* -----------------------------------------------------------------
     | Mass-assignment
     |----------------------------------------------------------------- */
    protected $fillable = [
        'admission_number',
        'first_name',
        'middle_name',
        'last_name',
        'gender',
        'date_of_birth',
        'avatar',
        'class_stream_id',    // FK → class_streams.id
        'status',
        'enrolled_at',
    ];

    /* -----------------------------------------------------------------
     | Casts
     |----------------------------------------------------------------- */
    protected $casts = [
        'date_of_birth' => 'date',
        'enrolled_at'   => 'datetime',
        'subjects'      => 'array',   // if you ever add a JSON column
    ];

    /* -----------------------------------------------------------------
     | Appended accessors
     |----------------------------------------------------------------- */
    protected $appends = ['full_name', 'age', 'avatar_url'];

    /* -----------------------------------------------------------------
     | Accessors
     |----------------------------------------------------------------- */
     public function getClassNameAttribute()
    {
        return $this->classStream->class->name ?? null;
    }

    public function getStreamNameAttribute()
    {
        return $this->classStream->stream->name ?? null;
    }

    public function getClassTeacherAttribute()
    {
        return $this->classStream->teacher ?? null;
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
    }

    public function getAgeAttribute(): ?int
    {
        return $this->date_of_birth ? Carbon::parse($this->date_of_birth)->age : null;
    }

    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar
            ? asset("storage/avatars/{$this->avatar}")
            : asset('images/defaults/' . strtolower($this->gender ?? 'male') . '.png');
    }

    /* -----------------------------------------------------------------
     | Relationships
     |----------------------------------------------------------------- */
    // ⬌ many guardians : many students


    public function classStream()
    {
        return $this->belongsTo(ClassStream::class);
    }
    public function guardians()
    {
        return $this->belongsToMany(Guardian::class, 'guardian_student')
                    ->withPivot('relation')
                    ->withTimestamps();
    }



    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function fees()
    {
        return $this->hasMany(FeePayment::class);
    }

    /* -----------------------------------------------------------------
     | Query scopes
     |----------------------------------------------------------------- */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
