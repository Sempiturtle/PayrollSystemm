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
            // Add a temporary index on user_id to satisfy the foreign key requirement
            $table->index('user_id', 'temp_user_id_idx');
            
            // Now drop the old unique index
            $table->dropUnique('schedules_user_id_day_of_week_unique');
            
            // Create the new composite unique index
            $table->unique(['user_id', 'day_of_week', 'start_time']);
            
            // Drop the temporary index
            $table->dropIndex('temp_user_id_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            // Add temporary index on user_id
            $table->index('user_id', 'temp_user_id_idx');
            
            // Drop new unique index
            $table->dropUnique('schedules_user_id_day_of_week_start_time_unique');
            
            // Restore old unique index
            $table->unique(['user_id', 'day_of_week']);
            
            // Drop temporary index
            $table->dropIndex('temp_user_id_idx');
        });
    }
};
