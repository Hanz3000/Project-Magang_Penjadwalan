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
        'start_time',
        'end_time',
        'completed'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
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

    /**
     * Get formatted start time
     */
    public function getFormattedStartTimeAttribute()
    {
        return $this->start_time ? $this->start_time->format('H:i') : null;
    }

    /**
     * Get formatted end time
     */
    public function getFormattedEndTimeAttribute()
    {
        return $this->end_time ? $this->end_time->format('H:i') : null;
    }

    /**
     * Check if task is all day
     */
    public function getIsAllDayAttribute()
    {
        return !$this->start_time || !$this->end_time;
    }

    /**
     * Get full start datetime
     */
    public function getStartDateTimeAttribute()
    {
        if ($this->start_date && $this->start_time) {
            return $this->start_date->format('Y-m-d') . 'T' . $this->start_time->format('H:i:s');
        }
        return $this->start_date ? $this->start_date->format('Y-m-d') : null;
    }

    /**
     * Get full end datetime
     */
    public function getEndDateTimeAttribute()
    {
        if ($this->end_date && $this->end_time) {
            return $this->end_date->format('Y-m-d') . 'T' . $this->end_time->format('H:i:s');
        }
        return $this->end_date ? $this->end_date->format('Y-m-d') : null;
    }
}