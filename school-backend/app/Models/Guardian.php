<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guardian extends Model
{
    use HasFactory, SoftDeletes;

    /* -----------------------------------------------------------------
     | Table & key
     |----------------------------------------------------------------- */
    protected $table   = 'guardians';
    protected $guarded = ['id'];   // or use $fillable if you prefer white-listing

    /* -----------------------------------------------------------------
     | Casts
     |----------------------------------------------------------------- */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    /* -----------------------------------------------------------------
     | Relationships
     |----------------------------------------------------------------- */

    /**
     * Many guardians ↔ many students (via pivot `guardian_student`)
     */
    public function students()
    {
        return $this->belongsToMany(Student::class, 'guardian_student')
                    ->withPivot('relation')      // e.g. mother, father, guardian
                    ->withTimestamps();
    }

    /**
     * If guardians can log in, link to the users table
     * (comment out if you don’t have user_id)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /* -----------------------------------------------------------------
     | Accessors & helpers
     |----------------------------------------------------------------- */

    /**
     * Get the guardian's occupation.
     */
    public function getOccupationAttribute(): ?string
    {
        return $this->attributes['occupation'] ?? null;
    }
}

