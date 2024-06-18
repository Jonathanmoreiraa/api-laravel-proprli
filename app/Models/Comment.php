<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'comment',
        "task_id",
        "user_id",
        'date'
    ];
    
    /**
     * Returns the user that commented in the task
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
