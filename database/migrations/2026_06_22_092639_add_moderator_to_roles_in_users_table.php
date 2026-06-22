<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * NOTE: `ALTER TABLE ... MODIFY COLUMN` is MySQL-specific and will fail on
     * SQLite (used by the test suite). We guard the statement so it only runs
     * on MySQL. SQLite stores ENUMs as TEXT and will accept any string value,
     * so `moderator` works in tests without an explicit schema change.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'moderator', 'professor', 'employee') DEFAULT 'employee';");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'professor', 'employee') DEFAULT 'employee';");
        }
    }
};

