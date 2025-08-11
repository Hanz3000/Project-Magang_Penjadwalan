<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

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
        'completed',
        'user_id',
        'is_all_day'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'completed' => 'boolean',
        'is_all_day' => 'boolean',
    ];

    // ✅ Supaya ikut di JSON
    protected $appends = [
        'start_date_formatted',
        'end_date_formatted',
        'duration_days',
        'calendar_progress',
        'formatted_start_time',
        'formatted_end_time',
        'start_date_time',
        'end_date_time'
    ];

    /** ===== RELASI ===== */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subTasks(): HasMany
    {
        return $this->hasMany(SubTask::class)->orderBy('parent_id')->orderBy('created_at');
    }

    public function collaborators(): HasMany
    {
        return $this->hasMany(TaskCollaborator::class);
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(TaskRevision::class);
    }

    public function approvedCollaborators(): HasMany
    {
        return $this->hasMany(TaskCollaborator::class)->where('status', 'approved');
    }

    public function pendingRevisions(): HasMany
    {
        return $this->hasMany(TaskRevision::class)->where('status', 'pending');
    }

    /** ===== ACCESSOR ===== */
    public function getStartDateFormattedAttribute()
    {
        return $this->start_date ? $this->start_date->format('d M Y') : null;
    }

    public function getEndDateFormattedAttribute()
    {
        return $this->end_date ? $this->end_date->format('d M Y') : null;
    }

    public function getDurationDaysAttribute()
    {
        if ($this->start_date && $this->end_date) {
            return $this->start_date->diffInDays($this->end_date) + 1;
        }
        return 0;
    }

    public function getCalendarProgressAttribute()
    {
        if (!$this->start_date || !$this->end_date) return 0;

        $total = $this->start_date->diffInDays($this->end_date) + 1;
        $elapsed = $this->start_date->diffInDays(now()) + 1;

        return min(100, max(0, round(($elapsed / $total) * 100)));
    }

    public function getFormattedStartTimeAttribute()
    {
        return $this->start_time ? $this->start_time->format('H:i') : null;
    }

    public function getFormattedEndTimeAttribute()
    {
        return $this->end_time ? $this->end_time->format('H:i') : null;
    }

    public function getStartDateTimeAttribute()
    {
        if ($this->start_date && $this->start_time) {
            return $this->start_date->format('Y-m-d') . 'T' . $this->start_time->format('H:i:s');
        }
        return $this->start_date ? $this->start_date->format('Y-m-d') : null;
    }

    public function getEndDateTimeAttribute()
    {
        if ($this->end_date && $this->end_time) {
            return $this->end_date->format('Y-m-d') . 'T' . $this->end_time->format('H:i:s');
        }
        return $this->end_date ? $this->end_date->format('Y-m-d') : null;
    }

    /** ===== SCOPE ===== */
    public function scopeAccessibleBy($query, $userId)
    {
        return $query->where('user_id', $userId)
            ->orWhereHas('collaborators', function ($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->where('status', 'approved');
            });
    }

    /** ===== PERMISSION ===== */
    public function canEdit($userId): bool
    {
        if ($this->user_id === $userId) {
            return true;
        }

        return $this->collaborators()
            ->where('user_id', $userId)
            ->where('status', 'approved')
            ->where('can_edit', true)
            ->exists();
    }

    public function canView($userId): bool
    {
        if ($this->user_id === $userId) {
            return true;
        }

        return $this->collaborators()
            ->where('user_id', $userId)
            ->where('status', 'approved')
            ->exists();
    }
}
