<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Add is_all_day column if it doesn't exist
            if (!Schema::hasColumn('tasks', 'is_all_day')) {
                $table->boolean('is_all_day')->default(false)->after('end_time');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            if (Schema::hasColumn('tasks', 'is_all_day')) {
                $table->dropColumn('is_all_day');
            }
        });
    }
};