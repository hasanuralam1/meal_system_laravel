<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    use HasFactory;

    protected $table = 't_meal'; // specify table name

    protected $fillable = [
        'meal_name',
        'date',
        'day',
        'night',
        'day_name',
    ];
}
