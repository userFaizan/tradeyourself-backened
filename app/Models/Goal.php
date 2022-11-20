<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    use HasFactory;
    protected $table = 'goals';
    protected $primaryKey= 'id';
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'goal_image_id',
    ];


    public function users(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function goal_images(){
        return $this->belongsTo(GoalImages::class,'goal_image_id','id');
    }
    public function habitlogs(){
        return $this->hasMany(HabitLog::class);
    }
    public function habit(){
        return $this->hasMany(Habit::class);
    }
    public function todo(){
        return $this->hasMany(Todo::class);
    }
    public function todoPending(){
        return $this->hasMany(Todo::class)->where('status','0');
    }
    public function habitPending(){
        return $this->hasMany(Habit::class)->where('status','0');
    }
    public function todoUnCompleted(){
        return $this->hasMany(Todo::class)->where('status','2');
    }
    public function habitUnCompleted(){
        return $this->hasMany(Habit::class)->where('status','2');
    }
    public function TodoCompleted(){
        return $this->hasMany(Todo::class)->where('status','1');
    }


    public function TodoCompletedhigh(){
        return $this->hasMany(Todo::class)->where('status','1')->where('priority_id','1');
    }
    public function TodoCompletedmedium(){
        return $this->hasMany(Todo::class)->where('status','1')->where('priority_id','2');
    }
    public function TodoCompletedlow(){
        return $this->hasMany(Todo::class)->where('status','1')->where('priority_id','3');
    }




    
    public function habitCompletedhigh(){
        return $this->hasMany(Habit::class)->where('status','1')->where('category_id','1')->where('priority_id','1');
    }
    public function habitCompletedmedium(){
        return $this->hasMany(Habit::class)->where('status','1')->where('category_id','1')->where('priority_id','2');
    }
    public function habitCompletedlow(){
        return $this->hasMany(Habit::class)->where('status','1')->where('category_id','1')->where('priority_id','3');
    }
    public function badhabitCompletedhigh(){
        return $this->hasMany(Habit::class)->where('status','1')->where('category_id','2')->where('priority_id','1');
    }
    public function badhabitCompletedmedium(){
        return $this->hasMany(Habit::class)->where('status','1')->where('category_id','2')->where('priority_id','2');
    }
    public function badhabitCompletedlow(){
        return $this->hasMany(Habit::class)->where('status','1')->where('category_id','2')->where('priority_id','3');
    }
}
