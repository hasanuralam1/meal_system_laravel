<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dustbin extends Model
{
    use HasFactory;

    protected $table = 't_dustbin'; // specify table name

    protected $fillable = [
        'user_id',
        'date',
        'day_name',
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
