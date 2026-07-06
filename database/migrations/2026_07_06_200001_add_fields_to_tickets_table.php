<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table): void {
            $table->foreignId('category_id')->nullable()->after('assigned_to')
                ->constrained('categories')->nullOnDelete();
            $table->date('due_date')->nullable()->after('category_id');
            $table->text('resolution_notes')->nullable()->after('due_date');
            $table->timestamp('resolved_at')->nullable()->after('resolution_notes');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('category_id');
            $table->dropColumn(['due_date', 'resolution_notes', 'resolved_at']);
        });
    }
};