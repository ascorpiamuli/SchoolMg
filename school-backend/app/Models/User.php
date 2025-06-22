<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /* -----------------------------------------------------------------
     | Constants
     |----------------------------------------------------------------- */
    public const ROLE_ADMIN      = 'admin';
    public const ROLE_TEACHER    = 'teacher';
    public const ROLE_ACCOUNTANT = 'accountant';
    public const ROLE_PARENT     = 'parent';

    /* -----------------------------------------------------------------
     | Mass-assignment
     |----------------------------------------------------------------- */
    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'middle_name',
        'email',
        'password',
        'role',
        'phone',
        'gender',
        'address',
        'national_id',
    ];

    /* -----------------------------------------------------------------
     | Hidden
     |----------------------------------------------------------------- */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /* -----------------------------------------------------------------
     | Casts
     |----------------------------------------------------------------- */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'subjects'          => 'array',   // JSON list on teachers
        'password'          => 'hashed',  // Laravel 12 feature: auto-hash
    ];

    /* -----------------------------------------------------------------
     | Relationships
     |----------------------------------------------------------------- */
    public function teacherProfile()
    {
        return $this->hasOne(Teacher::class, 'user_id');
    }

    public function guardianProfile()
    {
        return $this->hasOne(Guardian::class, 'user_id');
    }

    /* -----------------------------------------------------------------
     | Helpers / role checks
     |----------------------------------------------------------------- */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isTeacher(): bool
    {
        return $this->role === self::ROLE_TEACHER;
    }

    public function isParent(): bool
    {
        return $this->role === self::ROLE_PARENT;
    }
    public function isAccountant(): bool
    {
        return $this->role === self::ROLE_ACCOUNTANT;
    }
    

}
