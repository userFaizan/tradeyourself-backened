<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{

    use HasFactory;
protected $table= "to_dos";
protected $primaryKey= 'id';
protected $guarded = [];

    public function priority(){
        return $this->belongsTo(Priority::class,'priority_id');
    }
    public function goal(){
        return $this->belongsTo(Goal::class,'goal_id');
    }
    public function users(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function todologs()
    {
        return $this->hasMany(TodoLog::class);
    }
}
