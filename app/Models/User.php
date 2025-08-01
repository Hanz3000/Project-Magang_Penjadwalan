<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get all tasks owned by this user
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get all collaborations where this user is a collaborator
     */
    public function collaborations()
    {
        return $this->hasMany(TaskCollaborator::class, 'user_id');
    }

    /**
     * Get all collaborations where this user invited others
     */
    public function sentInvitations()
    {
        return $this->hasMany(TaskCollaborator::class, 'invited_by');
    }

    /**
     * Get all task revisions where this user is the collaborator
     */
    public function taskRevisions()
    {
        return $this->hasMany(TaskRevision::class, 'collaborator_id');
    }

    /**
     * Get all task revisions reviewed by this user
     */
    public function reviewedRevisions()
    {
        return $this->hasMany(TaskRevision::class, 'reviewed_by');
    }

    /**
     * Get tasks where this user is an approved collaborator
     */
    public function collaboratedTasks()
    {
        return $this->belongsToMany(Task::class, 'task_collaborators', 'user_id', 'task_id')
                    ->wherePivot('status', 'approved')
                    ->withPivot(['can_edit', 'status', 'invited_at', 'responded_at']);
    }

    /**
     * Get pending collaboration invites for this user
     */
    public function pendingInvites()
    {
        return $this->hasMany(TaskCollaborator::class, 'user_id')
                    ->where('status', 'pending')
                    ->with(['task', 'inviter']);
    }
}