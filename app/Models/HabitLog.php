<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HabitLog extends Model
{
    use HasFactory;
    protected $table = 'habit_logs';
    protected $primaryKey= 'id';
    protected  $guarded = [];
    public function habit()
    {
        return $this->BelongsTo(Habit::class,'habit_id','id');
    }
    public function goals()
    {
        return $this->BelongsTo(Goal::class,'goal_id','id');
    }
    public function user()
    {
        return $this->BelongsTo(User::class,'user_id','id');
    }
    // public function habitCompletedhigh(){
    //     return $this->BelongsTo(Habit::class)->where('status','1')->where('category_id','1')->where('priority_id','1');
    // }
    // public function habitCompletedmedium(){
    //     return $this->BelongsTo(Habit::class)->where('status','1')->where('category_id','1')->where('priority_id','2');
    // }
    // public function habitCompletedlow(){
    //     return $this->BelongsTo(Habit::class)->where('status','1')->where('category_id','1')->where('priority_id','3');
    // }
    // public function badhabitCompletedhigh(){
    //     return $this->BelongsTo(Habit::class)->where('status','1')->where('category_id','2')->where('priority_id','1');
    // }
    // public function badhabitCompletedmedium(){
    //     return $this->BelongsTo(Habit::class)->where('status','1')->where('category_id','2')->where('priority_id','2');
    // }
    // public function badhabitCompletedlow(){
    //     return $this->BelongsTo(Habit::class)->where('status','1')->where('category_id','2')->where('priority_id','3');
    // }

}
