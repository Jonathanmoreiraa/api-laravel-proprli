<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title', 'description', 'creator_id', 'status_id', 'building_id', 'assignee_id', 'completed_on'
    ];

    /**
     * Returns all the comments from a specific task
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'task_id');
    }

    /**
     * Returns the creator of the task
     */
    public function task_creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Returns the responsible for doing the task
     */
    public function task_responsible()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    /**
     * Returns the status of the task
     */
    public function task_status()
    {
        return $this->belongsTo(TasksStatuses::class, 'status_id');
    }
}
