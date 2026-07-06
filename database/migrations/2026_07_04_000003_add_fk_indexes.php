<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add missing indexes on foreign-key columns.
 * SQLite does not auto-index FK columns, so every join and WHERE lookup
 * on these columns was doing a full table scan.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('assigned_to');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->index('ticket_id');
            $table->index('user_id');
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->index('ticket_id');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['assigned_to']);
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropIndex(['ticket_id']);
            $table->dropIndex(['user_id']);
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex(['ticket_id']);
            $table->dropIndex(['user_id']);
        });
    }
};
