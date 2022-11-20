<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'users';
    protected $primaryKey= 'id';
    protected $fillable = [
        'name',
        'email',
    ];
   public function goals(){
    return $this->hasMany(Goal::class);
}
public function habits(){
    return $this->hasMany(Habit::class);
}
public function todos(){
    return $this->hasMany(Todo::class);
}
public function calcuations(){
    return $this->hasMany(calcuations::class);
}
public function habitlogs(){
    return $this->hasMany(HabitLog::class);
}
}
