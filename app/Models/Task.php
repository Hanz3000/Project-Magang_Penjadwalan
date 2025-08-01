<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    // ✅ Relasi user (pemilik tugas)
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

    public function getDurationDaysAttribute()
    {
        if ($this->start_date && $this->end_date) {
            return $this->start_date->diffInDays($this->end_date) + 1;
        }
        return 0;
    }

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

    public function scopeAccessibleBy($query, $userId)
    {
        return $query->where('user_id', $userId)
            ->orWhereHas('collaborators', function ($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->where('status', 'approved');
            });
    }
}