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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('employment_type', ['professor', 'staff', 'part_time'])
                  ->default('professor')
                  ->after('role');
            $table->decimal('monthly_salary', 10, 2)
                  ->default(0)
                  ->after('hourly_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['employment_type', 'monthly_salary']);
        });
    }
};
