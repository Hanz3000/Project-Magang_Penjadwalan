<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubTask extends Model
{
    protected $table = 'sub_tasks';

    protected $fillable = [
        'task_id',
        'title',
        'description',
        'completed',
        'parent_id',
        'is_group',
        'start_date',
        'end_date',
        'start_time',
        'end_time'
    ];

    protected $casts = [
        'completed' => 'boolean',
        'is_group' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(SubTask::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(SubTask::class, 'parent_id')->with('children');
    }

    /**
     * Check if user can edit this subtask
     */
    public function canEdit($userId): bool
    {
        return $this->task && $this->task->canEdit($userId);
    }

    /**
     * Check if user can view this subtask
     */
    public function canView($userId): bool
    {
        return $this->task && $this->task->canView($userId);
    }
}
