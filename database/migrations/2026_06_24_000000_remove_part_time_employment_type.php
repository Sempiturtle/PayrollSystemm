<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Update any existing part_time users to professor
        DB::table('users')
            ->where('employment_type', 'part_time')
            ->update(['employment_type' => 'professor']);

        // 2. Modify enum column (driver-aware)
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN employment_type ENUM('professor', 'staff') NOT NULL DEFAULT 'professor'");
        } else {
            // SQLite doesn't support MODIFY COLUMN or ENUM — use string column instead
            Schema::table('users', function (Blueprint $table) {
                $table->string('employment_type', 20)->default('professor')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN employment_type ENUM('professor', 'staff', 'part_time') NOT NULL DEFAULT 'professor'");
        } else {
            Schema::table('users', function (Blueprint $table) {
                $table->string('employment_type', 20)->default('professor')->change();
            });
        }
    }
};
