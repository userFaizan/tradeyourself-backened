<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoalImages extends Model
{
    use HasFactory;
    protected $table = "goal_images";
    protected $fillable = [
        'image',
    ];

    public function goals(){
        return $this->hasMany(Goal::class,'id');
    }
}
