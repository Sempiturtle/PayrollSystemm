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
        Schema::table('schedules', function (Blueprint $table) {
            // Add a plain index to satisfy the foreign key requirement
            $table->index('user_id');
            // Now we can drop the unique one
            $table->dropUnique(['user_id', 'day_of_week']);
            // Add our new broader one
            $table->unique(['user_id', 'day_of_week', 'start_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'day_of_week', 'start_time']);
            $table->unique(['user_id', 'day_of_week']);
        });
    }
};
