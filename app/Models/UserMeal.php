<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMeal extends Model
{
     use HasFactory;

    protected $table = 't_user_meal'; // specify table name

    protected $fillable = [
        'user_id',
        'user_name',
        'date',
        'day',
        'night',
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
