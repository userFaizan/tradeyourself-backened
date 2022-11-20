<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class HabitDays extends Model
{
    protected $fillable = [
        'habit_id',
        'day_id',
    ];
    use HasFactory;
    protected $table = "habit_days";
}
