<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calculations extends Model
{
    use HasFactory;
    protected $table = 'calculations';
    protected $primaryKey= 'id';
    protected $fillable = [
        'user_id',
        'goal_id',
        'High',
        'Low',
        'Open',
        'Close',
        'Totalpoints',
        'Date'
    ];

    public function users(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function goals(){
        return $this->belongsTo(Goal::class,'goal_id');
    }
}
