<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habit extends Model
{
    use HasFactory;
    protected $table = "habits";
    protected $primaryKey= 'id';
    protected $guarded = [];

    public function days()
    {
        return $this->belongsToMany(Days::class,'habit_days')->withPivot('id','id','days_id','habit_id');
    }
    public function habit_category()
    {
        return $this->belongsTo(HabitCategory::class,'category_id');
    }
    public function priority()
    {
        return $this->belongsTo(Priority::class,'priority_id');
    }
    public function goal()
    {
        return $this->belongsTo(Goal::class,'goal_id');
    }
    public function users()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function habitlogs()
    {
        return $this->hasMany(HabitLog::class);
    }
}
