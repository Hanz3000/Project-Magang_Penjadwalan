<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Update task_revisions table to support subtask operations
        Schema::table('task_revisions', function (Blueprint $table) {
            // Add index for better performance on common queries
            $table->index(['collaborator_id', 'status']);
            $table->index(['task_id', 'revision_type', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('task_revisions', function (Blueprint $table) {
            $table->dropIndex(['collaborator_id', 'status']);
            $table->dropIndex(['task_id', 'revision_type', 'status']);
        });
    }
};