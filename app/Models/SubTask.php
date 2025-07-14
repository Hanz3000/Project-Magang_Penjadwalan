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
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    
}
