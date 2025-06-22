<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Model: SchoolClass
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'level', 'description'];

    public function streams()
    {
        return $this->belongsToMany(Stream::class, 'class_streams', 'class_id', 'stream_id','teacher_id');
    }

}
