<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubTask extends Model
{
    protected $table = 'sub_tasks';

    protected $fillable = ['title', 'task_id', 'parent_id', 'completed', 'is_group','start_date','end_date'];
    protected $casts = [
        'start_date' => 'date', // Casting ke objek Carbon
        'end_date' => 'date',
        'completed' => 'boolean',
    ];
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function children()
    {
        return $this->hasMany(SubTask::class, 'parent_id')->with('children');
    }

    public function parent()
    {
        return $this->belongsTo(SubTask::class, 'parent_id');
    }
}
