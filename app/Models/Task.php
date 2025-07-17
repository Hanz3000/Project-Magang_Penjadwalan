<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;


class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'category_id',
        'priority',
        'start_date',
        'end_date',
        'completed'
    ];



    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];



    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subTasks()
{
    return $this->hasMany(SubTask::class)->orderBy('id');
}

public function getDurationDaysAttribute()
{
    if ($this->start_date && $this->end_date) {
        return $this->start_date->diffInDays($this->end_date) + 1; // +1 to include both start and end days
    }
    return 0;
}
}
