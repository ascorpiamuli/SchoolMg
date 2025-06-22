<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stream extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code'];

    /**
     * Many-to-Many: Stream belongs to many SchoolClasses through class_streams.
     */
    public function classes()
    {
        return $this->belongsToMany(SchoolClass::class, 'class_streams', 'stream_id', 'class_id')
                    ->withPivot('teacher_id')
                    ->withTimestamps();
    }

    /**
     * Optional: One-to-Many if you treat class_streams as a full model.
     */
    public function classStreams()
    {
        return $this->hasMany(ClassStream::class);
    }
}
