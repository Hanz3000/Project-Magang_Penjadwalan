<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubTask extends Model
{
    protected $fillable = [
    'title',
    'task_id',
    'completed',
    'parent_id', // ✅ tambahkan ini
];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function children()
{
    return $this->hasMany(Subtask::class, 'parent_id')->with('children');
}

public function parent()
{
    return $this->belongsTo(Subtask::class, 'parent_id');
}


    
}
