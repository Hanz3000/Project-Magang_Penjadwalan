<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskRevision extends Model
{
    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'task_revisions';

    /**
     * Atribut yang bisa diisi (mass assignable).
     *
     * @var array
     */
    protected $fillable = [
        'task_id',
        'collaborator_id',
        'revision_type',
        'original_data',
        'proposed_data',
        'status',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
    ];

    /**
     * Atribut yang di-cast ke tipe tertentu.
     *
     * @var array
     */
    protected $casts = [
        'original_data' => 'array',      // Data asli tugas sebelum perubahan
        'proposed_data' => 'array',      // Data usulan dari kolaborator
        'status' => 'string',            // pending, approved, rejected
        'reviewed_at' => 'datetime',     // Kapan direview
        'review_notes' => 'string',      // Catatan saat menolak
    ];

    /**
     * Atribut yang tidak boleh diisi secara massal.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Atribut yang dihidden saat di-serialize ke array/JSON.
     *
     * @var array
     */
    protected $hidden = [
        'review_notes', // Opsional: sembunyikan jika sensitif
    ];

    // -----------------------------
    // Relasi (Relationships)
    // -----------------------------

    /**
     * Relasi ke tugas yang direvisi.
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Relasi ke kolaborator yang mengusulkan revisi.
     */
    public function collaborator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'collaborator_id');
    }

    /**
     * Relasi ke user yang meninjau (approve/tolak) revisi.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // -----------------------------
    // Accessor (Atribut Turunan)
    // -----------------------------

    /**
     * Dapatkan nama tipe revisi dalam format yang ramah dibaca.
     */
    public function getRevisionTypeLabelAttribute(): string
    {
        return match ($this->revision_type) {
            'create' => 'Pembuatan Tugas',
            'update' => 'Pembaruan Tugas',
            'delete' => 'Penghapusan Tugas',
            default => ucfirst($this->revision_type)
        };
    }

    /**
     * Dapatkan warna status untuk UI (contoh: badge).
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
            'approved' => 'bg-green-100 text-green-800 border-green-200',
            'rejected' => 'bg-red-100 text-red-800 border-red-200',
            default => 'bg-gray-100 text-gray-800 border-gray-200'
        };
    }

    /**
     * Dapatkan ikon berdasarkan tipe revisi.
     */
    public function getRevisionIconAttribute(): string
    {
        return match ($this->revision_type) {
            'create' => '➕',
            'update' => '✏️',
            'delete' => '🗑️',
            default => '📄'
        };
    }

    // -----------------------------
    // Method Status (Boolean)
    // -----------------------------

    /**
     * Cek apakah revisi sedang menunggu.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Cek apakah revisi disetujui.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Cek apakah revisi ditolak.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Cek apakah revisi sudah diproses (approved/rejected).
     */
    public function isReviewed(): bool
    {
        return $this->isApproved() || $this->isRejected();
    }

    // -----------------------------
    // Method Bantuan
    // -----------------------------

    /**
     * Dapatkan daftar field yang berubah.
     */
    public function getChangedFields(): array
    {
        $changes = [];
        foreach ($this->proposed_data as $key => $newValue) {
            $originalValue = $this->original_data[$key] ?? null;
            if ($originalValue !== $newValue) {
                $changes[$key] = [
                    'from' => $originalValue,
                    'to' => $newValue
                ];
            }
        }
        return $changes;
    }

    /**
     * Terapkan perubahan ke tugas asli (jika disetujui).
     */
    public function applyChanges(): bool
    {
        if (!$this->isApproved()) {
            return false;
        }

        $task = $this->task;
        $task->update($this->proposed_data);

        return true;
    }

    /**
     * Batalkan perubahan (jika ditolak atau dibatalkan).
     */
    public function revertChanges(): bool
    {
        if (!$this->isRejected()) {
            return false;
        }

        $task = $this->task;
        $task->update($this->original_data);

        return true;
    }

    // -----------------------------
    // Scope (Query Builder)
    // -----------------------------

    /**
     * Scope untuk revisi yang sedang menunggu.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope untuk revisi yang sudah disetujui.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope untuk revisi yang ditolak.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope untuk revisi berdasarkan tipe.
     */
    public function scopeType($query, string $type)
    {
        return $query->where('revision_type', $type);
    }

    /**
     * Scope untuk revisi yang terkait dengan user tertentu (sebagai pengusul).
     */
    public function scopeByCollaborator($query, int $userId)
    {
        return $query->where('collaborator_id', $userId);
    }

    /**
     * Scope untuk revisi yang harus direview oleh pemilik tugas.
     */
    public function scopeForOwner($query, int $ownerId)
    {
        return $query->whereHas('task', function ($q) use ($ownerId) {
            $q->where('user_id', $ownerId);
        });
    }
}