<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HabitCategory extends Model
{
    use HasFactory;

protected $table= "habit_categories";
protected $primaryKey= 'id';

protected $guarded = [];

    public function habit(){
        return $this->hasMany(Habit::class);
    }
}
