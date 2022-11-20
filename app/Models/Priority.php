<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Priority extends Model
{
    use HasFactory;
protected $table= "priorities";
protected $primaryKey= 'id';
protected $guarded = [];


    public function habit(){
        return $this->hasMany(Habit::class);
    }
    public function todo(){
        return $this->hasMany(Todo::class);
    }
}
