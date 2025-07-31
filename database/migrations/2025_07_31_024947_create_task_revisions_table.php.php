<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('task_collaborators', function (Blueprint $table) {
            $table->foreignId('invited_by')->nullable()->after('can_edit')->constrained('users')->onDelete('set null');
            $table->timestamp('invited_at')->nullable()->after('invited_by');
            $table->timestamp('responded_at')->nullable()->after('invited_at');
        });
    }

    public function down(): void
    {
        Schema::table('task_collaborators', function (Blueprint $table) {
            $table->dropForeign(['invited_by']);
            $table->dropColumn(['invited_by', 'invited_at', 'responded_at']);
        });
    }
};