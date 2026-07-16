<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Replaces the single users.department_id column with a many-to-many
     * pivot, so one technician (e.g. IT support) can belong to several
     * departments. Existing single-department assignments are backfilled.
     */
    public function up(): void
    {
        Schema::create('department_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['department_id', 'user_id']);
        });

        DB::table('users')
            ->whereNotNull('department_id')
            ->get(['id', 'department_id'])
            ->each(function ($user) {
                DB::table('department_user')->insert([
                    'department_id' => $user->department_id,
                    'user_id'       => $user->id,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('department_id')
                  ->nullable()
                  ->after('role')
                  ->constrained('departments')
                  ->nullOnDelete();
        });

        DB::table('department_user')
            ->orderBy('id')
            ->get()
            ->groupBy('user_id')
            ->each(function ($rows, $userId) {
                DB::table('users')->where('id', $userId)->update([
                    'department_id' => $rows->first()->department_id,
                ]);
            });

        Schema::dropIfExists('department_user');
    }
};
