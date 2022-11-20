<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Days extends Model
{
    protected $table= "days";
    use HasFactory;
    protected $fillable = [
        'id',
        'days'
    ];
    public function habits()
    {
        return $this->belongsToMany(Habit::class,'habit_days');
    }
}
