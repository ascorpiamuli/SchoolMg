<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Accountant extends Model
{
    use HasFactory; // ✅ This enables the `factory()` method
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'qualification',
        'date_of_birth',
        // add other fields as needed
    ];

    /**
     * Get the user that owns the accountant.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
