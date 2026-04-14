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
            // Statutory IDs
            $table->string('tin_id')->nullable()->after('employee_id');
            $table->string('sss_id')->nullable()->after('tin_id');
            $table->string('philhealth_id')->nullable()->after('sss_id');
            $table->string('pagibig_id')->nullable()->after('philhealth_id');

            // Leave Credits
            $table->decimal('sick_leave_credits', 8, 2)->default(0)->after('pagibig_id');
            $table->decimal('vacation_leave_credits', 8, 2)->default(0)->after('sick_leave_credits');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'tin_id', 'sss_id', 'philhealth_id', 'pagibig_id',
                'sick_leave_credits', 'vacation_leave_credits'
            ]);
        });
    }
};
