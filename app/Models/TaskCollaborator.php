<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskCollaborator extends Model
{
    protected $fillable = [
        'task_id',
        'user_id',
        'status',
        'can_edit',
        'invited_by',
        'invited_at',
        'responded_at'
    ];

    protected $casts = [
        'can_edit' => 'boolean',
        'invited_at' => 'datetime',
        'responded_at' => 'datetime'
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
