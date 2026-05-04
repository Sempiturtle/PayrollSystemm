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
        Schema::table('attendance_logs', function (Blueprint $table) {
            $table->string('source', 50)->default('RFID')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_logs', function (Blueprint $table) {
            // ENUM to VARCHAR change is tricky in some versions of SQLite/Laravel
            // but the most compatible way is to just define it as a string
            $table->string('source', 50)->default('RFID')->change();
        });
    }
};
