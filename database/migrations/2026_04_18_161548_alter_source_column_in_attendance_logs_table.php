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
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE attendance_logs MODIFY COLUMN source VARCHAR(50) DEFAULT 'RFID'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_logs', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE attendance_logs MODIFY COLUMN source ENUM('RFID', 'Biometric') DEFAULT 'RFID'");
        });
    }
};
