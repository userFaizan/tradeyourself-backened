<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoLog extends Model
{
    use HasFactory;
    protected $table = 'todo_logs';
    protected $primaryKey= 'id';
    protected  $guarded = [];
    public function todo()
    {
        return $this->BelongsTo(Todo::class,'todo_id','id');
    }
    public function goals()
    {
        return $this->BelongsTo(Goal::class,'goal_id','id');
    }
    public function user()
    {
        return $this->BelongsTo(User::class,'user_id','id');
    }
}
