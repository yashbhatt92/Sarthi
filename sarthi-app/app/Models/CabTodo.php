<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CabTodo extends Model
{
    protected $table = 'cab_todos';
    protected $fillable = ['staff_id', 'title', 'is_done'];
}
